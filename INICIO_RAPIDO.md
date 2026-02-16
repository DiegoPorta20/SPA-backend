# ⚡ Guía de Inicio Rápido

## 🚀 Instalación en 3 Pasos

### Paso 1: Instalar dependencias
```bash
composer install
```

### Paso 2: Configurar entorno
```bash
cp .env.example .env
php artisan key:generate
```

**Edita el archivo `.env` y configura tu base de datos:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clientes_mascotas_db
DB_USERNAME=root
DB_PASSWORD=tu_password
```

### Paso 3: Ejecutar migraciones
```bash
php artisan migrate --seed
php artisan serve
```

✅ **¡Listo!** Tu API está corriendo en: http://localhost:8000/api

---

## 🧪 Probar la API

### 1. Listar clientes
```bash
curl http://localhost:8000/api/clientes
```

### 2. Obtener cliente específico
```bash
curl http://localhost:8000/api/clientes/1
```

### 3. Crear cliente con mascotas
```bash
curl -X POST http://localhost:8000/api/clientes \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "nombres": "Juan",
    "apellidos": "Pérez",
    "dni": "99999999",
    "telefono": "987654321",
    "email": "juan@example.com",
    "estado": "activo",
    "mascotas": [
      {
        "nombre": "Max",
        "especie": "Perro",
        "raza": "Labrador",
        "edad": 3,
        "peso": 25.5,
        "sexo": "macho",
        "estado": "activo"
      }
    ]
  }'
```

---

## 📋 Endpoints Disponibles

| Método | Endpoint              | Descripción                    |
|--------|-----------------------|--------------------------------|
| GET    | `/api/clientes`       | Listar todos los clientes      |
| GET    | `/api/clientes/{id}`  | Obtener cliente específico     |
| POST   | `/api/clientes`       | Crear cliente                  |
| PUT    | `/api/clientes/{id}`  | Actualizar cliente             |
| DELETE | `/api/clientes/{id}`  | Eliminar cliente               |

---

## 🧪 Ejecutar Tests

```bash
php artisan test
```

**Tests disponibles:**
- ✅ Listar clientes
- ✅ Obtener cliente
- ✅ Crear cliente sin mascotas
- ✅ Crear cliente con mascotas
- ✅ Actualizar y sincronizar mascotas
- ✅ Eliminar cliente
- ✅ Validaciones

---

## 📚 Documentación Completa

- **API_DOCUMENTATION.md** - Documentación detallada de la API
- **EJEMPLOS_USO.md** - Ejemplos prácticos de uso
- **RESUMEN_EJECUTIVO.md** - Resumen del proyecto
- **postman_collection.json** - Colección de Postman

---

## 🔧 Comandos Útiles

### Ver rutas API
```bash
php artisan route:list --path=api
```

### Limpiar y regenerar base de datos
```bash
php artisan migrate:fresh --seed
```

### Limpiar cache
```bash
php artisan cache:clear
php artisan config:clear
```

### Ver logs en tiempo real
```bash
php artisan pail
```

---

## 📊 Estructura del Request

### Crear/Actualizar Cliente

```json
{
  "nombres": "string (requerido, max:100)",
  "apellidos": "string (requerido, max:100)",
  "dni": "string (requerido, único, solo números, max:20)",
  "telefono": "string (opcional, max:20)",
  "email": "email (opcional, max:150)",
  "direccion": "string (opcional, max:500)",
  "estado": "enum (opcional: activo|inactivo)",
  "mascotas": [
    {
      "id": "integer (opcional, solo para actualizar)",
      "nombre": "string (requerido, max:100)",
      "especie": "string (requerido, max:50)",
      "raza": "string (opcional, max:100)",
      "edad": "integer (opcional, min:0, max:100)",
      "peso": "decimal (opcional, min:0, max:9999.99)",
      "sexo": "enum (opcional: macho|hembra)",
      "estado": "enum (opcional: activo|inactivo)"
    }
  ]
}
```

---

## 🎯 Datos de Prueba

El seeder crea automáticamente:
- 5 clientes de ejemplo
- 9 mascotas asociadas
- Diferentes estados y especies

**Accede a ellos con:**
```bash
curl http://localhost:8000/api/clientes
```

---

## ⚠️ Solución de Problemas

### Error: "Connection refused"
- Verifica que MySQL esté corriendo
- Verifica las credenciales en `.env`

### Error: "Database not found"
```bash
# Crea la base de datos manualmente
mysql -u root -p
CREATE DATABASE clientes_mascotas_db;
exit;
```

### Error: "Class not found"
```bash
composer dump-autoload
php artisan clear-compiled
```

---

## 🎉 ¡Listo!

Tu API está funcionando correctamente. Para más información detallada, consulta:

- **API_DOCUMENTATION.md** - Documentación completa
- **EJEMPLOS_USO.md** - Más ejemplos de uso

---

**¿Necesitas ayuda?** Revisa los logs en `storage/logs/laravel.log`

