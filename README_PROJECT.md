# 🚀 Sistema de Gestión de Clientes y Mascotas - Backend API

> **API RESTful profesional desarrollada con Laravel 12** para el mantenimiento de clientes y sus mascotas asociadas.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## 📋 Índice

- [Características](#-características)
- [Instalación Rápida](#-instalación-rápida)
- [Arquitectura](#-arquitectura)
- [Endpoints API](#-endpoints-api)
- [Documentación](#-documentación)
- [Testing](#-testing)

---

## ✨ Características

### 🎯 Funcionalidades Core

- ✅ **CRUD completo** de clientes y mascotas
- ✅ **Sincronización inteligente** de mascotas (crear, actualizar, eliminar en una sola transacción)
- ✅ **Validaciones robustas** con FormRequests
- ✅ **Transacciones DB** para integridad de datos
- ✅ **Soft Deletes** implementado
- ✅ **API Resources** para respuestas consistentes
- ✅ **Códigos HTTP correctos** (200, 201, 404, 422, 500)

### 🏗 Arquitectura Profesional

- ✅ **Service Layer** - Separación de lógica de negocio
- ✅ **DTOs** - Transferencia de datos tipada
- ✅ **Repository Pattern** - Acceso a datos
- ✅ **FormRequests** - Validaciones centralizadas
- ✅ **Exception Handler** personalizado
- ✅ **Clean Code** y **SOLID Principles**

### 🔒 Seguridad y Calidad

- ✅ Validación de datos de entrada
- ✅ Protección contra SQL Injection (Eloquent ORM)
- ✅ Manejo de errores robusto
- ✅ Logs estructurados
- ✅ Tests automatizados

---

## 🚀 Instalación Rápida

### Requisitos Previos

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Git

### Opción 1: Script Automático (PowerShell)

```powershell
.\setup.ps1
```

### Opción 2: Instalación Manual

```bash
# 1. Instalar dependencias
composer install

# 2. Configurar entorno
cp .env.example .env
php artisan key:generate

# 3. Configurar base de datos en .env
# DB_DATABASE=clientes_mascotas_db
# DB_USERNAME=root
# DB_PASSWORD=

# 4. Ejecutar migraciones y seeders
php artisan migrate --seed

# 5. Iniciar servidor
php artisan serve
```

La API estará disponible en: **http://localhost:8000/api**

---

## 🏗 Arquitectura

```
┌─────────────────────────────────────────────────────┐
│                   HTTP Request                       │
└────────────────────┬────────────────────────────────┘
                     ▼
         ┌───────────────────────┐
         │   ClienteController   │ ◄── FormRequests (Validación)
         └──────────┬────────────┘
                    ▼
         ┌───────────────────────┐
         │   ClienteService      │ ◄── DTOs (Transferencia)
         │   MascotaService      │
         └──────────┬────────────┘
                    ▼
         ┌───────────────────────┐
         │   DB::transaction()   │ ◄── Transacciones
         └──────────┬────────────┘
                    ▼
         ┌───────────────────────┐
         │   Cliente (Model)     │ ◄── Eloquent ORM
         │   Mascota (Model)     │
         └──────────┬────────────┘
                    ▼
         ┌───────────────────────┐
         │   MySQL Database      │
         └───────────────────────┘
```

### Estructura de Carpetas

```
app/
├── DTOs/                    # Data Transfer Objects
│   ├── ClienteDTO.php
│   └── MascotaDTO.php
├── Services/                # Lógica de negocio
│   ├── ClienteService.php
│   └── MascotaService.php
├── Http/
│   ├── Controllers/Api/     # Controladores REST
│   ├── Requests/            # Validaciones FormRequest
│   └── Resources/           # Formateo de respuestas
└── Models/                  # Eloquent Models
    ├── Cliente.php
    └── Mascota.php
```

---

## 🔌 Endpoints API

**Base URL:** `http://localhost:8000/api`

| Método | Endpoint              | Descripción                              |
|--------|-----------------------|------------------------------------------|
| GET    | `/clientes`           | Listar todos los clientes con mascotas   |
| GET    | `/clientes/{id}`      | Obtener cliente específico               |
| POST   | `/clientes`           | Crear cliente (con/sin mascotas)         |
| PUT    | `/clientes/{id}`      | Actualizar cliente y sincronizar mascotas|
| DELETE | `/clientes/{id}`      | Eliminar cliente (soft delete)           |

### Ejemplo: Crear Cliente con Mascotas

```bash
POST /api/clientes
Content-Type: application/json

{
  "nombres": "Laura Patricia",
  "apellidos": "Morales Castillo",
  "dni": "55443322",
  "telefono": "912345678",
  "email": "laura@example.com",
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
    }
  ]
}
```

**Ver ejemplos completos en:** [`EJEMPLOS_USO.md`](EJEMPLOS_USO.md)

---

## 📚 Documentación

| Documento                | Descripción                                  |
|--------------------------|----------------------------------------------|
| [`API_DOCUMENTATION.md`](API_DOCUMENTATION.md) | Documentación completa de la API   |
| [`EJEMPLOS_USO.md`](EJEMPLOS_USO.md)           | Ejemplos prácticos de uso          |
| [`postman_collection.json`](postman_collection.json) | Colección de Postman        |

### Importar en Postman

1. Abrir Postman
2. Click en "Import"
3. Seleccionar `postman_collection.json`
4. ¡Listo! Tendrás todos los endpoints configurados

---

## 🧪 Testing

### Ejecutar todos los tests

```bash
php artisan test
```

### Ejecutar tests específicos

```bash
php artisan test --filter ClienteApiTest
```

### Tests Incluidos

- ✅ Listar clientes
- ✅ Obtener cliente individual
- ✅ Crear cliente sin mascotas
- ✅ Crear cliente con mascotas
- ✅ Actualizar y sincronizar mascotas
- ✅ Eliminar cliente
- ✅ Validación de DNI duplicado
- ✅ Validación de campos requeridos
- ✅ Manejo de errores 404

---

## 🗄️ Base de Datos

### Modelo de Datos

```
┌─────────────────────┐         ┌─────────────────────┐
│     CLIENTES        │         │      MASCOTAS       │
├─────────────────────┤         ├─────────────────────┤
│ id (PK)             │◄───┐    │ id (PK)             │
│ nombres             │    │    │ cliente_id (FK)     │
│ apellidos           │    └────┤ nombre              │
│ dni (unique)        │         │ especie             │
│ telefono            │         │ raza                │
│ email               │         │ edad                │
│ direccion           │         │ peso                │
│ estado              │         │ sexo                │
│ timestamps          │         │ estado              │
│ deleted_at          │         │ timestamps          │
└─────────────────────┘         │ deleted_at          │
                                └─────────────────────┘
```

**Relación:** 1:N (Un cliente tiene muchas mascotas)

---

## 🔥 Características Destacadas

### 1. Sincronización Inteligente de Mascotas

Al actualizar un cliente, el sistema automáticamente:
- **Actualiza** mascotas existentes (con `id`)
- **Crea** nuevas mascotas (sin `id`)
- **Elimina** mascotas no enviadas (soft delete)

### 2. Validación de DNI Único

El sistema valida que el DNI sea único, excepto para el propio cliente en updates.

### 3. Soft Deletes

No se eliminan registros físicamente, permitiendo recuperación de datos.

### 4. Transacciones Atómicas

Todas las operaciones críticas se ejecutan en transacciones para garantizar integridad.

---

## 🛠 Tecnologías Utilizadas

- **Framework:** Laravel 12.x
- **PHP:** 8.2+
- **Base de Datos:** MySQL 8.0+
- **ORM:** Eloquent
- **Testing:** PHPUnit
- **Validación:** FormRequests
- **Arquitectura:** Service Layer + Repository Pattern

---

## 📈 Mejores Prácticas Implementadas

- ✅ **SOLID Principles**
- ✅ **Clean Code**
- ✅ **RESTful API Design**
- ✅ **Transaction Management**
- ✅ **Error Handling**
- ✅ **Input Validation**
- ✅ **Logging**
- ✅ **Testing**
- ✅ **Documentation**
- ✅ **Type Safety** (PHP 8.2)

---

## 📝 Comandos Útiles

```bash
# Listar rutas API
php artisan route:list --path=api

# Ejecutar tests con coverage
php artisan test --coverage

# Limpiar cache
php artisan cache:clear
php artisan config:clear

# Regenerar base de datos
php artisan migrate:fresh --seed
```

---

## 📄 Licencia

MIT License

---

**Desarrollado con ❤️ usando Laravel 12**

