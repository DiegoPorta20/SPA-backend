# API REST - Sistema de Gestión de Clientes y Mascotas

> **Backend desarrollado con Laravel 12** - Solución profesional para el mantenimiento de clientes y sus mascotas asociadas.

## 📋 Tabla de Contenidos

- [Características](#características)
- [Arquitectura](#arquitectura)
- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Configuración](#configuración)
- [Endpoints API](#endpoints-api)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Pruebas](#pruebas)
- [Buenas Prácticas Implementadas](#buenas-prácticas-implementadas)

---

## 🚀 Características

### Backend API RESTful
- ✅ **API RESTful** completa con Laravel 12
- ✅ **Eloquent ORM** para manejo de datos
- ✅ **Transacciones DB** para integridad de datos
- ✅ **FormRequests** con validaciones robustas
- ✅ **API Resources** para formateo consistente de respuestas
- ✅ **Soft Deletes** implementado
- ✅ **DTOs** para transferencia de datos limpia
- ✅ **Service Layer** separando lógica de negocio
- ✅ **Exception Handler** personalizado
- ✅ **Códigos HTTP correctos** (200, 201, 404, 422, 500)

### Funcionalidades Específicas
- Sincronización inteligente de mascotas (crear, actualizar, eliminar)
- Validación de DNI único con manejo correcto en updates
- Relaciones Eloquent (1:N) entre Cliente y Mascota
- Sistema de estados (activo/inactivo)
- Logs estructurados de operaciones
- Seeders con datos de prueba
- Tests automatizados

---

## 🏗 Arquitectura

```
┌─────────────┐
│  Controller │  ← FormRequests (Validación)
└──────┬──────┘
       │
       ▼
┌─────────────┐
│   Service   │  ← DTOs (Transferencia de Datos)
└──────┬──────┘
       │
       ▼
┌─────────────┐
│    Model    │  ← Eloquent ORM
└──────┬──────┘
       │
       ▼
┌─────────────┐
│  Database   │  ← MySQL
└─────────────┘
```

### Capas de la Aplicación

1. **Controllers** (`app/Http/Controllers/Api`)
   - Manejo de requests HTTP
   - Delegación a servicios
   - Formateo de respuestas con Resources

2. **Services** (`app/Services`)
   - Lógica de negocio
   - Transacciones de base de datos
   - Coordinación entre modelos

3. **DTOs** (`app/DTOs`)
   - Transferencia de datos tipada
   - Transformación de datos

4. **Models** (`app/Models`)
   - Representación de entidades
   - Relaciones Eloquent
   - Scopes y Accessors

5. **Requests** (`app/Http/Requests`)
   - Validaciones centralizadas
   - Reglas de negocio

6. **Resources** (`app/Http/Resources`)
   - Formateo de respuestas JSON
   - Control de datos expuestos

---

## 💻 Requisitos

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Laravel 12.x

---

## 📦 Instalación

### 1. Clonar repositorio

```bash
cd backend_test
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar variables de entorno

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurar base de datos

Edita el archivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clientes_mascotas_db
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Ejecutar migraciones y seeders

```bash
php artisan migrate --seed
```

### 6. Iniciar servidor de desarrollo

```bash
php artisan serve
```

La API estará disponible en: `http://localhost:8000`

---

## 🗄️ Modelo de Datos

### Tabla: `clientes`

| Campo      | Tipo         | Descripción              |
|------------|--------------|--------------------------|
| id         | BIGINT       | Primary Key              |
| nombres    | VARCHAR(100) | Nombres del cliente      |
| apellidos  | VARCHAR(100) | Apellidos del cliente    |
| dni        | VARCHAR(20)  | DNI único                |
| telefono   | VARCHAR(20)  | Teléfono (nullable)      |
| email      | VARCHAR(150) | Email (nullable)         |
| direccion  | TEXT         | Dirección (nullable)     |
| estado     | ENUM         | activo/inactivo          |
| created_at | TIMESTAMP    |                          |
| updated_at | TIMESTAMP    |                          |
| deleted_at | TIMESTAMP    | Soft delete              |

### Tabla: `mascotas`

| Campo      | Tipo         | Descripción              |
|------------|--------------|--------------------------|
| id         | BIGINT       | Primary Key              |
| cliente_id | BIGINT       | Foreign Key              |
| nombre     | VARCHAR(100) | Nombre de la mascota     |
| especie    | VARCHAR(50)  | Perro, Gato, etc.        |
| raza       | VARCHAR(100) | Raza (nullable)          |
| edad       | INT          | Edad en años (nullable)  |
| peso       | DECIMAL(6,2) | Peso en kg (nullable)    |
| sexo       | ENUM         | macho/hembra (nullable)  |
| estado     | ENUM         | activo/inactivo          |
| created_at | TIMESTAMP    |                          |
| updated_at | TIMESTAMP    |                          |
| deleted_at | TIMESTAMP    | Soft delete              |

**Relación:** Un Cliente tiene muchas Mascotas (1:N)

---

## 🔌 Endpoints API

Base URL: `http://localhost:8000/api`

### 1. Listar todos los clientes

```http
GET /api/clientes?page={page}&per_page={per_page}&search={search}
```

**Query Parameters:**

| Parámetro  | Tipo    | Requerido | Default | Descripción                                          |
|------------|---------|-----------|---------|------------------------------------------------------|
| `page`     | integer | No        | 1       | Número de página                                     |
| `per_page` | integer | No        | 15      | Cantidad de registros por página                     |
| `search`   | string  | No        | ''      | Búsqueda por DNI, nombres, apellidos, email o nombre completo |

**Ejemplos:**
```bash
# Listar todos los clientes (página 1, 15 por página)
GET /api/clientes

# Buscar por DNI
GET /api/clientes?search=4567

# Buscar por nombre
GET /api/clientes?search=Juan

# Buscar con paginación personalizada
GET /api/clientes?page=1&per_page=10&search=María
```

**Response: 200 OK**

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
      "mascotas_count": 1,
      "created_at": "2024-02-12T10:00:00.000000Z",
      "updated_at": "2024-02-12T10:00:00.000000Z"
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/clientes?page=1",
    "last": "http://localhost:8000/api/clientes?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "path": "http://localhost:8000/api/clientes",
    "per_page": 15,
    "to": 5,
    "total": 5
  }
}
```

**Campos de Búsqueda:**
- ✅ DNI (parcial)
- ✅ Nombres (parcial)
- ✅ Apellidos (parcial)
- ✅ Email (parcial)
- ✅ Nombre Completo (nombres + apellidos)

### 2. Obtener un cliente específico

```http
GET /api/clientes/{id}
```

**Response: 200 OK** | **404 Not Found**

```json
{
  "data": {
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
}
```

### 3. Crear un nuevo cliente (con o sin mascotas)

```http
POST /api/clientes
Content-Type: application/json
```

**Request Body:**

```json
{
  "nombres": "María Elena",
  "apellidos": "Rodríguez López",
  "dni": "87654321",
  "telefono": "912345678",
  "email": "maria.rodriguez@example.com",
  "direccion": "Jr. Los Olivos 456, Miraflores",
  "estado": "activo",
  "mascotas": [
    {
      "nombre": "Rocky",
      "especie": "Perro",
      "raza": "Labrador",
      "edad": 5,
      "peso": 35.0,
      "sexo": "macho",
      "estado": "activo"
    },
    {
      "nombre": "Luna",
      "especie": "Gato",
      "raza": "Persa",
      "edad": 2,
      "peso": 4.2,
      "sexo": "hembra",
      "estado": "activo"
    }
  ]
}
```

**Response: 201 Created**

```json
{
  "data": {
    "id": 2,
    "nombres": "María Elena",
    "apellidos": "Rodríguez López",
    "dni": "87654321",
    "mascotas": [...],
    "mascotas_count": 2,
    ...
  }
}
```

**Response: 422 Unprocessable Entity** (Errores de validación)

```json
{
  "message": "Los datos proporcionados no son válidos",
  "errors": {
    "dni": ["El DNI ya está registrado en el sistema."],
    "email": ["El email debe ser una dirección de correo válida."]
  }
}
```

### 4. Actualizar un cliente (sincroniza mascotas)

```http
PUT /api/clientes/{id}
Content-Type: application/json
```

**Request Body:**

```json
{
  "nombres": "María Elena",
  "apellidos": "Rodríguez López",
  "dni": "87654321",
  "telefono": "912345678",
  "email": "maria.updated@example.com",
  "direccion": "Nueva dirección 789",
  "estado": "activo",
  "mascotas": [
    {
      "id": 1,
      "nombre": "Rocky Updated",
      "especie": "Perro",
      "raza": "Labrador",
      "edad": 6,
      "peso": 36.0,
      "sexo": "macho",
      "estado": "activo"
    },
    {
      "nombre": "Nueva Mascota",
      "especie": "Ave",
      "raza": "Loro",
      "edad": 1,
      "peso": 0.5,
      "sexo": "hembra",
      "estado": "activo"
    }
  ]
}
```

**Lógica de Sincronización:**
- Mascotas **con `id`**: Se actualizan
- Mascotas **sin `id`**: Se crean nuevas
- Mascotas **no enviadas**: Se eliminan (soft delete)

**Response: 200 OK** | **404 Not Found** | **422 Unprocessable Entity**

### 5. Eliminar un cliente (soft delete)

```http
DELETE /api/clientes/{id}
```

**Response: 200 OK**

```json
{
  "message": "Cliente eliminado exitosamente"
}
```

**Response: 404 Not Found**

```json
{
  "message": "Cliente no encontrado"
}
```

---

## 📁 Estructura del Proyecto

```
backend_test/
├── app/
│   ├── DTOs/
│   │   ├── ClienteDTO.php
│   │   └── MascotaDTO.php
│   ├── Exceptions/
│   │   └── Handler.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       └── ClienteController.php
│   │   ├── Requests/
│   │   │   ├── StoreClienteRequest.php
│   │   │   └── UpdateClienteRequest.php
│   │   └── Resources/
│   │       ├── ClienteResource.php
│   │       └── MascotaResource.php
│   ├── Models/
│   │   ├── Cliente.php
│   │   └── Mascota.php
│   └── Services/
│       ├── ClienteService.php
│       └── MascotaService.php
├── database/
│   ├── factories/
│   │   ├── ClienteFactory.php
│   │   └── MascotaFactory.php
│   ├── migrations/
│   │   ├── 2024_02_12_000001_create_clientes_table.php
│   │   └── 2024_02_12_000002_create_mascotas_table.php
│   └── seeders/
│       ├── ClienteSeeder.php
│       └── DatabaseSeeder.php
├── routes/
│   └── api.php
└── tests/
    └── Feature/
        └── ClienteApiTest.php
```

---

## 🧪 Pruebas

### Ejecutar todos los tests

```bash
php artisan test
```

### Ejecutar tests específicos

```bash
php artisan test --filter ClienteApiTest
```

### Tests implementados

- ✅ Listar todos los clientes
- ✅ Obtener cliente individual
- ✅ Crear cliente sin mascotas
- ✅ Crear cliente con mascotas
- ✅ Actualizar cliente y sincronizar mascotas
- ✅ Eliminar cliente (soft delete)
- ✅ Validación de DNI duplicado
- ✅ Validación de campos requeridos
- ✅ Manejo de errores 404

---

## ✨ Buenas Prácticas Implementadas

### 1. **Separación de Responsabilidades**
- Controllers: Solo manejo de HTTP
- Services: Lógica de negocio
- Models: Acceso a datos
- DTOs: Transferencia de datos

### 2. **Transacciones de Base de Datos**
```php
DB::transaction(function () {
    // Operaciones atómicas
    $cliente->save();
    $cliente->mascotas()->createMany($mascotas);
});
```

### 3. **Validaciones con FormRequests**
- Validaciones centralizadas
- Mensajes personalizados en español
- Reglas específicas por operación (Store/Update)

### 4. **Códigos HTTP Correctos**
- `200 OK`: Operaciones exitosas
- `201 Created`: Recurso creado
- `404 Not Found`: Recurso no encontrado
- `422 Unprocessable Entity`: Errores de validación
- `500 Internal Server Error`: Errores del servidor

### 5. **Soft Deletes**
- No se eliminan registros físicamente
- Permite recuperación de datos
- Mantiene integridad referencial

### 6. **API Resources**
- Formateo consistente de respuestas
- Control de datos expuestos
- Transformación de datos

### 7. **Logging**
- Registro de operaciones críticas
- Información para debugging
- Auditoría de acciones

### 8. **Testing**
- Tests de integración
- Coverage de casos críticos
- RefreshDatabase para aislamiento

### 9. **Clean Code**
- Nombres descriptivos
- Métodos cortos y específicos
- Comentarios donde es necesario
- Tipado estricto (PHP 8.2)

### 10. **Sincronización Inteligente de Mascotas**
```php
// En el Service
public function syncMascotas(Cliente $cliente, array $mascotasDTOs): void
{
    // Actualiza existentes, crea nuevas, elimina no enviadas
}
```

---

## 🔒 Seguridad

- ✅ Validación de entrada de datos
- ✅ Protección contra SQL Injection (Eloquent)
- ✅ CORS configurado (si aplica)
- ✅ Rate limiting (puede configurarse)
- ✅ Exception handling seguro

---

## 📝 Notas Adicionales

### DNI Único en Update
La validación del DNI ignora el propio registro del cliente:

```php
Rule::unique('clientes', 'dni')
    ->ignore($clienteId)
    ->whereNull('deleted_at')
```

### Sincronización de Mascotas
El sistema identifica mascotas por el campo `id`:
- **Con ID**: Actualiza
- **Sin ID**: Crea nueva
- **No enviada**: Elimina (soft delete)

### Logs
Los logs se guardan en `storage/logs/laravel.log`:
- Creación/actualización de clientes
- Sincronización de mascotas
- Errores y excepciones

---

## 👨‍💻 Desarrollo

Desarrollado con **Laravel 12** siguiendo:
- Clean Code
- SOLID Principles
- Repository Pattern (Service Layer)
- RESTful API Best Practices

---

## 📄 Licencia

MIT License

---

**¿Tienes preguntas?** Revisa la documentación de Laravel: https://laravel.com/docs

