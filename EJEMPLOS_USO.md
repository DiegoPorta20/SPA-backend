# Ejemplos de Uso de la API

Este documento contiene ejemplos prácticos de cómo usar la API de Clientes y Mascotas.

## Configuración Base

**Base URL:** `http://localhost:8000/api`

**Headers requeridos:**
```
Accept: application/json
Content-Type: application/json (para POST/PUT)
```

---

## 1. Listar todos los clientes

### Request
```bash
curl -X GET http://localhost:8000/api/clientes \
  -H "Accept: application/json"
```

### Response (200 OK)
```json
{
  "data": [
    {
      "id": 1,
      "nombres": "Juan Carlos",
      "apellidos": "Pérez García",
      "nombre_completo": "Juan Carlos Pérez García",
      "dni": "12345678",
      "telefono": "987654321",
      "email": "juan.perez@example.com",
      "direccion": "Av. Principal 123, Lima",
      "estado": "activo",
      "mascotas": [...],
      "mascotas_count": 2,
      "created_at": "2024-02-12T10:00:00.000000Z",
      "updated_at": "2024-02-12T10:00:00.000000Z"
    }
  ]
}
```

---

## 2. Obtener un cliente específico

### Request
```bash
curl -X GET http://localhost:8000/api/clientes/1 \
  -H "Accept: application/json"
```

### Response (200 OK)
```json
{
  "data": {
    "id": 1,
    "nombres": "Juan Carlos",
    "apellidos": "Pérez García",
    "nombre_completo": "Juan Carlos Pérez García",
    "dni": "12345678",
    "mascotas": [
      {
        "id": 1,
        "cliente_id": 1,
        "nombre": "Max",
        "especie": "Perro",
        "raza": "Golden Retriever",
        "edad": 3,
        "peso": 30.5,
        "sexo": "macho",
        "estado": "activo",
        "created_at": "2024-02-12T10:00:00.000000Z",
        "updated_at": "2024-02-12T10:00:00.000000Z"
      }
    ],
    "mascotas_count": 1
  }
}
```

### Response (404 Not Found)
```json
{
  "message": "Cliente no encontrado"
}
```

---

## 3. Crear cliente SIN mascotas

### Request
```bash
curl -X POST http://localhost:8000/api/clientes \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "nombres": "Carlos Alberto",
    "apellidos": "Gómez Fernández",
    "dni": "99887766",
    "telefono": "987654321",
    "email": "carlos.gomez@example.com",
    "direccion": "Av. Test 123, Lima",
    "estado": "activo"
  }'
```

### Response (201 Created)
```json
{
  "data": {
    "id": 6,
    "nombres": "Carlos Alberto",
    "apellidos": "Gómez Fernández",
    "dni": "99887766",
    "mascotas": [],
    "mascotas_count": 0
  }
}
```

---

## 4. Crear cliente CON mascotas

### Request
```bash
curl -X POST http://localhost:8000/api/clientes \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "nombres": "Laura Patricia",
    "apellidos": "Morales Castillo",
    "dni": "55443322",
    "telefono": "912345678",
    "email": "laura.morales@example.com",
    "direccion": "Jr. Las Palmeras 456, San Isidro",
    "estado": "activo",
    "mascotas": [
      {
        "nombre": "Toby",
        "especie": "Perro",
        "raza": "Beagle",
        "edad": 4,
        "peso": 12.5,
        "sexo": "macho",
        "estado": "activo"
      },
      {
        "nombre": "Mimi",
        "especie": "Gato",
        "raza": "Angora",
        "edad": 2,
        "peso": 3.8,
        "sexo": "hembra",
        "estado": "activo"
      }
    ]
  }'
```

### Response (201 Created)
```json
{
  "data": {
    "id": 7,
    "nombres": "Laura Patricia",
    "apellidos": "Morales Castillo",
    "dni": "55443322",
    "mascotas": [
      {
        "id": 15,
        "nombre": "Toby",
        "especie": "Perro"
      },
      {
        "id": 16,
        "nombre": "Mimi",
        "especie": "Gato"
      }
    ],
    "mascotas_count": 2
  }
}
```

---

## 5. Actualizar cliente y SINCRONIZAR mascotas

### Escenario: 
- Cliente tiene 2 mascotas (IDs: 1 y 2)
- Queremos: Actualizar mascota 1, eliminar mascota 2, agregar nueva mascota

### Request
```bash
curl -X PUT http://localhost:8000/api/clientes/1 \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "nombres": "Juan Carlos",
    "apellidos": "Pérez García",
    "dni": "12345678",
    "telefono": "987654321",
    "email": "juan.updated@example.com",
    "direccion": "Av. Principal 123, Lima - Actualizada",
    "estado": "activo",
    "mascotas": [
      {
        "id": 1,
        "nombre": "Max Actualizado",
        "especie": "Perro",
        "raza": "Golden Retriever",
        "edad": 4,
        "peso": 32.0,
        "sexo": "macho",
        "estado": "activo"
      },
      {
        "nombre": "Nueva Mascota",
        "especie": "Ave",
        "raza": "Loro",
        "edad": 1,
        "peso": 0.8,
        "sexo": "macho",
        "estado": "activo"
      }
    ]
  }'
```

### Resultado:
- ✅ Mascota ID 1: ACTUALIZADA (nombre cambia a "Max Actualizado")
- ❌ Mascota ID 2: ELIMINADA (soft delete) - no está en la lista
- ✅ Nueva mascota: CREADA (sin ID en el request)

