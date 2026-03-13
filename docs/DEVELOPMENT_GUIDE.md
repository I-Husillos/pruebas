# Guía de Desarrollo

Guía rápida para implementar nuevas funcionalidades respetando la arquitectura del proyecto.

---

## 🚀 Flujo recomendado para un módulo nuevo

Ejemplo conceptual: módulo `Inquiries`.

### 1. Dominio (`src/.../Domain`)

Define solo reglas de negocio:
- Entidad/Aggregate.
- Value Objects.
- Interface de repositorio (puerto).

Regla: sin dependencias de Laravel (`Illuminate`, Eloquent, Request, etc.).

### 2. Aplicación (`src/.../Application`)

Implementa casos de uso:
- `Create`, `Find`, `Search`, `Update`, `Delete`.
- Orquesta dominio + repositorio (interface).

Regla: no SQL, no HTTP.

### 3. Infraestructura (`src/.../Infrastructure`)

Implementa adaptadores técnicos:
- Repositorio Eloquent que implementa la interface del dominio.
- Integraciones externas (APIs, colas, storage, etc.).

### 4. Entrada HTTP (`app/Http/Controllers/...`)

El controlador:
- Valida request.
- Invoca caso de uso (o bus de comandos/queries).
- Devuelve `sendResponse` / `sendError`.

Regla: no lógica de negocio dentro del controlador.

### 5. Enlace de dependencias (`app/Providers/...`)

Registrar binding interface → implementación.

```php
$this->app->bind(
    \Termosalud\Web\Example\Domain\ExampleRepository::class,
    \Termosalud\Web\Example\Infrastructure\Persistence\EloquentExampleRepository::class
);
```

### 6. Rutas y seguridad

- Registrar endpoint en `routes/api.php` o `routes/frontoffice.php`.
- Aplicar middleware correcto (`auth:api` cuando corresponda).

---

## 📋 Checklist por ticket

- [ ] Dominio sin dependencias de framework.
- [ ] Caso de uso en `Application`.
- [ ] Implementación técnica en `Infrastructure`.
- [ ] Binding de DI registrado.
- [ ] Controlador de acción única (`__invoke`) y respuesta estándar.
- [ ] Ruta registrada con middleware correcto.
- [ ] Tests mínimos (unit/feature) para el cambio.

---

## Referencias

- Arquitectura y patrones: **[ARCHITECTURE.md](./ARCHITECTURE.md)**
- Contratos API: **[API.md](./API.md)**
- Contenido y localizaciones: **[CONTENT_SYSTEM.md](./CONTENT_SYSTEM.md)**
