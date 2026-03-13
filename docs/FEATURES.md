# Características Clave del Sistema

Resumen corto de las decisiones funcionales más importantes de Termosalud.

---

## 🚀 1. API-First

- La API versionada (`/api/v1`) es la base para web, integraciones y clientes futuros.
- La autenticación en endpoints privados usa Laravel Passport (`auth:api`).
- Contratos y esquemas: `/api/documentation`.

---

## 📝 2. Contenido Editorial Unificado

- Blog, noticias y prensa comparten `articles` con campo `type` (`blog`, `news`, `press`).
- Se evita duplicación de CRUD, validaciones y flujos de publicación.
- Categorías reutilizadas vía `article_categories`.

---

## 🌐 3. Multi-Market + Multi-Language

- Mercado y idioma son dimensiones distintas.
- Una variante de contenido se define por `(entidad, market_id, language_id)` en `*_localizations`.
- El idioma traduce; el mercado define reglas regulatorias y disponibilidad.

Ejemplos:
- `/es/es/productos/zionic` → mercado `es`, idioma `es`
- `/us/en/products/zionic` → mercado `us`, idioma `en`
- `/us/es/products/zionic` → mercado `us`, idioma `es`

Ver detalle de modelo y `content` JSON: **[CONTENT_SYSTEM.md](./CONTENT_SYSTEM.md)**.

---

## 🏢 4. Contenido Corporativo Compartido

- El contenido institucional puede compartirse entre mercados.
- Se permiten overrides locales cuando un mercado requiere datos específicos (contacto, sede, etc.).
- Objetivo: edición centralizada con excepciones controladas.

---

## 🎨 5. Landings Flexibles

- Marketing puede publicar landings con URL propia por mercado/idioma.
- El contenido se compone con bloques (BlockEditor).
- Se mantiene control técnico de colisiones con rutas reservadas.

---

## 🔀 6. Prioridad de Routing

Orden general:
1. Rutas fijas del dominio (home, productos, tratamientos, etc.).
2. Páginas dinámicas previstas por el sistema.
3. Fallback dinámico.
4. `404`.

El objetivo es evitar colisiones entre contenido editable y rutas del núcleo.