### Response (200 OK)
```json
{
  "data": {
    "id": 1,
    "email": "juan.updated@example.com",
    "mascotas": [
      {
        "id": 1,
        "nombre": "Max Actualizado",
        "edad": 4
      },
      {
        "id": 17,
        "nombre": "Nueva Mascota",
        "especie": "Ave"
      }
    ],
    "mascotas_count": 2
  }
}
```

---

## 6. Eliminar cliente (Soft Delete)

### Request
```bash
curl -X DELETE http://localhost:8000/api/clientes/5 \
  -H "Accept: application/json"
```

### Response (200 OK)
```json
{
  "message": "Cliente eliminado exitosamente"
}
```

**Nota:** El cliente y sus mascotas son eliminados con soft delete (deleted_at se marca).

---

## 7. Manejo de Errores

### Error: DNI Duplicado

#### Request
```bash
curl -X POST http://localhost:8000/api/clientes \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "nombres": "Test",
    "apellidos": "Usuario",
    "dni": "12345678",
    "estado": "activo"
  }'
```

#### Response (422 Unprocessable Entity)
```json
{
  "message": "Los datos proporcionados no son válidos",
  "errors": {
    "dni": [
      "El DNI ya está registrado en el sistema."
    ]
  }
}
```

### Error: Campos Requeridos Faltantes

#### Request
```bash
curl -X POST http://localhost:8000/api/clientes \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "telefono": "987654321"
  }'
```

#### Response (422 Unprocessable Entity)
```json
{
  "message": "Los datos proporcionados no son válidos",
  "errors": {
    "nombres": [
      "El campo nombres es obligatorio."
    ],
    "apellidos": [
      "El campo apellidos es obligatorio."
    ],
    "dni": [
      "El campo DNI es obligatorio."
    ]
  }
}
```

### Error: Cliente No Encontrado

#### Response (404 Not Found)
```json
{
  "message": "Cliente no encontrado"
}
```

### Error: Servidor

#### Response (500 Internal Server Error)
```json
{
  "message": "Error al crear el cliente",
  "error": "Descripción del error (solo en modo debug)"
}
```

---

## 8. Casos de Uso Comunes

### Caso 1: Crear cliente con múltiples mascotas de diferentes especies

```json
{
  "nombres": "Ana Lucía",
  "apellidos": "Torres Vega",
  "dni": "32165498",
  "telefono": "955443322",
  "estado": "activo",
  "mascotas": [
    {
      "nombre": "Rex",
      "especie": "Perro",
      "raza": "Pastor Alemán",
      "edad": 5,
      "peso": 35.0,
      "sexo": "macho",
      "estado": "activo"
    },
    {
      "nombre": "Mishi",
      "especie": "Gato",
      "raza": "Siamés",
      "edad": 2,
      "peso": 4.5,
      "sexo": "hembra",
      "estado": "activo"
    },
    {
      "nombre": "Piolín",
      "especie": "Ave",
      "raza": "Canario",
      "edad": 1,
      "peso": 0.3,
      "sexo": "macho",
      "estado": "activo"
    }
  ]
}
```

### Caso 2: Actualizar solo datos del cliente (sin modificar mascotas)

```json
{
  "nombres": "Juan Carlos",
  "apellidos": "Pérez García",
  "dni": "12345678",
  "telefono": "999888777",
  "email": "nuevo.email@example.com",
  "direccion": "Nueva dirección actualizada",
  "estado": "activo",
  "mascotas": []
}
```

**Nota:** Si envías `mascotas: []` ELIMINARÁ todas las mascotas. Si no quieres modificar mascotas, NO incluyas el campo.

### Caso 3: Cambiar estado de cliente a inactivo

```json
{
  "nombres": "Juan Carlos",
  "apellidos": "Pérez García",
  "dni": "12345678",
  "estado": "inactivo"
}
```

---

## 9. Testing con PowerShell

### Listar clientes
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/api/clientes" -Method Get -Headers @{"Accept"="application/json"}
```

### Crear cliente
```powershell
$body = @{
    nombres = "Test PowerShell"
    apellidos = "Usuario PS"
    dni = "11223344"
    estado = "activo"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:8000/api/clientes" -Method Post -Body $body -ContentType "application/json" -Headers @{"Accept"="application/json"}
```

---

## 10. Validaciones Implementadas

### Cliente
- ✅ `nombres`: Requerido, string, máx 100 caracteres
- ✅ `apellidos`: Requerido, string, máx 100 caracteres
- ✅ `dni`: Requerido, único, solo números, máx 20 caracteres
- ✅ `telefono`: Opcional, string, máx 20 caracteres
- ✅ `email`: Opcional, email válido, máx 150 caracteres
- ✅ `direccion`: Opcional, text, máx 500 caracteres
- ✅ `estado`: Opcional, enum (activo/inactivo)

### Mascota
- ✅ `nombre`: Requerido, string, máx 100 caracteres
- ✅ `especie`: Requerido, string, máx 50 caracteres
- ✅ `raza`: Opcional, string, máx 100 caracteres
- ✅ `edad`: Opcional, integer, min 0, max 100
- ✅ `peso`: Opcional, numeric, min 0, max 9999.99
- ✅ `sexo`: Opcional, enum (macho/hembra)
- ✅ `estado`: Opcional, enum (activo/inactivo)

---

**Documentación completa:** Ver `API_DOCUMENTATION.md`

