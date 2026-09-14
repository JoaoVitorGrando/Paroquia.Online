$ErrorActionPreference = "Stop"
$ProgressPreference = "SilentlyContinue"
[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12
Set-Location -Path $PSScriptRoot

function Info($m) { Write-Host ""; Write-Host "==> $m" -ForegroundColor Cyan }

Write-Host "==========================================" -ForegroundColor Yellow
Write-Host "   PAROQUIA ONLINE - instalar e rodar" -ForegroundColor Yellow
Write-Host "==========================================" -ForegroundColor Yellow

$phpDir = Join-Path $PSScriptRoot "php-portatil"
$phpExe = $null

# ---------- 1. PHP ----------
$cmd = Get-Command php -ErrorAction SilentlyContinue
if ($cmd) {
    try {
        $v = & $cmd.Source -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;" 2>$null
        if ($v -match '^8\.[1-9]') {
            $phpExe = $cmd.Source
            Info "Usando o PHP ja instalado no computador (versao $v)"
        }
    } catch {}
}
if (-not $phpExe -and (Test-Path (Join-Path $phpDir "php.exe"))) {
    $phpExe = Join-Path $phpDir "php.exe"
    Info "Usando o PHP portatil ja baixado em php-portatil\"
}
if (-not $phpExe) {
    Info "PHP nao encontrado. Baixando uma copia portatil (nao instala nada no Windows)..."
    $listUrl = "https://windows.php.net/downloads/releases/"
    $html = (Invoke-WebRequest -Uri $listUrl -UseBasicParsing).Content
    $file = $null
    foreach ($pat in @('php-8\.2\.\d+-nts-Win32-vs16-x64\.zip','php-8\.3\.\d+-nts-Win32-vs16-x64\.zip','php-8\.\d+\.\d+-nts-Win32-vs1[6-9]-x64\.zip')) {
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
    foreach ($e in @('openssl','mbstring','fileinfo','pdo_sqlite','sqlite3','curl','zip','gd','pdo_mysql','mysqli','exif','intl')) {
        $c = $c -replace ('(?m)^;extension=' + $e + '\s*$'), ('extension=' + $e)
    }
    Set-Content -Path $ini -Value $c -Encoding ASCII
    Info "php.ini configurado (sqlite, mbstring, openssl, curl, zip...)"
}

# ---------- 3. Composer + dependencias ----------
if (-not (Test-Path ".\vendor\autoload.php")) {
    $composer = Join-Path $PSScriptRoot "composer.phar"
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
if (-not (Test-Path ".env")) { Copy-Item ".env.example" ".env" }
$envc = Get-Content ".env" -Raw
if ($envc -notmatch '(?m)^APP_KEY=base64:') {
    Info "Gerando a chave da aplicacao..."
    & $phpExe artisan key:generate --force
}

# ---------- 5. Banco de dados (SQLite) ----------
$db = Join-Path $PSScriptRoot "database\database.sqlite"
if (-not (Test-Path $db) -or (Get-Item $db).Length -eq 0) {
    Info "Criando o banco SQLite e populando com os dados iniciais..."
    if (-not (Test-Path $db)) { New-Item -ItemType File -Path $db -Force | Out-Null }
    & $phpExe artisan migrate --seed --force
} else {
    Info "Banco de dados ja existe. Aplicando migrations pendentes..."
    & $phpExe artisan migrate --force
}

# ---------- 6. Subir o servidor ----------
Info "Iniciando o servidor em http://localhost:8000 ..."
Write-Host ""
Write-Host "    Site:   http://localhost:8000" -ForegroundColor Green
Write-Host "    Admin:  http://localhost:8000/admin" -ForegroundColor Green
Write-Host "    Login:  admin@paroquia.com  /  admin123" -ForegroundColor Green
Write-Host ""
Write-Host "    Para parar o servidor, feche esta janela ou aperte Ctrl+C." -ForegroundColor DarkGray
Write-Host ""
Start-Job -ScriptBlock { Start-Sleep -Seconds 5; Start-Process "http://localhost:8000" } | Out-Null
& $phpExe artisan serve --host=127.0.0.1 --port=8000
