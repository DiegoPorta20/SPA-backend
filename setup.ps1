# Script de instalación automática para el proyecto
# Sistema de Gestión de Clientes y Mascotas

Write-Host "================================================" -ForegroundColor Cyan
Write-Host "  API Clientes y Mascotas - Instalación" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

# 1. Verificar si existe .env
Write-Host "[1/6] Verificando archivo .env..." -ForegroundColor Yellow
if (-Not (Test-Path ".env")) {
    Write-Host "  Creando .env desde .env.example..." -ForegroundColor Gray
    Copy-Item ".env.example" ".env"
    Write-Host "  ✓ Archivo .env creado" -ForegroundColor Green
} else {
    Write-Host "  ✓ Archivo .env ya existe" -ForegroundColor Green
}
Write-Host ""

# 2. Instalar dependencias
Write-Host "[2/6] Instalando dependencias de Composer..." -ForegroundColor Yellow
composer install --no-interaction
if ($LASTEXITCODE -eq 0) {
    Write-Host "  ✓ Dependencias instaladas correctamente" -ForegroundColor Green
} else {
    Write-Host "  ✗ Error al instalar dependencias" -ForegroundColor Red
    exit 1
}
Write-Host ""

# 3. Generar APP_KEY
Write-Host "[3/6] Generando APP_KEY..." -ForegroundColor Yellow
php artisan key:generate
if ($LASTEXITCODE -eq 0) {
    Write-Host "  ✓ APP_KEY generada correctamente" -ForegroundColor Green
} else {
    Write-Host "  ✗ Error al generar APP_KEY" -ForegroundColor Red
    exit 1
}
Write-Host ""

# 4. Crear base de datos
Write-Host "[4/6] Configurando base de datos..." -ForegroundColor Yellow
Write-Host "  IMPORTANTE: Asegúrate de que MySQL esté corriendo" -ForegroundColor Cyan
Write-Host "  y que hayas creado la base de datos 'clientes_mascotas_db'" -ForegroundColor Cyan
Write-Host ""
$continue = Read-Host "  ¿Continuar con las migraciones? (s/n)"
if ($continue -ne "s") {
    Write-Host "  Instalación pausada. Ejecuta 'php artisan migrate --seed' cuando estés listo." -ForegroundColor Yellow
    exit 0
}
Write-Host ""

# 5. Ejecutar migraciones
Write-Host "[5/6] Ejecutando migraciones..." -ForegroundColor Yellow
php artisan migrate --force
if ($LASTEXITCODE -eq 0) {
    Write-Host "  ✓ Migraciones ejecutadas correctamente" -ForegroundColor Green
} else {
    Write-Host "  ✗ Error al ejecutar migraciones" -ForegroundColor Red
    Write-Host "  Verifica la configuración de la base de datos en .env" -ForegroundColor Yellow
    exit 1
}
Write-Host ""

# 6. Ejecutar seeders
Write-Host "[6/6] Ejecutando seeders (datos de prueba)..." -ForegroundColor Yellow
php artisan db:seed --force
if ($LASTEXITCODE -eq 0) {
    Write-Host "  ✓ Seeders ejecutados correctamente" -ForegroundColor Green
} else {
    Write-Host "  ✗ Error al ejecutar seeders" -ForegroundColor Red
    exit 1
}
Write-Host ""

# Finalización
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "  ✓ INSTALACIÓN COMPLETADA" -ForegroundColor Green
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Para iniciar el servidor de desarrollo, ejecuta:" -ForegroundColor Yellow
Write-Host "  php artisan serve" -ForegroundColor White
Write-Host ""
Write-Host "La API estará disponible en:" -ForegroundColor Yellow
Write-Host "  http://localhost:8000/api/clientes" -ForegroundColor White
Write-Host ""
Write-Host "Para ejecutar los tests:" -ForegroundColor Yellow
Write-Host "  php artisan test" -ForegroundColor White
Write-Host ""
Write-Host "Documentación completa en: API_DOCUMENTATION.md" -ForegroundColor Cyan
Write-Host ""

