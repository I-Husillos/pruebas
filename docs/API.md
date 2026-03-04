# Documentación de la API REST

> [!NOTE]
> **Para qué sirve esta API**
> En Arquitectura Hexagonal, la web construida con Vue/Inertia es solo *una forma más* de consumir nuestro "Core" central de negocio.
> Si mañana Termosalud decide hacer una App Nativa en iOS/Android o si un hospital aliado necesita integrarse con nuestro catálogo para su sistema de inventario, usarán esta API REST de consulta pura, que obvia completamente a Vue o Inertia.

## 🔐 Autenticación (API privada)

### Laravel Passport OAuth2

Nuestra API no debe ser consultada por cualquiera para espiar nuestra estructura. Se requiere autenticación usando tokens temporales estándar del mercado: **OAuth2**.

#### Obtener Token de acceso (ejemplo con cURL)

```bash
# Otorgamos contraseña a cambio de Token (Password Grant)
curl -X POST http://localhost/oauth/token \
  -H "Content-Type: application/json" \
  -d '{
    "grant_type": "password",
    "client_id": "CLIENT_ID",
    "client_secret": "CLIENT_SECRET",
    "username": "user@termosalud.com",
    "password": "password",
    "scope": ""
  }'
```

**Respuesta Exitosa:**

```json
{
  "token_type": "Bearer",
  "expires_in": 31536000,
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "refresh_token": "def50200..."
}
```

*Ese `access_token` es ahora la "llave" que autorizará peticiones futuras.*

#### Usar Token en peticiones posteriores

Simplemente incluimos el token en la cabecera (header) `Authorization`:

```bash
curl -X GET http://localhost/api/v1/products \
  -H "Authorization: Bearer TU_ACCESS_TOKEN" \
  -H "Accept: application/json"
```

---

## 📦 API de productos

*Nota: Estilamos nuestras URL indicando `/vX/` (versionado). Si el catálogo cambia de manera que rompa las Apps móviles antiguas, crearíamos `/api/v2/products` manteniendo ambas vivas temporalmente.*

### GET /api/v1/products

Obtiene la lista global paginada de productos.

**Cabeceras (Headers):**

- `Authorization: Bearer {token}`
- `Accept: application/json`

**Parámetros URL Opcionales para Filtrado:**

- `market`: Código del mercado (ej. `es`, `mx`, `us`). Retorna productos normativizados para esa región.
- `language`: Código del idioma (`es`, `en`, `fr`). Formatea los JSON y metadatos hacia ese idioma en concreto.

**Respuesta 200 (OK):**

```json
{
  "success": true,
  "message": "Productos recuperados exitosamente",
  "data": [
    {
      "id": "01jf2x3y4z5a6b7c8d9e0f1g",
      "name": {
        "es": "Zionic Pro Max",
        "en": "Zionic Pro Max"
      },
      "slug": {
        "es": "zionic-pro-max",
        "en": "zionic-pro-max"
      },
      "sku": "ZIONIC-PRO-MAX",
      "categories": ["radiofrequency", "body"],
      "featured_image": "/images/products/zionic-pro-max.jpg",
      "is_featured": true,
      "status": true,
      "created_at": "2025-12-09T10:00:00.000000Z"
    }
  ]
}
```

---

### GET /api/v1/products/{id}

Obtener un único producto al detalle.

**Parámetros URL:**

- `id` (requerido): El ID único universal (ULID) o SKU interno.

**Respuesta 200 (OK):**

```json
{
  "success": true,
  "message": "Producto recuperado exitosamente",
  "data": {
    "id": "01jf2x3y4z5a6b7c8d9e0f1g",
    "name": {
      "es": "Zionic Pro Max",
      "en": "Zionic Pro Max"
    },
    "description": {
      "es": "<p>Descripción completa...</p>",
      "en": "<p>Full description...</p>"
    },
    "sku": "ZIONIC-PRO-MAX",
    "technical_specs": {
      "es": [
        {"label": "Potencia", "value": "300W"},
        {"label": "Frecuencia", "value": "1MHz"}
      ]
    },
    "certifications": ["CE", "FDA"],
    "market_availability": ["es", "mx", "us"]
  }
}
```

---

### POST /api/v1/products

Permite a sistemas externos registrar remotamente un producto en Termosalud corporativo.

**Respuesta 201 (Creado):**

```json
{
  "success": true,
  "message": "Producto creado exitosamente",
  "data": {
    "id": "01jf2x3y4z5a6b7c8d9e0f1h"
  }
}
```

**Respuesta 422 (Error de Validación):**

```json
{
  "success": false,
  "message": "Error de validación",
  "errors": {
    "sku": ["El código (SKU) introducido ya está siendo utilizado por otro producto del catálogo."]
  }
}
```

---

## ⛔ Operaciones mutables posteriores

Estas peticiones funcionan análogamente mandando un payload de actualización en JSON.

### PUT /api/v1/products/{id}

Actualiza (parcial o completamente) la ficha de un producto.
*Retorna 200.*

### DELETE /api/v1/products/{id}

Elimina permanentemente un producto del maestro base.
*Retorna 200. Si el producto ya no existe, devuelve el error 404 Estándar.*

---

## 📊 Formato global de respuesta (el estándar)

Notarás que todos nuestros Controladores envuelven los datos con un patrón que hace que los móviles (Flutter/React Native) lo procesen siempre fácil, pase lo que pase. Nunca devolvemos arrays sueltos.

### Respuesta exitosa

```json
{
  "success": true,
  "message": "Operación completada, mensaje genérico",
  "data": { /* El payload principal va aquí dentro */ }
}
```

### Respuesta fallida (errores controlados u orquestados)

```json
{
  "success": false,
  "message": "El producto no se pudo eliminar porque existen facturas conectadas a él.",
  "data": {
    "error_code": "FOREIGN_KEY_RESTRICTION_102"
  }
}
```

---

## 🔍 Códigos de estado (HTTP Status Codes)

Cuando programes o conectes con el API, siempre verifica la cabecera (Header HTTP), no solo el texto JSON.

- `200 OK`: La petición funcionó perfectamente.
- `201 Created`: El registro se introdujo en la base de datos de forma correcta.
- `401 Unauthorized`: Intentaste consultar sin proveer Token Bearer, o este ha caducado.
- `404 Not Found`: Recurso no encontrado (Ej. Pides la ID de un producto eliminado).
- `422 Unprocessable Entity`: La petición es perfecta a nivel técnico, pero enviaste información no lógica o incorrecta y falló la validación.
- `500 Internal Server Error`: Fallo interno no controlado (Un "pantallazo" del servidor - Típicamente bugs en nuestro código de producción).

---

## 🔐 Limitación de tráfico (Rate Limiting)

Para proteger nuestro catálogo contra "Robots" extractores agresivos (Scraping):

- **Público**: 60 consultas de búsqueda máxima / minuto por IP pública.
- **Tokens Internos**: 100 consultas / minuto.

La API mandará en sus headers `X-RateLimit-Limit` avisándote de cuántos te quedan para llegar al ban temporal.
