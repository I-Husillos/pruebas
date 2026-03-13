# Sistema de Contenidos - Termosalud Corporate

Este documento describe en profundidad cómo se almacenan, estructuran y gestionan todos los contenidos multi-idioma y multi-mercado del proyecto: el patrón de tablas de localizaciones, las traducciones de categorías, y el formato JSON que genera el **BlockEditor** para construir páginas libremente.

---

## 📐 Arquitectura de almacenamiento de contenidos

### Filosofía central

> [!IMPORTANT]
> **El idioma y el mercado son dimensiones independientes.**
> Un texto en español puede ser completamente diferente para el mercado `es` (España, normativa EU_MDR) que para el mercado `us` (EE.UU., normativa FDA), aunque ambos sean "en español". Por tanto, **una fila en base de datos = una versión de contenido para una combinación `(idioma, mercado)` concreta**.

Esto descarta el patrón clásico de "un campo JSON con clave de idioma" (`{"es": "...", "en": "..."}`), que era válido para webs simples pero **no permite variaciones de contenido regulatorio por mercado**.

---

## 🗄️ Patrón de Tablas de Localización (Canónico)

Todas las entidades de contenido principal siguen este esquema de dos tablas:

### Tabla maestra (`pages`, `products`, `articles`, `treatments`)

Contiene únicamente los datos **no traducibles** o **compartidos globalmente** entre todos los mercados e idiomas:

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | bigint | PK autoincremental |
| `status` | enum | `draft`, `published`, `scheduled`, `pending_review` |
| `images` | json | Array de URLs de imágenes (compartido) |
| `order` | int | Orden de aparición en listados |
| `[entity]_category_id` | FK | Referencia a la categoría global |
| `related_products` / `related_treatments` | json | IDs de entidades relacionadas |
| `deleted_at` | timestamp | Soft delete |
| `created_at`, `updated_at` | timestamps | — |

### Tabla de localizaciones (`*_localizations`)

Contiene **una fila por combinación `(entidad, mercado, idioma)`**. Es aquí donde vive todo el contenido editable:

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | bigint | PK autoincremental |
| `slug` | string (unique) | URL amigable única en todo el sistema |
| `[entity]_id` | FK | Referencia a la entidad maestra |
| `language_id` | FK → `languages` | El idioma de esta versión |
| `market_id` | FK → `markets` | El mercado de esta versión |
| `title` | string | Título legible |
| `excerpt` | string | Resumen corto / texto de tarjeta |
| `description` | text | Descripción larga / texto libre |
| `content` | **json** | **Bloques de contenido (BlockEditor)** |
| `seo_metadata` | json | Metadatos SEO (ver estructura más abajo) |
| `created_at`, `updated_at` | timestamps | — |

**Restricción de unicidad clave:**
```sql
UNIQUE (article_id, language_id, market_id)
```
No puede existir más de una versión del mismo artículo en el mismo idioma y mercado.

**Índice de búsqueda optimizado:**
```sql
INDEX (slug, language_id, market_id)
```
Permite resolver una URL del tipo `/{market}/{lang}/{slug}` directamente.

### Entidades que implementan este patrón

| Entidad | Tabla maestra | Tabla de localizaciones |
|---|---|---|
| Páginas libres (custom landings) | `pages` | `page_localizations` |
| Productos | `products` | `product_localizations` |
| Artículos / Blog / Noticias | `articles` | `article_localizations` |
| Tratamientos | `treatments` | `treatment_localizations` |

> **Nota sobre `treatment_localizations`**: esta tabla tiene dos campos adicionales específicos del dominio médico: `indications` (json) y `contraindications` (json), también en formato BlockEditor.

---

## 🏷️ Patrón de Traducciones de Categorías

Las categorías **no varían por mercado** (un "Láser" es un "Láser" en todos los mercados), por lo que solo necesitan traducción por idioma:

| Tabla | Clave única | Campos traducibles |
|---|---|---|
| `product_category_translations` | `(product_category_id, language_id)` | `name`, `description`, `slug`, `seo_metadata` |
| `article_category_translations` | `(article_category_id, language_id)` | `title`, `description`, `slug`, `seo_metadata` |
| `treatment_category_translations` | `(treatment_category_id, language_id)` | `title`, `description`, `slug`, `seo_metadata` |

---

## 📦 Estructura del campo `content` — BlockEditor JSON

El campo `content` (y `indications`/`contraindications` en tratamientos) es un array JSON generado por el componente `BlockEditor.vue`. Es un documento de contenido visual estructurado en **filas** → **columnas** → **bloques**.

### Nivel 1: Array de Filas

```json
[
  { "type": "row", "layout": "1-1", "columns": [ ... ] },
  { "type": "row", "layout": "1",   "columns": [ ... ] }
]
```

