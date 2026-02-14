# Pruebas para Verificar las Correcciones

## 🔧 Cambios Realizados

### 1. Solución para Caracteres Especiales con Tildes
**Problema**: Los caracteres con tildes se mostraban como `\u00e9` en lugar de `é`

**Archivos modificados**:
- `app/Http/Middleware/SetJsonEncoding.php` (creado)
- `app/Http/Resources/ClienteResource.php`
- `app/Http/Resources/MascotaResource.php`
- `bootstrap/app.php`
- `.env` (agregado `DB_CHARSET` y `DB_COLLATION`)

### 2. Solución para Validación de DNI en Update
**Problema**: No se podía actualizar un cliente porque validaba el DNI contra sí mismo

**Archivo modificado**:
- `app/Http/Requests/UpdateClienteRequest.php`

**Cambio**: Corregida la obtención del ID desde `$this->route('cliente')` a `$this->route('id')`

---

## 📝 Instrucciones de Prueba

### Iniciar el Servidor
```bash
cd C:\Users\diego.porta\Documents\TEST_SPA\SPA-backend
php artisan serve
```

El servidor debería iniciar en: `http://localhost:8000`

---

## 🧪 Prueba 1: Verificar Caracteres Especiales

### GET - Listar todos los clientes
```bash
# PowerShell
Invoke-RestMethod -Uri "http://localhost:8000/api/clientes" -Method Get | ConvertTo-Json -Depth 10

# O usando curl
curl http://localhost:8000/api/clientes
```

**Resultado esperado**: 
Los nombres con tildes deben aparecer correctamente:
```json
{
  "id": 1,
  "nombres": "José",
  "apellidos": "Pérez García",
  "dni": "12345678"
}
```

**NO debe aparecer así**:
```json
{
  "apellidos": "P\u00e9rez Garc\u00eda"
}
```

---

## 🧪 Prueba 2: Validación de DNI en Update

### Escenario A: Actualizar cliente con su mismo DNI ✅ (Debe funcionar)

```bash
# PowerShell
$body = @{
    nombres = "José Manuel"
    apellidos = "Pérez García"
    dni = "12345678"  # El mismo DNI del cliente
    telefono = "999888777"
    email = "jose@example.com"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:8000/api/clientes/1" -Method Put -Body $body -ContentType "application/json"
```

**Resultado esperado**: ✅ El cliente se actualiza correctamente (status 200)

---

### Escenario B: Actualizar cliente con DNI de otro cliente ❌ (Debe fallar)

Supongamos que existe un cliente con ID=2 y DNI="87654321"

```bash
# PowerShell
$body = @{
    nombres = "José Manuel"
    apellidos = "Pérez García"
    dni = "87654321"  # DNI que ya pertenece a otro cliente
    telefono = "999888777"
    email = "jose@example.com"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:8000/api/clientes/1" -Method Put -Body $body -ContentType "application/json"
```

**Resultado esperado**: ❌ Error de validación (status 422)
```json
{
  "message": "El DNI ya está registrado en el sistema.",
  "errors": {
    "dni": ["El DNI ya está registrado en el sistema."]
  }
}
```

---

### Escenario C: Actualizar cliente con DNI nuevo ✅ (Debe funcionar)

```bash
# PowerShell
$body = @{
    nombres = "José Manuel"
    apellidos = "Pérez García"
    dni = "99999999"  # DNI nuevo que no existe en la base de datos
    telefono = "999888777"
    email = "jose@example.com"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:8000/api/clientes/1" -Method Put -Body $body -ContentType "application/json"
```

**Resultado esperado**: ✅ El cliente se actualiza correctamente (status 200)

---

## 🧪 Prueba 3: Crear Cliente con Tildes

```bash
# PowerShell
$body = @{
    nombres = "María José"
    apellidos = "Rodríguez López"
    dni = "11223344"
    telefono = "987654321"
    email = "maria@example.com"
    direccion = "Calle Américo Vespucio 123, Lima, Perú"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:8000/api/clientes" -Method Post -Body $body -ContentType "application/json"
```

**Resultado esperado**: El cliente se crea y la respuesta muestra correctamente:
```json
{
  "id": 3,
  "nombres": "María José",
  "apellidos": "Rodríguez López",
  "direccion": "Calle Américo Vespucio 123, Lima, Perú"
}
```

---

## ✅ Checklist de Verificación

- [ ] El servidor inicia sin errores
- [ ] Los caracteres con tildes se muestran correctamente en GET
- [ ] Los caracteres con tildes se muestran correctamente en POST
- [ ] Se puede actualizar un cliente manteniendo su mismo DNI
- [ ] No se puede actualizar un cliente usando el DNI de otro cliente
- [ ] Se puede actualizar un cliente con un DNI nuevo

---

## 🐛 Solución de Problemas

### Error: "Failed to open stream: No such file or directory"
```bash
composer install
```

### Error: "Base table or view not found"
```bash
php artisan migrate:fresh --seed
```

### Error con tildes persiste
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

### Verificar configuración de la base de datos
Asegúrate de que en `.env` esté:
```env
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
```

---

## 📚 Comandos Útiles

```bash
# Ver todas las rutas
php artisan route:list

# Ver rutas de clientes
php artisan route:list --path=api/clientes

# Limpiar cachés
php artisan optimize:clear

# Ejecutar tests
php artisan test

# Ver logs en tiempo real
Get-Content -Path "storage\logs\laravel.log" -Wait -Tail 50
```

