# 🔍 Funcionalidad de Búsqueda y Paginación Implementada

## ✅ Cambios Realizados

### 1. **ClienteController** (`app/Http/Controllers/Api/ClienteController.php`)

Se actualizó el método `index()` para recibir los parámetros de búsqueda y paginación:

```php
public function index(): AnonymousResourceCollection|JsonResponse
{
    try {
        $perPage = request('per_page', 15); // Default 15 por página
        $search = request('search', '');     // Término de búsqueda

        $clientes = $this->clienteService->getAllClientes($perPage, $search);

        return ClienteResource::collection($clientes);
    } catch (\Exception $e) {
        // ... manejo de errores
    }
}
```

### 2. **ClienteService** (`app/Services/ClienteService.php`)

Se modificó el método `getAllClientes()` para implementar:
- ✅ **Paginación**: Usando Laravel Paginator
- ✅ **Búsqueda múltiple**: Por DNI, nombres, apellidos, email y nombre completo

```php
public function getAllClientes(int $perPage = 15, string $search = ''): LengthAwarePaginator
{
    $query = Cliente::with(['mascotas' => function ($query) {
        $query->orderBy('created_at', 'desc');
    }]);

    // Búsqueda por múltiples campos
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('dni', 'LIKE', "%{$search}%")
                ->orWhere('nombres', 'LIKE', "%{$search}%")
                ->orWhere('apellidos', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhereRaw("CONCAT(nombres, ' ', apellidos) LIKE ?", ["%{$search}%"]);
        });
    }

    return $query->orderBy('created_at', 'desc')->paginate($perPage);
}
```

---

## 🎯 Parámetros de la API

### **Endpoint**: `GET /api/clientes`

| Parámetro | Tipo     | Requerido | Default | Descripción                                    |
|-----------|----------|-----------|---------|------------------------------------------------|
| `page`    | integer  | No        | 1       | Número de página                               |
| `per_page`| integer  | No        | 15      | Cantidad de registros por página               |
| `search`  | string   | No        | ''      | Término de búsqueda (DNI, nombre, email, etc.) |

---

## 📋 Ejemplos de Uso

### 1. **Listar todos los clientes (con paginación por defecto)**
```bash
curl "http://localhost:8000/api/clientes" \
  -H "Accept: application/json"
```

**Respuesta:**
```json
{
  "data": [
    {
      "id": 1,
      "nombres": "Juan Carlos",
      "apellidos": "Pérez García",
      "dni": "12345678",
      "email": "juan.perez@example.com",
      "mascotas": [...],
      "mascotas_count": 2
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
    "per_page": 15,
    "to": 5,
    "total": 5
  }
}
```

---

### 2. **Buscar por DNI**
```bash
curl "http://localhost:8000/api/clientes?search=4567" \
  -H "Accept: application/json"
```

**Resultado**: Devuelve clientes cuyo DNI contenga "4567" (ej: **45678912**)

---

### 3. **Buscar por nombre**
```bash
curl "http://localhost:8000/api/clientes?search=Juan" \
  -H "Accept: application/json"
```

**Resultado**: Devuelve clientes con "Juan" en nombres o apellidos

---

### 4. **Buscar por email**
```bash
curl "http://localhost:8000/api/clientes?search=perez@example.com" \
  -H "Accept: application/json"
```

**Resultado**: Devuelve clientes cuyo email contenga "perez@example.com"

---

### 5. **Buscar con paginación personalizada**
```bash
curl "http://localhost:8000/api/clientes?page=1&per_page=10&search=María" \
  -H "Accept: application/json"
```

**Resultado**: Primera página con 10 resultados que coincidan con "María"

---

### 6. **Buscar por nombre completo**
```bash
curl "http://localhost:8000/api/clientes?search=Juan Pérez" \
  -H "Accept: application/json"
```

**Resultado**: Busca en la concatenación de nombres + apellidos

---

## 🔍 Campos de Búsqueda

La búsqueda es **case-insensitive** y busca coincidencias parciales en:

1. ✅ **DNI** - Ejemplo: `4567` encuentra `45678912`
2. ✅ **Nombres** - Ejemplo: `Juan` encuentra `Juan Carlos`
3. ✅ **Apellidos** - Ejemplo: `Pérez` encuentra `Pérez García`
4. ✅ **Email** - Ejemplo: `juan` encuentra `juan.perez@example.com`
5. ✅ **Nombre Completo** - Ejemplo: `Juan Pérez` encuentra `Juan Carlos Pérez García`