Cada fila tiene:

| Campo | Tipo | Valores posibles |
|---|---|---|
| `type` | string | Siempre `"row"` |
| `layout` | string | `"1"`, `"1-1"`, `"1-2"`, `"2-1"`, `"1-1-1"` |
| `columns` | array | 1, 2 ó 3 objetos columna según `layout` |

**Correspondencia de layouts con CSS Grid:**

| Valor | Columnas | CSS |
|---|---|---|
| `"1"` | 1 columna al 100% | `grid-cols-1` |
| `"1-1"` | 2 columnas 50/50 | `md:grid-cols-2` |
| `"1-2"` | 2 columnas 33/66 | `md:grid-cols-[1fr_2fr]` |
| `"2-1"` | 2 columnas 66/33 | `md:grid-cols-[2fr_1fr]` |
| `"1-1-1"` | 3 columnas iguales | `md:grid-cols-3` |

### Nivel 2: Columnas

```json
{
  "blocks": [ ... ]
}
```

Cada columna contiene solo un array `blocks` con los bloques de contenido apilados verticalmente en esa columna.

### Nivel 3: Bloques de contenido

Cada bloque tiene siempre la misma forma: `{ "type": "...", "data": { ... } }`. Los tipos disponibles son:

#### `rich_text` / `wysiwyg` — Texto enriquecido

```json
{
  "type": "rich_text",
  "data": {
    "content": "<p>HTML generado por el editor WYSIWYG (Tiptap)</p>"
  }
}
```

El campo `content` contiene HTML limpio producido por el editor `Wysiwyg.vue`. Los dos `type` (`rich_text` y `wysiwyg`) son equivalentes y renderizan con el mismo componente.

#### `multimedia` — Imagen o Vídeo

```json
{
  "type": "multimedia",
  "data": {
    "url": "https://termosalud.com/storage/media/imagen-producto.jpg",
    "type": "image",
    "caption": "Texto alternativo / pie de foto"
  }
}
```

| Campo | Tipo | Descripción |
|---|---|---|
| `url` | string | URL absoluta al archivo en storage |
| `type` | string | `"image"` o `"video"` |
| `caption` | string | Texto alt para imágenes / descripción para vídeos |

El archivo es subido mediante `POST /api/v1/admin/media` (ruta `admin.media.store`) y la URL devuelta se almacena aquí. Soporta imágenes JPG/PNG/GIF y vídeos MP4/WEBM hasta 50MB.

#### `form` — Formulario incrustado

```json
{
  "type": "form",
  "data": {
    "form_id": 3
  }
}
```

Referencia por ID a un registro de la tabla `forms`. El frontend renderizará el formulario dinámico en esa posición del layout.

#### `html` — HTML libre

```json
{
  "type": "html",
  "data": {
    "code": "<script src=\"https://embed.ejemplo.com/widget.js\"></script>"
  }
}
```

Para incrustar widgets externos, contadores, iframes, etc. Solo accesible por usuarios con permisos de administrador técnico.

---

### Ejemplo completo de campo `content`

```json
[
  {
    "type": "row",
    "layout": "1-2",
    "columns": [
      {
        "blocks": [
          {
            "type": "multimedia",
            "data": {
              "url": "https://termosalud.com/storage/media/zionic-hero.jpg",
              "type": "image",
              "caption": "Equipo Zionic Pro en clínica"
            }
          }
        ]
      },
      {
        "blocks": [
          {
            "type": "rich_text",
            "data": {
              "content": "<h2>Zionic Pro</h2><p>La tecnología de última generación en crioterapia estética, certificada bajo normativa EU MDR.</p>"
            }
          },
          {
            "type": "form",
            "data": {
              "form_id": 1
            }
          }
        ]
      }
    ]
  },
  {
    "type": "row",
    "layout": "1",
    "columns": [
      {
        "blocks": [
          {
            "type": "html",
            "data": {
              "code": "<!-- Google Tag Manager dataLayer push -->"
            }
          }
        ]
      }
    ]
  }
]
```

---

## 🌐 Estructura del campo `seo_metadata`

```json
{
  "title": "Zionic Pro - Crioterapia Estética Certificada EU MDR | Termosalud",
  "description": "Descubre el Zionic Pro, el equipo de crioterapia más avanzado del mercado, certificado bajo normativa MDR europea.",
  "keywords": "crioterapia, estética, MDR, Zionic",
  "og_title": "Zionic Pro",
  "og_description": "...",
  "og_image": "https://termosalud.com/storage/seo/zionic-og.jpg",
  "canonical": "https://termosalud.com/es/es/productos/zionic-pro",
  "robots": "index, follow"
}
```

---

## 🔌 Formato de la API para crear/editar localizaciones

### Páginas — `POST /api/v1/pages` (patrón canónico)

