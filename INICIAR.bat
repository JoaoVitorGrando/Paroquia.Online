@echo off
title Paroquia Online
cd /d "%~dp0"
if exist "iniciar.ps1" del /f /q "iniciar.ps1"
if exist "CORRIGIR.bat" del /f /q "CORRIGIR.bat"
if exist "COMO-RODAR.md" del /f /q "COMO-RODAR.md"
powershell -NoProfile -ExecutionPolicy Bypass -Command "$c=[IO.File]::ReadAllText('%~f0');$i=$c.LastIndexOf('#::PS::');iex $c.Substring($i+7)"
echo.
echo O servidor foi encerrado.
pause
exit /b
#::PS::
$ErrorActionPreference = "Stop"
$ProgressPreference = "SilentlyContinue"
[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12
$raiz = (Get-Location).Path

function Info($m) { Write-Host ""; Write-Host "==> $m" -ForegroundColor Cyan }
function Aviso($m) { Write-Host "    $m" -ForegroundColor Yellow }

# ---------- helpers do .env ----------
function Read-EnvLines {
    if (-not (Test-Path ".env")) { return @() }
    return [System.IO.File]::ReadAllLines((Join-Path $raiz ".env"), [System.Text.Encoding]::UTF8)
}
function Write-EnvLines($lines) {
    $utf8NoBom = New-Object System.Text.UTF8Encoding($false)
    [System.IO.File]::WriteAllLines((Join-Path $raiz ".env"), [string[]]$lines, $utf8NoBom)
}
function Get-EnvValue($key) {
    foreach ($l in (Read-EnvLines)) {
        if ($l -match ("^\s*" + [regex]::Escape($key) + "\s*=\s*(.*)$")) {
            return $Matches[1].Trim().Trim('"')
        }
    }
    return $null
}
function Set-EnvValue($key, $value) {
    $lines = @(Read-EnvLines)
    $found = $false
    $out = @()
    foreach ($l in $lines) {
        if ($l -match ("^\s*" + [regex]::Escape($key) + "\s*=")) { $out += "$key=$value"; $found = $true }
        else { $out += $l }
    }
    if (-not $found) { $out += "$key=$value" }
    Write-EnvLines $out
}
function Disable-EnvKey($key) {
    $lines = @(Read-EnvLines)
    $out = @()
    foreach ($l in $lines) {
        if ($l -match ("^\s*" + [regex]::Escape($key) + "\s*=")) { $out += ("# " + $l) } else { $out += $l }
    }
    Write-EnvLines $out
}
function Use-Sqlite {
    Set-EnvValue "DB_CONNECTION" "sqlite"
    foreach ($k in @("DB_HOST", "DB_PORT", "DB_DATABASE", "DB_USERNAME", "DB_PASSWORD")) { Disable-EnvKey $k }
}

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
        if ($v -match '^8\.([1-9]|\d\d)') {
            $mods = & $cmd.Source -m 2>$null
            if ($mods -contains "pdo_sqlite") {
                $phpExe = $cmd.Source
                Info "Usando o PHP ja instalado no computador (versao $v)"
            } else {
                Aviso "O PHP instalado (versao $v) esta sem a extensao pdo_sqlite."
                Aviso "Vou usar uma copia portatil do PHP para nao mexer na sua instalacao."
            }
        }
    } catch {}
}
if (-not $phpExe -and (Test-Path (Join-Path $phpDir "php.exe"))) {
    $phpExe = Join-Path $phpDir "php.exe"
    Info "Usando o PHP portatil ja baixado em php-portatil\"
}
if (-not $phpExe) {
    Info "Baixando uma copia portatil do PHP (nao instala nada no Windows)..."
    $listUrl = "https://windows.php.net/downloads/releases/"
    $html = (Invoke-WebRequest -Uri $listUrl -UseBasicParsing).Content
    $file = $null
    foreach ($pat in @('php-8\.2\.\d+-nts-Win32-vs16-x64\.zip', 'php-8\.3\.\d+-nts-Win32-vs16-x64\.zip', 'php-8\.\d+\.\d+-nts-Win32-vs1[6-9]-x64\.zip')) {
        $m = [regex]::Matches($html, $pat)
        if ($m.Count -gt 0) { $file = $m[0].Value; break }
    }
    if (-not $file) { throw "Nao consegui localizar o pacote do PHP para Windows." }
    $zip = Join-Path $env:TEMP $file
    Write-Host "    baixando $file ..."
    Invoke-WebRequest -Uri ($listUrl + $file) -OutFile $zip -UseBasicParsing
    if (Test-Path $phpDir) { Remove-Item $phpDir -Recurse -Force }
    Expand-Archive -Path $zip -DestinationPath $phpDir -Force
    Remove-Item $zip -Force
    $phpExe = Join-Path $phpDir "php.exe"
    Info "PHP portatil instalado em php-portatil\"
}

# ---------- 2. php.ini (so para o PHP portatil) ----------
$ini = Join-Path $phpDir "php.ini"
if ((Test-Path $phpDir) -and $phpExe.StartsWith($phpDir) -and -not (Test-Path $ini)) {
    Copy-Item (Join-Path $phpDir "php.ini-development") $ini
    $c = Get-Content $ini -Raw
    $c = $c -replace '(?m)^;\s*extension_dir\s*=\s*"ext"', 'extension_dir = "ext"'
    foreach ($e in @('openssl', 'mbstring', 'fileinfo', 'pdo_sqlite', 'sqlite3', 'curl', 'zip', 'gd', 'pdo_mysql', 'mysqli', 'exif', 'intl')) {
        $c = $c -replace ('(?m)^;extension=' + $e + '\s*$'), ('extension=' + $e)
    }
    Set-Content -Path $ini -Value $c -Encoding ASCII
    Info "php.ini configurado (sqlite, mbstring, openssl, curl, zip...)"
}

# ---------- 3. Composer + dependencias ----------
if (-not (Test-Path ".\vendor\autoload.php")) {
    $composer = Join-Path $raiz "composer.phar"
    if (-not (Test-Path $composer)) {
        Info "Baixando o Composer..."
        Invoke-WebRequest -Uri "https://getcomposer.org/composer.phar" -OutFile $composer -UseBasicParsing
    }
    Info "Instalando as dependencias do Laravel (pode levar alguns minutos)..."
    & $phpExe $composer install --no-dev --no-interaction --prefer-dist --ignore-platform-req=ext-intl
    if ($LASTEXITCODE -ne 0) { throw "Falha ao rodar o composer install." }
} else {
    Info "Dependencias ja instaladas (pasta vendor)."
}

# ---------- 4. .env + chave ----------
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

# garante que o administrador exista mesmo em banco ja criado
& $phpExe artisan db:seed --class=AdminSeeder --force --quiet 2>$null | Out-Null

# ---------- 6. Link das imagens enviadas ----------
if (-not (Test-Path ".\public\storage")) {
    & $phpExe artisan storage:link --quiet 2>$null | Out-Null
}

# ---------- 7. Subir o servidor ----------
$porta = 8000
for ($p = 8000; $p -lt 8020; $p++) {
    try {
        $l = New-Object System.Net.Sockets.TcpListener([System.Net.IPAddress]::Loopback, $p)
        $l.Start(); $l.Stop(); $porta = $p; break
    } catch { }
}
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