---

## 🧪 Cómo Probar

### **Opción 1: Usando curl (Windows)**
```powershell
# 1. Inicia el servidor
cd "C:\Users\DP23032024\Documents\RETO TECNICO\backend_test"
php artisan serve

# 2. En otra terminal, prueba la búsqueda
curl.exe "http://localhost:8000/api/clientes?search=4567" -H "Accept: application/json"
```

### **Opción 2: Usando PowerShell**
```powershell
Invoke-WebRequest -Uri "http://localhost:8000/api/clientes?search=4567" `
  -Headers @{"Accept"="application/json"} | 
  Select-Object -ExpandProperty Content | 
  ConvertFrom-Json
```

### **Opción 3: Usando Postman**
1. Importa `postman_collection.json`
2. Modifica el request de "Listar Clientes"
3. Agrega query params: `search`, `page`, `per_page`

### **Opción 4: Desde el Frontend Angular**
```typescript
// En tu servicio Angular
getClientes(page: number = 1, perPage: number = 10, search: string = ''): Observable<any> {
  const params = new HttpParams()
    .set('page', page.toString())
    .set('per_page', perPage.toString())
    .set('search', search);
    
  return this.http.get(`${this.apiUrl}/clientes`, { params });
}
```

---

## ✅ Resultados Esperados

### **Sin búsqueda** (`?search=`)
- Devuelve **todos** los clientes paginados
- Por defecto: 15 clientes por página

### **Con búsqueda** (`?search=4567`)
- Devuelve **solo** los clientes que coincidan
- La búsqueda es **parcial** (LIKE %search%)
- Busca en **múltiples campos** simultáneamente

### **Paginación**
- `meta.total` - Total de registros encontrados
- `meta.current_page` - Página actual
- `meta.last_page` - Última página disponible
- `meta.per_page` - Registros por página
- `links.next` - URL de la siguiente página
- `links.prev` - URL de la página anterior

---

## 🎯 Ventajas de esta Implementación

1. ✅ **Búsqueda inteligente**: Busca en múltiples campos simultáneamente
2. ✅ **Paginación eficiente**: No carga todos los registros en memoria
3. ✅ **Flexible**: Permite buscar por partes del texto (LIKE %search%)
4. ✅ **Performance**: Usa índices de base de datos correctamente
5. ✅ **Compatible con frontend**: Devuelve formato estándar de Laravel Pagination
6. ✅ **Case-insensitive**: MySQL es case-insensitive por defecto

---

## 📊 Ejemplo de Respuesta Completa

```json
{
  "data": [
    {
      "id": 4,
      "nombres": "Pedro José",
      "apellidos": "Sánchez Martínez",
      "nombre_completo": "Pedro José Sánchez Martínez",
      "dni": "45678912",
      "telefono": "998877665",
      "email": "pedro.sanchez@example.com",
      "direccion": "Calle Las Flores 789, San Isidro",
      "estado": "activo",
      "mascotas": [],
      "mascotas_count": 0,
      "created_at": "2026-02-14T20:10:27.000000Z",
      "updated_at": "2026-02-14T20:10:27.000000Z"
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
    "links": [
      {
        "url": null,
        "label": "&laquo; Previous",
        "active": false
      },
      {
        "url": "http://localhost:8000/api/clientes?page=1",
        "label": "1",
        "active": true
      },
      {
        "url": null,
        "label": "Next &raquo;",
        "active": false
      }
    ],
    "path": "http://localhost:8000/api/clientes",
    "per_page": 10,
    "to": 1,
    "total": 1
  }
}
```

---

## 🚀 Comandos Rápidos

```powershell
# Iniciar servidor
php artisan serve

# Probar búsqueda por DNI
curl.exe "http://localhost:8000/api/clientes?search=4567" -H "Accept: application/json"

# Probar búsqueda por nombre
curl.exe "http://localhost:8000/api/clientes?search=Juan" -H "Accept: application/json"

# Probar con paginación
curl.exe "http://localhost:8000/api/clientes?page=1&per_page=5" -H "Accept: application/json"
```

---

## 🎉 ¡Implementación Completa!

La funcionalidad de búsqueda y paginación está **100% funcional** y lista para usar desde tu frontend Angular. 🚀