```json
{
  "market_code": "es",
  "language_code": "es",
  "slug": "promo-verano-2026",
  "is_active": true,
  "seo_title": "Oferta Verano 2026 | Termosalud",
  "seo_description": "Aprovecha nuestra oferta exclusiva de verano...",
  "meta_keywords": "oferta, verano, termosalud",
  "blocks_json": [
    {
      "type": "row",
      "layout": "1",
      "columns": [
        {
          "blocks": [
            { "type": "rich_text", "data": { "content": "<p>Contenido...</p>" } }
          ]
        }
      ]
    }
  ]
}
```

Una **versión independiente** de la misma página para cada combinación de mercado e idioma se envía con un payload idéntico cambiando `market_code` y/o `language_code`.

### Artículos — `POST /api/v1/articles` (en transición)

Actualmente usa JSON-columns (pattern antiguo, pendiente de migrar):

```json
{
  "type": "blog",
  "title": { "es": "Mi artículo", "en": "My article" },
  "slug":  { "es": "mi-articulo", "en": "my-article" },
  "excerpt": { "es": "Resumen", "en": "Summary" },
  "content": { "es": "<p>Contenido ES</p>", "en": "<p>Content EN</p>" },
  "published": true,
  "category_id": 2
}
```

> [!WARNING]
> **Deuda técnica:** Los modelos `Product` y `ContentArticle` (junto con sus controladores de API) aún usan el patrón antiguo de columnas JSON (`name.es`, `slug.en`, etc.) en lugar de las tablas de localización. Aunque la base de datos ya tiene las tablas `product_localizations` y `article_localizations` creadas, el código de aplicación y los formularios del backoffice aún no las utilizan. Esta migración está pendiente.

---

## 🔄 Resolución de contenido en frontend

Cuando el router recibe `/es/es/productos/zionic-pro`:

1. `market = "es"`, `lang = "es"`, `slug = "zionic-pro"`
2. Se busca en `product_localizations` donde:
   - `slug = "zionic-pro"`
   - `language.code = "es"`
   - `market.code = "es"`
3. Se devuelve el campo `content` (BlockEditor JSON) + `seo_metadata` a Vue
4. El componente `BlockRenderer.vue` iterará las filas y renderizará cada bloque

Si no existe localización para la combinación exacta `(market, lang)`, el sistema puede hacer fallback al idioma base del mercado, o al contenido por defecto. Esta lógica de fallback es configurable por mercado a través del campo `fallback_language` en la tabla `languages`.

---

## 📊 Estado de implementación por entidad

| Entidad | DB (migraciones) | Dominio (`src/`) | API | backoffice (Vue) |
|---|---|---|---|---|
| **Pages** | ✅ Localization tables | ✅ Completo | ✅ Completo | ✅ `PageCreateController` + `BlockEditor` |
| **Products** | ✅ Localization tables | ⚠️ Parcial (usa JSON columns) | ⚠️ Usa JSON columns | ⚠️ Tabs ES/EN |
| **Articles** | ✅ Localization tables | ⚠️ Parcial (modelo `ContentArticle`) | ⚠️ Usa JSON columns | ⚠️ Tabs ES/EN |
| **Treatments** | ✅ Localization tables | ⚠️ Parcial (usa JSON columns) | — | — |

La implementación completa de Pages es el **patrón de referencia** que debe seguirse al migrar los demás módulos.

---

## 🗂️ Archivos relevantes

| Archivo | Propósito |
|---|---|
| `resources/js/Components/Admin/BlockEditor.vue` | Componente principal del constructor de páginas |
| `resources/js/Components/Admin/Blocks/WysiwygBlock.vue` | Bloque de texto enriquecido |
| `resources/js/Components/Admin/Blocks/MultimediaBlock.vue` | Bloque de imagen/vídeo + upload |
| `resources/js/Components/Admin/Blocks/FormBlock.vue` | Bloque de formulario embebido |
| `resources/js/Pages/Admin/Pages/Create.vue` | Formulario de creación de página (patrón canónico) |
| `app/Http/Requests/Admin/Page/StorePageRequest.php` | Validación del payload de páginas |
| `src/Web/Page/Application/Create/CreatePageCommand.php` | Command CQRS para creación |
| `src/Web/Page/Application/Create/PageCreator.php` | Handler del caso de uso |
| `database/migrations/2025_12_09_101320_create_pages_table.php` | Migraciones: `pages` + `page_localizations` |
| `database/migrations/2025_12_09_101320_create_products_table.php` | Migraciones: `products` + `product_localizations` |
| `database/migrations/2025_12_09_101321_create_articles_table.php` | Migraciones: `articles` + `article_localizations` |
| `database/migrations/2025_12_09_101321_create_treatments_table.php` | Migraciones: `treatments` + `treatment_localizations` |
