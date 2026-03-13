# Documentación de la API REST

> [!IMPORTANT]
> La fuente canónica de contratos es Swagger/OpenAPI en `/api/documentation`.
> Este archivo resume autenticación, convenciones y endpoints principales activos.

## Base y autenticación

- Base URL API versionada: `/api/v1`
- Health check público: `GET /api/health-check`
- Auth protegida: middleware `auth:api` (Laravel Passport)
- Endpoint público sin token: `POST /api/v1/forms/{key}/submit`

### Flujo de token (OAuth2 / Passport)

```bash
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

Usar token:

```bash
curl -X GET http://localhost/api/v1/products \
  -H "Authorization: Bearer TU_ACCESS_TOKEN" \
  -H "Accept: application/json"
```

## Convenciones de respuesta

Los controladores API usan `sendResponse(...)` y `sendError(...)` del `ApiController` base.

Ejemplo exitoso:

```json
{
  "success": true,
  "message": "Products retrieved successfully",
  "data": {}
}
```

Ejemplo con error:

```json
{
  "success": false,
  "message": "Product not found",
  "data": []
}
```

## Endpoints activos (resumen)

### Público

- `GET /api/health-check`
- `POST /api/v1/forms/{key}/submit`
- `GET /api/menus/{menu}/items`
- `GET /api/widgets/zone/{zoneKey}`
- `GET /api/forms/{id}`

### Protegidos (`auth:api`)

- `GET|POST|PUT|DELETE /api/v1/products[/{id}]`
- `GET|POST|PUT|DELETE /api/v1/product-categories[/{id}]`
- `GET|POST|PUT|DELETE /api/v1/treatments[/{id}]`
- `GET|POST|PUT|DELETE /api/v1/treatment-categories[/{id}]`
- `GET|POST|PUT|DELETE /api/v1/articles[/{id}]`
- `GET|POST|PUT|DELETE /api/v1/article-categories[/{id}]`
- `GET|POST|PUT|DELETE /api/v1/pages[/{id}]`
- `GET|POST|PUT|DELETE /api/v1/forms[/{id}]`
- `GET|POST|PUT|DELETE /api/v1/markets[/{id}]`
- `GET|POST|PUT|DELETE /api/v1/languages[/{id}]`
- `GET|POST|PUT|DELETE /api/v1/users[/{id}]`
- `POST|PUT|DELETE /api/v1/menus[/{id}]`
- `POST /api/v1/menus/{menu}/items`
- `PUT|DELETE /api/v1/menus/items/{id}`
- `POST /api/v1/menus/items/reorder`
- `POST|PUT|DELETE /api/v1/widgets[/{id}]`
- `POST /api/v1/widgets/reorder`
- `POST /api/v1/media`
- `POST|PUT|DELETE /api/v1/change-controls[/{id}]`
- `POST /api/v1/change-controls/{id}/approve`
- `POST /api/v1/change-controls/{id}/reject`
- `GET /api/user`

## Paginación y filtros

El soporte exacto de filtros/orden/paginación depende de cada endpoint. Por ejemplo, `GET /api/v1/products` acepta parámetros como `search`, `order_by`, `order`, `limit`, `offset`.

Consultar siempre el esquema OpenAPI para cada recurso en `/api/documentation`.

## Contenido localizado y BlockEditor

Los ejemplos de payload no se fijan aquí para evitar desalineación. El contenido multi-idioma/multi-mercado y el JSON de bloques (`content`) están documentados en:

- `docs/CONTENT_SYSTEM.md`

## Códigos HTTP esperables

- `200 OK`
- `201 Created`
- `400 Bad Request`
- `401 Unauthorized`
- `403 Forbidden`
- `404 Not Found`
- `422 Unprocessable Entity`
- `500 Internal Server Error`
