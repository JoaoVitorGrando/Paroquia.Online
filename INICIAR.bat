@echo off
title Paroquia Online
cd /d "%~dp0"
powershell -NoProfile -ExecutionPolicy Bypass -Command "$c=[IO.File]::ReadAllText('%~f0');$i=$c.LastIndexOf('#::PS::');iex $c.Substring($i+7)"
if errorlevel 1 (
  echo.
  echo A instalacao nao pode ser concluida. A mensagem acima explica o motivo.
) else (
  echo.
  echo O servidor foi encerrado.
)
pause
exit /b
#::PS::
$ErrorActionPreference = "Stop"
$ProgressPreference = "SilentlyContinue"
[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12
$raiz = (Get-Location).Path

function Info($m)  { Write-Host ""; Write-Host "==> $m" -ForegroundColor Cyan }
function Aviso($m) { Write-Host "    $m" -ForegroundColor Yellow }
function Erro($m)  { Write-Host "    $m" -ForegroundColor Red }

# Arquivos temporarios que este script pode criar. Sao apagados no final,
# mesmo que algo de errado no meio do caminho.
$temporarios = @(
    (Join-Path $raiz "_checar_banco.php")
)
function Limpar-Temporarios {
    foreach ($t in $temporarios) {
        if (Test-Path $t) { Remove-Item $t -Force -ErrorAction SilentlyContinue }
    }
}
# Se a execucao anterior foi interrompida, comeca limpando.
Limpar-Temporarios

try {

Write-Host "==========================================" -ForegroundColor Yellow
Write-Host "   PAROQUIA ONLINE - instalar e rodar" -ForegroundColor Yellow
Write-Host "==========================================" -ForegroundColor Yellow

$phpDir = Join-Path $raiz "php-portatil"
$phpExe = $null

# ---------- 1. PHP (precisa de versao 8.1+ e da extensao pdo_sqlite) ----------
$cmd = Get-Command php -ErrorAction SilentlyContinue
if ($cmd) {
    try {
        $v = & $cmd.Source -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;" 2>$null
        if ($v -match '^8\.([1-9]|\d\d)' -or $v -match '^9\.') {
            $mods = & $cmd.Source -m 2>$null
            if ($mods -contains "pdo_sqlite") {
                $phpExe = $cmd.Source
                Info "Usando o PHP ja instalado no computador (versao $v)"
            } else {
                Aviso "O PHP instalado (versao $v) esta sem a extensao pdo_sqlite."
                Aviso "Vou usar uma copia portatil do PHP, sem mexer na sua instalacao."
            }
        } else {
            Aviso "O PHP instalado (versao $v) e antigo demais para o Laravel 10."
            Aviso "Vou usar uma copia portatil do PHP, sem mexer na sua instalacao."
        }
    } catch { }
}
if (-not $phpExe -and (Test-Path (Join-Path $phpDir "php.exe"))) {
    $phpExe = Join-Path $phpDir "php.exe"
    Info "Usando o PHP portatil ja baixado em php-portatil\"
}
if (-not $phpExe) {
    Info "Baixando uma copia portatil do PHP (nao instala nada no Windows)..."

    # Procura um pacote do PHP para Windows, primeiro entre as versoes atuais e
    # depois no acervo. Os padroes vao do mais especifico ao mais generico, para
    # o script continuar funcionando conforme novas versoes forem lancadas.
    $fontes = @(
        "https://windows.php.net/downloads/releases/",
        "https://windows.php.net/downloads/releases/archives/"
    )
    $padroes = @(
        'php-8\.[234]\.\d+-nts-Win32-vs1[67]-x64\.zip',
        'php-8\.\d+\.\d+-nts-Win32-vs\d\d-x64\.zip',
        'php-[89]\.\d+\.\d+-nts-Win32-[a-z0-9]+-x64\.zip'
    )
    $arquivo = $null
    $base    = $null
    foreach ($fonte in $fontes) {
        try { $html = (Invoke-WebRequest -Uri $fonte -UseBasicParsing -TimeoutSec 40).Content }
        catch { Aviso "Nao consegui ler $fonte"; continue }
        foreach ($padrao in $padroes) {
            $m = [regex]::Matches($html, $padrao)
            if ($m.Count -gt 0) {
                # Entre os encontrados, fica com o nome mais recente em ordem alfabetica
                $arquivo = ($m | ForEach-Object { $_.Value } | Sort-Object -Unique | Select-Object -Last 1)
                $base = $fonte
                break
            }
        }
        if ($arquivo) { break }
    }

    if (-not $arquivo) {
        Erro "Nao foi possivel localizar um pacote do PHP para Windows."
        Erro "Instale o PHP 8.1 ou superior manualmente, a partir de"
        Erro "https://windows.php.net/download/ , e rode este arquivo de novo."
        Erro "Na instalacao, habilite a extensao pdo_sqlite no php.ini."
        throw "PHP nao encontrado e download indisponivel."
    }

    $zip = Join-Path $env:TEMP $arquivo
    Write-Host "    baixando $arquivo ..."
    try {
        Invoke-WebRequest -Uri ($base + $arquivo) -OutFile $zip -UseBasicParsing -TimeoutSec 300
        $temp = Join-Path $env:TEMP ("php-tmp-" + [guid]::NewGuid().ToString("N"))
        Expand-Archive -Path $zip -DestinationPath $temp -Force

        # Alguns pacotes vem com tudo dentro de uma subpasta. Acha onde esta o php.exe.
        $achado = Get-ChildItem -Path $temp -Filter "php.exe" -Recurse | Select-Object -First 1
        if (-not $achado) { throw "O pacote baixado nao contem php.exe." }
        $origem = $achado.Directory.FullName

        if (Test-Path $phpDir) { Remove-Item $phpDir -Recurse -Force }
        Move-Item -Path $origem -Destination $phpDir
        $phpExe = Join-Path $phpDir "php.exe"
        Info "PHP portatil instalado em php-portatil\"
    } finally {
        if (Test-Path $zip) { Remove-Item $zip -Force -ErrorAction SilentlyContinue }
        if ($temp -and (Test-Path $temp)) { Remove-Item $temp -Recurse -Force -ErrorAction SilentlyContinue }
    }
}

# ---------- 2. php.ini (so para o PHP portatil) ----------
$ini = Join-Path $phpDir "php.ini"
if ((Test-Path $phpDir) -and $phpExe.StartsWith($phpDir) -and -not (Test-Path $ini)) {
    $modelo = Join-Path $phpDir "php.ini-development"
    if (Test-Path $modelo) {
        Copy-Item $modelo $ini
        $c = Get-Content $ini -Raw
        $c = $c -replace '(?m)^;\s*extension_dir\s*=\s*"ext"', 'extension_dir = "ext"'
        foreach ($e in @('openssl','mbstring','fileinfo','pdo_sqlite','sqlite3','curl','zip','gd','pdo_mysql','mysqli','exif','intl')) {
            $c = $c -replace ('(?m)^;extension=' + $e + '\s*$'), ('extension=' + $e)
        }
        Set-Content -Path $ini -Value $c -Encoding ASCII
        Info "php.ini configurado (sqlite, mbstring, openssl, curl, zip...)"
    }
}

# Confere se o PHP escolhido realmente consegue falar com o SQLite.
$temSqlite = (& $phpExe -m 2>$null) -contains "pdo_sqlite"
if (-not $temSqlite) {
    Erro "O PHP disponivel esta sem a extensao pdo_sqlite e o sistema nao roda sem ela."
    Erro "Habilite a linha extension=pdo_sqlite no php.ini e rode este arquivo de novo."
    throw "Extensao pdo_sqlite ausente."
}

# ---------- 3. Composer + dependencias ----------
if (-not (Test-Path ".\vendor\autoload.php")) {
    $composer = Join-Path $raiz "composer.phar"
    if (-not (Test-Path $composer)) {
        Info "Baixando o Composer..."
        Invoke-WebRequest -Uri "https://getcomposer.org/composer.phar" -OutFile $composer -UseBasicParsing -TimeoutSec 120
    }
    Info "Instalando as dependencias do Laravel (pode levar alguns minutos)..."
    & $phpExe $composer install --no-dev --no-interaction --prefer-dist --ignore-platform-req=ext-intl
    if ($LASTEXITCODE -ne 0) {
        Erro "O composer install falhou. Verifique a conexao com a internet."
        throw "Falha ao instalar as dependencias."
    }
} else {
    Info "Dependencias ja instaladas (pasta vendor)."
}

# ---------- 4. .env + chave da aplicacao ----------
function Read-EnvLines {
    if (-not (Test-Path ".env")) { return @() }
    return [System.IO.File]::ReadAllLines((Join-Path $raiz ".env"), [System.Text.Encoding]::UTF8)
}
function Write-EnvLines($lines) {
    $semBom = New-Object System.Text.UTF8Encoding($false)
    [System.IO.File]::WriteAllLines((Join-Path $raiz ".env"), [string[]]$lines, $semBom)
}
function Get-EnvValue($key) {
    foreach ($l in (Read-EnvLines)) {
        if ($l -match ("^\s*" + [regex]::Escape($key) + "\s*=\s*(.*)$")) { return $Matches[1].Trim().Trim('"') }
    }
    return $null
}
function Set-EnvValue($key, $value) {
    $out = @(); $achou = $false
    foreach ($l in @(Read-EnvLines)) {
        if ($l -match ("^\s*" + [regex]::Escape($key) + "\s*=")) { $out += "$key=$value"; $achou = $true }
        else { $out += $l }
    }
    if (-not $achou) { $out += "$key=$value" }
    Write-EnvLines $out
}
function Disable-EnvKey($key) {
    $out = @()
    foreach ($l in @(Read-EnvLines)) {
        if ($l -match ("^\s*" + [regex]::Escape($key) + "\s*=")) { $out += ("# " + $l) } else { $out += $l }
    }
    Write-EnvLines $out
}
function Use-Sqlite {
    Set-EnvValue "DB_CONNECTION" "sqlite"
    foreach ($k in @("DB_HOST","DB_PORT","DB_DATABASE","DB_USERNAME","DB_PASSWORD")) { Disable-EnvKey $k }
}

if (-not (Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
    Info ".env criado a partir do .env.example"
}
if (-not (Get-EnvValue "DB_CONNECTION")) { Use-Sqlite }
if ((Get-Content ".env" -Raw) -notmatch '(?m)^APP_KEY=base64:') {
    Info "Gerando a chave da aplicacao..."
    & $phpExe artisan key:generate --force
}
& $phpExe artisan config:clear --quiet 2>$null | Out-Null

# ---------- 5. Banco de dados ----------
$conexao = Get-EnvValue "DB_CONNECTION"

if ($conexao -ne "sqlite") {
    Info "Testando a conexao com o $conexao..."
    $probe = Join-Path $raiz "_checar_banco.php"
    @'
<?php
require __DIR__ . "/vendor/autoload.php";
$app = require __DIR__ . "/bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
try {
    Illuminate\Support\Facades\DB::connection()->getPdo();
    exit(0);
} catch (Throwable $e) {
    exit(1);
}
'@ | Set-Content -Path $probe -Encoding ASCII
    & $phpExe $probe 2>$null | Out-Null
    $okBanco = ($LASTEXITCODE -eq 0)
    Remove-Item $probe -Force -ErrorAction SilentlyContinue
    if ($okBanco) {
        Info "Conectado no $conexao."
    } else {
        Aviso "Nao foi possivel conectar no $conexao (servidor desligado ou senha diferente)."
        Aviso "Trocando para SQLite, que nao precisa de instalacao."
        Use-Sqlite
        $conexao = "sqlite"
        & $phpExe artisan config:clear --quiet 2>$null | Out-Null
    }
}

if ($conexao -eq "sqlite") {
    $db = Join-Path $raiz "database\database.sqlite"
    if (-not (Test-Path $db)) { New-Item -ItemType File -Path $db -Force | Out-Null }
    $novo = ((Get-Item $db).Length -eq 0)
} else {
    $novo = $false
}

if ($novo) {
    Info "Criando o banco e populando com os dados iniciais..."
    & $phpExe artisan migrate --seed --force
} else {
    Info "Aplicando migrations pendentes..."
    & $phpExe artisan migrate --force
}
if ($LASTEXITCODE -ne 0) { throw "Falha ao preparar o banco de dados." }

# Garante que a conta de administrador exista, mesmo em banco ja criado.
& $phpExe artisan db:seed --class=AdminSeeder --force --quiet 2>$null | Out-Null

# ---------- 6. Pastas de upload ----------
foreach ($pasta in @("public\uploads\eventos", "public\uploads\grupos")) {
    $caminho = Join-Path $raiz $pasta
    if (-not (Test-Path $caminho)) { New-Item -ItemType Directory -Path $caminho -Force | Out-Null }
}

# ---------- 7. Subir o servidor ----------
$porta = $null
for ($p = 8000; $p -lt 8020; $p++) {
    try {
        $l = New-Object System.Net.Sockets.TcpListener([System.Net.IPAddress]::Loopback, $p)
        $l.Start(); $l.Stop(); $porta = $p; break
    } catch { }
}
if (-not $porta) { throw "Nenhuma porta livre entre 8000 e 8019." }
if ($porta -ne 8000) { Aviso "A porta 8000 estava ocupada. Usando a porta $porta." }

$adminEmail = Get-EnvValue "ADMIN_EMAIL"; if (-not $adminEmail) { $adminEmail = "admin@paroquia.com" }
$adminSenha = Get-EnvValue "ADMIN_SENHA"; if (-not $adminSenha) { $adminSenha = "trocar-esta-senha" }
$url = "http://localhost:$porta"

Info "Iniciando o servidor em $url ..."
Write-Host ""
Write-Host "    Site:   $url" -ForegroundColor Green
Write-Host "    Admin:  $url/admin" -ForegroundColor Green
Write-Host "    Login:  $adminEmail  /  $adminSenha" -ForegroundColor Green
Write-Host "    Banco:  $conexao" -ForegroundColor Green
Write-Host ""
Write-Host "    Para parar o servidor, feche esta janela ou aperte Ctrl+C." -ForegroundColor DarkGray
Write-Host ""
Start-Job -ScriptBlock { param($u) Start-Sleep -Seconds 5; Start-Process $u } -ArgumentList $url | Out-Null
& $phpExe artisan serve --host=127.0.0.1 --port=$porta

}
catch {
    Write-Host ""
    Erro $_.Exception.Message
    Limpar-Temporarios
    exit 1
}
finally {
    Limpar-Temporarios
}
