# AestheticMed Global Corporate Site (Termosalud)

**Versión:** 4.0.0  
**Sitio Web:** www.termosalud.com

## 📋 Descripción del Proyecto

Plataforma corporativa global multi-mercado y multi-idioma para medicina estética, con **API REST desde el inicio**, gestión avanzada de contenidos regulados (MDR, FDA), catálogo de equipos médicos, motor unificado de contenidos (blog/noticias/prensa), y **arquitectura hexagonal desacoplada del framework**.

### Objetivos Principales

#### Negocio
- Posicionar la marca como líder global en medicina estética con tecnología de equipos avanzados
- Generar leads de alta calidad para clínicas, distribuidores e inversores
- Soportar ventas, formación y cumplimiento regulatorio con documentación robusta
- Flexibilidad total para escalar mercados e idiomas según estrategia comercial

#### Técnicos
- **Arquitectura hexagonal**: Core business logic en `src/` 100% desacoplado de Laravel
- **API REST desde día 1**: Soportar web, apps móviles (Ionic/React Native) y futuras integraciones
- **Laravel 11 como infraestructura**: Solo HTTP, Eloquent adapters, Queue, Cache
- **Frontend moderno**: Vue 3 + TypeScript + Inertia + TailwindCSS
- **Motor unificado de contenidos**: ContentArticle para blog, noticias y prensa (mismo modelo, diferentes categorías)
- **Routing explícito**: Sin complejidad accidental ni consultas múltiples a BD por request
- **Gestión dinámica**: Mercados e idiomas configurables desde backoffice/API
- **Core Web Vitals optimizados**: LCP < 1.8s, CLS < 0.1, INP < 200ms

#### SEO
- Máxima visibilidad orgánica por país e idioma (multi-regional + multi-idioma)
- URLs 100% amigables y 100% localizables (estilo Siemens)
- Implementación completa de hreflang por combinación idioma-región

---

## 🌍 Estrategia Internacional

### ⚙️ Gestión Dinámica de Mercados e Idiomas

**🔧 100% Configurable desde Backoffice** - Tanto mercados como idiomas son entidades gestionables que pueden añadirse, editarse o desactivarse sin modificar código.

### Mercados Iniciales (Ejemplo)

| Código | País | Región Regulatoria | Idioma Principal | Idiomas Adicionales | Estado |
|--------|------|-------------------|------------------|---------------------|--------|
| `es` | España | EU_MDR | Español | Inglés | Activo |
| `mx` | México | LATAM_GENERIC | Español | - | Activo |
| `us` | Estados Unidos | FDA | Inglés | Español | Activo |
| `fr` | Francia | EU_MDR | Francés | Inglés | Planificado |
| `de` | Alemania | EU_MDR | Alemán | Inglés | Planificado |

**Campos Configurables por Mercado:**
- Código de mercado (2 letras, único)
- Nombre del país/región
- Región regulatoria (EU_MDR, FDA, LATAM_GENERIC, etc.)
- Idioma por defecto
- Idiomas habilitados (multi-selección)
- Región hreflang (para SEO)
- Estado (activo/inactivo)
- Orden de prioridad (para selector de país)

### Idiomas Iniciales (Ejemplo)

| Código | Nombre | Nombre Nativo | Estado | Dirección |
|--------|--------|---------------|--------|----------|
| `es` | Español | Español | Activo | LTR |
| `en` | Inglés | English | Activo | LTR |
| `fr` | Francés | Français | Planificado | LTR |
| `de` | Alemán | Deutsch | Planificado | LTR |
| `it` | Italiano | Italiano | Planificado | LTR |
| `pt` | Portugués | Português | Planificado | LTR |
| `lt` | Lituano | Lietuvių | Planificado | LTR |

**Campos Configurables por Idioma:**
- Código ISO 639-1 (2 letras)
- Nombre del idioma (en español)
- Nombre nativo
- Dirección del texto (LTR/RTL para futuros idiomas árabes, hebreo, etc.)
- Estado (activo/inactivo)
- Fallback language (idioma de respaldo si falta traducción)

---

## 🔗 Estrategia de URLs (Estilo Siemens)

### Patrón General
```
/{marketCode}/{language}/{sectionSlugLocalized}/{contentSlugLocalized}
```

### Ejemplos Reales (Equipos Médicos)

```
# España - Español - Equipo Zionic Pro Max
/es/es/productos/zionic-pro-max          (hreflang: es-ES)

# México - Español - Equipo Zionic Pro Max
/mx/es/productos/zionic-pro-max          (hreflang: es-MX)

# Estados Unidos - Inglés - Equipo Zionic
/us/en/products/zionic                   (hreflang: en-US)

# Francia - Francés - Equipo Eneka Pro
/fr/fr/produits/eneka-pro                (hreflang: fr-FR)

# Página corporativa España (contenido compartido)
/es/es/empresa/quienes-somos             (hreflang: es-ES)

# Landing personalizada - Promoción España
/es/es/promocion-zionic-2025             (hreflang: es-ES)
```

### Reglas de URLs
✅ **Todos los segmentos son localizables** - No hay palabras en inglés forzadas  
✅ **Market + Language explícitos** - SEO internacional óptimo  
✅ **Redirects 301 automáticos** - Cuando cambian slugs de sección o contenido  
✅ **Sin query params** - Todo en la ruta para máxima indexabilidad  
✅ **Landings personalizadas** - URLs completamente libres (ej: `/es/es/promocion-verano-2025`)  
✅ **Contenido compartible** - Misma información corporativa reutilizable entre mercados

### Patrones de URL Soportados

```json
{
  "home": "/{marketCode}/{language}",
  "productList": "/{marketCode}/{language}/{productsSectionSlug}",
  "productDetail": "/{marketCode}/{language}/{productsSectionSlug}/{productSlug}",
  "treatmentList": "/{marketCode}/{language}/{treatmentsSectionSlug}",
  "treatmentDetail": "/{marketCode}/{language}/{treatmentsSectionSlug}/{treatmentSlug}",
  "blogList": "/{marketCode}/{language}/{blogSectionSlug}",
  "blogDetail": "/{marketCode}/{language}/{blogSectionSlug}/{postSlug}",
  "newsList": "/{marketCode}/{language}/{newsSectionSlug}",
  "newsDetail": "/{marketCode}/{language}/{newsSectionSlug}/{newsSlug}",
  "pressClipping": "/{marketCode}/{language}/{pressSectionSlug}",
  "corporatePage": "/{marketCode}/{language}/{corporateSectionSlug}/{pageSlug}",
  "customLanding": "/{marketCode}/{language}/{customSlug}"
}
```

---

## 🏗️ Arquitectura del Sistema

### Filosofía: Hexagonal Architecture (Ports & Adapters) - Enfoque Pragmático

**Core business logic desacoplado en `src/` con módulos + `Shared/`**

```
┌──────────────────────────────────────────────────────┐
│  Frontends (Consumers)                               │
│  • Web (Vue + Inertia)                              │
│  • Mobile Apps (Ionic/React Native)                 │
│  • Third-party integrations                         │
└──────────────────┬───────────────────────────────────┘
                   │ HTTP/REST API
                   ↓
┌──────────────────────────────────────────────────────┐
│  Infrastructure Layer (Laravel 11)                   │
│  app/                                                │
│  • HTTP Controllers & Routes                         │
│  • Eloquent Models (Adapters)                       │
│  • Queue/Cache/Events (Adapters)                    │
└──────────────────┬───────────────────────────────────┘
                   │ Implements Ports
                   ↓
┌──────────────────────────────────────────────────────┐
│  Core Business Logic (Framework-agnostic)            │
│  src/                                                │
│  ├─ Catalog/, Content/, Treatments/ (Modules)       │
│  │  ├─ Domain (Aggregates, VOs, Ports)              │
│  │  ├─ Application (Use Cases)                      │
│  │  └─ Infrastructure (Adapters)                    │
│  └─ Shared/ (Reusable primitives)                   │
│     ├─ Domain/ (AggregateRoot, ValueObjects, Bus)  │
│     └─ Infrastructure/ (Base repos, Helpers)        │
└──────────────────────────────────────────────────────┘
```

**Ventajas:**
- ✅ Cambiar de Laravel a Symfony/Nest.js/FastAPI → Solo cambia `app/`, `src/` intacto
- ✅ Testing más fácil → Core sin dependencias externas
- ✅ Independencia tecnológica → Framework es solo un detalle de implementación
- ✅ Longevidad del código → Business logic sobrevive a cambios de framework

### Backend: Laravel 11 (Solo Infraestructura)

**Rol:** Adaptador de infraestructura únicamente
- HTTP routing y controllers
- Eloquent ORM como implementación de repositorios
- Queue, Cache, Events como adaptadores

**Módulos en `src/`:**
- `Catalog/` - Productos (entidad de dominio: equipos médicos con SKU, UDI-DI, regulatory variants, pricing)
- `Treatments/` - Tratamientos médicos (entidad de dominio con claims regulados, indications, contraindications)
- `Content/` - Motor híbrido:
  - `ContentArticle` (entidad de dominio para blog/news/press - motor unificado, mismo modelo diferente tipo)
  - `Page` (entidad CMS simple para landings/corporate/custom pages)
- `Forms/` - 📍 **CRÍTICO** - Sistema de formularios basado en patrón TermoCRM:
  - `Form` (template con estructura dinámica en JSON)
  - `FormSubmission` (instancia de envío con tracking + anti-spam + CRM integration)
  - `FormSubmissionResponse` (respuestas individuales por campo - flexible y queryable)
  - Anti-spam 6 capas (honeypot, throttle, reCAPTCHA v3, rate limits IP/email, disposable blocking)
  - Integración OAuth2 con MiTermosalud CRM (background jobs con retries)
- `GeoTargeting/` - Gestión dinámica de mercados
- `I18n/` - Gestión dinámica de idiomas y traducciones
- `SEO/` - Sitemaps, hreflang, redirects
- `Auth/` - Autenticación y roles
- `Shared/` - 🎯 **Base classes y utilidades reutilizables**

**Capas por Módulo:**
```
src/{Module}/
├── Domain/
│   ├── Aggregates/        (Product.php, Treatment.php - extends AggregateRoot)
│   ├── ValueObjects/      (ProductCode.php, Slug.php - extends base VOs from Shared)
│   ├── Events/            (ProductCreated.php - extends DomainEvent from Shared)
│   └── Repositories/      (ProductRepository.php - Interface/Port)
├── Application/
│   ├── Create/            (ProductCreator.php)
│   ├── Find/              (ProductFinder.php, ProductsByCriteria.php)
│   ├── Update/            (ProductUpdater.php)
│   └── Delete/            (ProductDeleter.php)
└── Infrastructure/
    ├── Eloquent/          (ProductEloquentModel.php)
    ├── Persistence/       (EloquentProductRepository.php - implements Port)
    └── Services/          (External API clients, etc.)

**Shared/ (Reutilizable entre todos los módulos):**
```
src/Shared/
├── Domain/
│   ├── Aggregate/
│   │   └── AggregateRoot.php          (Base class con Domain Events)
│   ├── ValueObject/
│   │   ├── IntValueObject.php         (Base para VOs numéricos)
│   │   ├── StringValueObject.php      (Base para VOs de texto)
│   │   ├── DateValueObject.php
│   │   └── Uuid.php
│   ├── Bus/
│   │   ├── Command/                   (CQRS - Command Bus)
│   │   ├── Query/                     (CQRS - Query Bus)
│   │   └── Event/                     (Domain Events Bus)
│   ├── Criteria/
│   │   ├── Criteria.php               (Queries complejas)
│   │   ├── Filter.php
│   │   └── Order.php
│   ├── Collection.php
│   └── Assert.php                     (Validaciones)
└── Infrastructure/
    ├── Persistence/
    │   └── EloquentRepository.php     (Base repository)
    └── Helpers/
        ├── SlugHelper.php
        └── DateHelper.php
```

### 🚀 API REST (Desde Día 1)

**Base URL:** `/api/v1`  
**Autenticación:** Laravel Passport OAuth2
- Client Credentials para backoffice
- Public endpoints sin auth para formularios

**Consumidores:**
- ✅ Backoffice Web UI (Vue + Inertia) - OAuth2
- ✅ Futuras apps móviles (Ionic, React Native) - OAuth2
- ✅ Formularios públicos (sin autenticación)
- ✅ MiTermosalud CRM (integración bidireccional OAuth2)

**Endpoints Públicos:**
```
GET  /api/v1/markets
GET  /api/v1/markets/{code}/languages
GET  /api/v1/{market}/{language}/products
GET  /api/v1/{market}/{language}/products/{slug}
GET  /api/v1/{market}/{language}/treatments
GET  /api/v1/{market}/{language}/treatments/{slug}
GET  /api/v1/{market}/{language}/content-articles?type=blog|news|press
GET  /api/v1/{market}/{language}/content-articles/{slug}
GET  /api/v1/{market}/{language}/corporate-pages/{slug}
GET  /api/v1/{market}/{language}/landings/{slug}
POST /api/v1/public/forms/{formKey}/submit
GET  /api/v1/public/forms/{formKey}/config
```

**Endpoints Backoffice (OAuth2 Required):**
```
POST   /api/v1/backoffice/products
PUT    /api/v1/backoffice/products/{id}
POST   /api/v1/backoffice/content-articles
PUT    /api/v1/backoffice/content-articles/{id}
POST   /api/v1/backoffice/workflow/approve
POST   /api/v1/backoffice/markets
PUT    /api/v1/backoffice/markets/{code}
GET    /api/v1/backoffice/forms
POST   /api/v1/backoffice/forms
PUT    /api/v1/backoffice/forms/{id}
DELETE /api/v1/backoffice/forms/{id}
GET    /api/v1/backoffice/forms/{id}/submissions
GET    /api/v1/backoffice/submissions/{id}
POST   /api/v1/backoffice/submissions/{id}/resend-to-crm
```

**Documentación:** OpenAPI 3.0 (auto-generada)

### Frontend: Vue 3 + TypeScript + Inertia

**Web:**
- Framework: Vue 3 Composition API
- Type Safety: TypeScript estricto
- Patrón: **Inertia.js** (SPA-like con SSR)
- Estilos: TailwindCSS v4 (sin Bootstrap)
- Build: Vite
- SSR: Inertia SSR server (Node.js para pre-render)

**¿Por qué Inertia y no Nuxt?**
- ✅ Laravel ya es el backend sólido (no necesitamos API REST para todo)
- ✅ SSR simple con `php artisan inertia:start-ssr`
- ✅ Un solo deploy (Laravel + Inertia SSR)
- ✅ -40% menos código (no duplicar lógica backend/frontend)
- ✅ Arquitectura hexagonal aprovechada al máximo
- ✅ Core Web Vitals excelentes con pre-fetch automático
- ❌ Nuxt sería overkill (requiere API REST completa + deploy separado)

**Mobile (Planificado):**
- Ionic o React Native
- Consume API REST (`/api/v1/*`) cuando sea necesario
- Autenticación OAuth2 con Laravel Passport

---

## 🗂️ Motor Unificado de Contenidos

### ContentArticle: Un Modelo, Tres Tipos

**Problema resuelto:** Blog, Noticias y Prensa comparten el 95% de funcionalidad.

**Solución:** Una sola entidad `ContentArticle` con campo `type`:

| Tipo | Descripción | URL Pattern |
|------|-------------|-------------|
| `blog` | Artículos educativos, thought leadership | `/es/es/blog/{slug}` |
| `news` | Noticias corporativas, lanzamientos | `/es/es/noticias/{slug}` |
| `press` | Notas de prensa, cobertura medios | `/es/es/prensa/{slug}` |

**Ventajas:**
- ✅ Sin duplicación de código (mismo CRUD)
- ✅ Backoffice simplificado
- ✅ Mismo sistema de categorías/tags
- ✅ Mismo layout system
- ✅ Un solo workflow de aprobación
- ✅ API unificada con filtro `?type=blog|news|press`

**Campos comunes:**
- `title`, `body`, `excerpt`, `author`, `featuredImage`
- `publishedAt`, `isHighlighted`, `viewCount`
- `categories[]`, `tags[]`
- `seoTitle`, `seoDescription`, `slugLocalized`

---

## 🔀 Routing Explícito (Sin Complejidad Accidental)

### Filosofía: Claridad sobre Flexibilidad

**❌ Evitamos:** Resolución genérica con múltiples checks a BD
```php
// MALO - Complejidad accidental
Route::get('/{market}/{lang}/{slug}', function($slug) {
    // ¿Es producto? Check DB
    if ($product = Product::whereSlug($slug)->first()) return $product;
    // ¿Es tratamiento? Check DB
    if ($treatment = Treatment::whereSlug($slug)->first()) return $treatment;
    // ¿Es blog? Check DB
    if ($article = Article::whereSlug($slug)->first()) return $article;
    // ¿Es landing? Check DB
    if ($landing = Landing::whereSlug($slug)->first()) return $landing;
    // 4+ queries por request → Lento, no cacheable, debug difícil
});
```

**✅ Implementamos:** Routing explícito y predecible
```php
// BUENO - Explícito y performante
Route::get('/{market}/{lang}', HomeController::class);
Route::get('/{market}/{lang}/productos/{slug}', ProductController::class);
Route::get('/{market}/{lang}/tratamientos/{slug}', TreatmentController::class);
Route::get('/{market}/{lang}/blog/{slug}', ContentArticleController::class);
Route::get('/{market}/{lang}/noticias/{slug}', ContentArticleController::class);
Route::get('/{market}/{lang}/prensa/{slug}', ContentArticleController::class);
Route::get('/{market}/{lang}/empresa/{slug}', CorporatePageController::class);
Route::get('/{market}/{lang}/{slug}', CustomLandingController::class); // Fallback
```

**Orden de resolución:**
1. Home page
2. Secciones predefinidas (productos, tratamientos, blog, noticias, prensa, empresa)
3. Custom landings (fallback)
4. 404

**Beneficios:**
- ⚡ Rápido - 1 query en lugar de 4+
- 💾 Cacheable - Rutas predecibles
- 🐛 Debuggable - Sabes exactamente qué controller maneja cada URL
- 📊 Analizable - Tracking y métricas por sección claras

---

## 🗄️ Modelo de Contenido

### Filosofía de Diseño

El sistema separa:
1. **Entidades globales** (Product, Treatment) - Datos invariables
2. **Variantes regulatorias** por mercado (ProductRegulatoryVariant) - Campos regulados
3. **Contenido localizado** por idioma (ContentMaster) - Traducciones
4. **Overrides de mercado** opcionales (ContentMarketOverride) - Adaptaciones locales

### Entidades Principales

#### Productos (Equipos Médicos)

```
Product (global)
├── ProductRegulatoryVariant (por mercado/región regulatoria)
│   ├── intendedUse
│   ├── indications
│   ├── contraindications  
│   ├── warningsPrecautions
│   ├── mdrClassification / fdaPathway
│   └── udiDi / udiPi
├── ContentMaster (por idioma)
│   ├── title, body, claims
│   ├── seoTitle, seoDescription
│   └── slugLocalized
└── ContentMarketOverride (opcional, por mercado específico)
```

**Ejemplos Reales:**
- **Zionic Pro Max** - Equipo de radiofrecuencia multipolar + ultrasonidos
- **Zionic** - Equipo de radiofrecuencia avanzada
- **Eneka Pro** - Sistema de criolipólisis + cavitación

**Tipos de Producto:**
- `EQUIPMENT` - Equipos principales (Zionic Pro Max, Zionic, Eneka Pro)
- `ACCESSORY` - Manípulos, aplicadores, kits complementarios
- `CONSUMABLE` - Geles, electrodos, elementos de un solo uso
- `SOFTWARE` - Módulos software independientes o add-ons

**Relaciones:**
- Los equipos pueden tener accesorios requeridos/recomendados mediante `ProductAccessoryRelation`
- Ejemplo: Zionic Pro Max + Manípulo Facial + Gel Conductor (consumible)

#### Tratamientos

```
Treatment (global)
├── TreatmentRegulatoryVariant (por mercado/región regulatoria)
├── ContentMaster (por idioma)
└── ContentMarketOverride (opcional)
```

**Ejemplos Reales:**
- **Reducción de Grasa Localizada** (con Eneka Pro)
- **Reafirmación Corporal** (con Zionic Pro Max)
- **Tratamiento Facial Antiedad** (con Zionic + manípulos faciales)

#### Páginas Corporativas (Contenido Compartido)

```
CorporatePage (global)
├── ContentMaster (por idioma)
├── SharedAcrossMarkets (boolean) ← NUEVO
└── MarketExceptions (array) ← Mercados que necesitan versión específica
```

**Filosofía de Compartición:**
- ✅ **Por defecto:** Contenido corporativo es global y se comparte entre mercados
- 🌍 **"Quiénes Somos"** → Misma información en /es/es/, /mx/es/, /us/en/
- 🎯 **Excepción:** Si un mercado necesita información específica (ej: "Oficina local en México"), se crea un MarketOverride
- 📝 **Ventaja:** Editar una vez, aplicar a todos los mercados que compartan idioma o criterio

**Casos de Uso:**
- Historia de la empresa → Compartido globalmente
- Valores corporativos → Compartido globalmente  
- Certificaciones → Puede variar por región regulatoria (EU_MDR vs FDA)
- Contacto/Oficinas → Específico por mercado (override)

#### Landings Personalizadas

```
CustomLanding (entidad independiente)
├── id
├── marketCode (puede ser NULL para landing global)
├── language
├── customSlug (ej: "promocion-zionic-2025", "webinar-registro")
├── layoutId (referencia a Layout)
├── seoTitle, seoDescription
├── isActive
├── publishAt / unpublishAt (scheduling)
├── workflowState
└── blocksOrderJson
```

**Características:**
- URL completamente libre: `/{market}/{lang}/{cualquier-slug-personalizado}`
- No atada a secciones predefinidas (productos, blog, etc.)
- Layout 100% personalizable con sistema de bloques
- Ideal para: promociones, eventos, webinars, campañas específicas
- Puede ser específica de un mercado o compartida (marketCode = NULL)

**Ejemplos:**
```
/es/es/promocion-black-friday-2025
/us/en/webinar-zionic-technology
/mx/es/evento-cdmx-marzo
/es/es/descarga-guia-completa
```

#### Marketing de Contenidos

**Taxonomías SEO:**
- `ContentCategory` - Categorías jerárquicas con traducciones por idioma
- `ContentTag` - Etiquetas temáticas con traducciones por idioma
- Aplicables a: productos, tratamientos, blog posts, noticias, landings

**Beneficios:**
- Navegación temática enriquecida
- Recomendaciones internas inteligentes
- Señal SEO fuerte (URLs, breadcrumbs, meta, Schema.org)

---

## 🎨 Sistema de Layouts (Estilo mi.com)

### Concepto

Cada página (producto, tratamiento, blog, corporativa, landing) puede tener su propio diseño único mediante un sistema de bloques flexible.

### Estructura

```
Layout
├── targetType (product, treatment, blog_post, corporate_page, landing)
├── targetId (ID de la entidad)
├── marketCode + language
├── blocksOrderJson (orden de bloques)
├── customCss (CSS personalizado basado en Tailwind)
├── customJs (JS modular y scoped)
└── tailwindSafelistJson (clases dinámicas a preservar)

LayoutBlock
├── blockType (HeroProduct, KeyBenefitsGrid, FAQAccordion, etc.)
├── position (orden)
├── configJson (configuración del bloque)
└── visibilityRulesJson (reglas de visibilidad condicional)
```

### Tipos de Bloques Disponibles

**Generales:**
- `Hero`, `Jumbotron`, `TwoColumnTextImage`
- `ThreeColumnFeatures`, `GalleryGrid`, `GalleryCarousel`
- `VideoSection`, `QuoteHighlight`, `StatisticCounters`
- `FAQAccordion`, `Timeline`, `CardsGrid`
- `CallToAction`, `FormSection`, `TabsContent`

**Específicos de Producto:**
- `HeroProduct`, `KeyBenefitsGrid`, `TechnicalSpecsTable`
- `IndicationsList`, `BeforeAfterCarousel`, `DoctorTestimonial`
- `VideoShowcase`, `ClinicalEvidenceHighlight`
- `ProtocolStepByStep`, `InteractiveDoseCalculator`
- `Viewer3DOr360`, `StickyContactBar`
- `RelatedProductsSlider`, `DownloadCenter`

**Flexibilidad Total:**
- Blog posts pueden usar plantillas comunes o layouts totalmente personalizados
- Cada layout está sujeto al workflow de calidad (draft → approved → published)

---

## ⚖️ Gestión Regulatoria

### Regiones Regulatorias

- **EU_MDR** - Reglamento (UE) 2017/745
  - Campos: mdrClassification, mdrRuleReference, udiDi
- **FDA** - 21 CFR (Estados Unidos)
  - Campos: fdaPathway, fdaRegulationNumber, udiPi
- **LATAM_GENERIC** - Normativas genéricas LATAM

### Campos Regulados por Variante

Cada `ProductRegulatoryVariant` incluye:
- `intendedUse` - Uso previsto (campo crítico MDR/FDA)
- `indications` - Indicaciones aprobadas
- `contraindications` - Contraindicaciones
- `warningsPrecautions` - Advertencias y precauciones
- `regulatoryStatus` - Estado regulatorio (approved, pending, withdrawn)
- `validFrom` / `validUntil` - Fechas de vigencia

### Reglas de Aprobación (Quality Workflow)

```
draft → pending_quality_review → changes_requested ⟲
                                ↓
                            approved → scheduled → published → archived
```

**Actores:**
- **Marketing/Communication:** Editan contenido en draft
- **Quality:** Revisa y aprueba contenido regulado
- **Admin:** Control total (uso excepcional)

**Reglas:**
- Todo contenido que afecte información regulada pasa por Quality
- Solo Quality/Admin pueden publicar
- La web pública solo lee versiones published
- Historial de versiones con diffs a nivel de campo

**Excepciones de Workflow:**
- ✅ **Landings no reguladas** (promociones, eventos) pueden publicarse sin Quality si no mencionan claims médicos
- ✅ **Contenido corporativo genérico** (historia, valores) puede tener workflow simplificado
- ⚠️ **Contenido de equipos/tratamientos** SIEMPRE requiere aprobación de Quality

---

## 🔍 SEO Avanzado

### Hreflang

```html
<!-- Generado automáticamente por combinación market + language -->
<link rel="alternate" hreflang="es-ES" href="https://termosalud.com/es/es/productos/relleno-x" />
<link rel="alternate" hreflang="es-MX" href="https://termosalud.com/mx/es/productos/relleno-x" />
<link rel="alternate" hreflang="en-US" href="https://termosalud.com/us/en/products/filler-x" />
<link rel="alternate" hreflang="fr-FR" href="https://termosalud.com/fr/fr/produits/filler-x" />
<link rel="alternate" hreflang="x-default" href="https://termosalud.com/us/en/products/filler-x" />
```

### Sitemaps

- XML sitemaps por combinación market+language
- Solo URLs publicadas (workflow aprobado)
- Actualización automática ante cambios de slugs

### Schema.org

- Marcado semántico rico por tipo de contenido
- Product schema con campos médicos extendidos
- MedicalWebPage / MedicalBusiness markup
- Breadcrumbs con categorías/tags localizados

---

## 🎯 Estructura de Secciones Localizadas

### Mapping Interno → Slugs Localizados

| Sección Interna | ES | EN | FR | DE | IT | PT | LT |
|----------------|----|----|----|----|----|----|-----|
| products | productos | products | produits | produkte | prodotti | produtos | produktai |
| treatments | tratamientos | treatments | traitements | behandlungen | trattamenti | tratamentos | gydymas |
| blog | blog | blog | blog | blog | blog | blog | tinklarastis |
| news | noticias | news | actualites | news | notizie | noticias | naujienos |
| press | prensa | press | presse | presse | stampa | imprensa | spauda |
| corporate | empresa | company | entreprise | unternehmen | azienda | empresa | imone |

**Gestión:**
- Definidos por idioma, no por mercado
- Actualizables desde Backoffice
- Sistema genera 301 redirects automáticamente ante cambios

---

## 🚀 Rutas Públicas

```php
// Home
GET /{marketCode}/{language}

// Productos (Equipos Médicos)
GET /{marketCode}/{language}/{productsSectionSlug}
GET /{marketCode}/{language}/{productsSectionSlug}/{productSlug}
// Ejemplos: /es/es/productos/zionic-pro-max, /us/en/products/eneka-pro

// Tratamientos
GET /{marketCode}/{language}/{treatmentsSectionSlug}
GET /{marketCode}/{language}/{treatmentsSectionSlug}/{treatmentSlug}

// Blog
GET /{marketCode}/{language}/{blogSectionSlug}
GET /{marketCode}/{language}/{blogSectionSlug}/{postSlug}

// Noticias
GET /{marketCode}/{language}/{newsSectionSlug}
GET /{marketCode}/{language}/{newsSectionSlug}/{newsSlug}

// Prensa
GET /{marketCode}/{language}/{pressSectionSlug}

// Corporativo (Contenido Compartido)
GET /{marketCode}/{language}/{corporateSectionSlug}/{pageSlug}
// Nota: Mismo contenido puede aparecer en múltiples mercados

// Landings Personalizadas (URL Libre)
GET /{marketCode}/{language}/{customSlug}
// Ejemplos: 
//   /es/es/promocion-zionic-2025
//   /us/en/webinar-registration
//   /mx/es/evento-guadalajara

// Sitemaps
GET /sitemap.xml
GET /sitemap-{marketCode}-{language}.xml
```

---

## 📐 Stack Tecnológico Completo

### Core (Framework-Agnostic)
- **Arquitectura:** Hexagonal (Ports & Adapters) + DDD
- **Lenguaje:** PHP 8.2+
- **Ubicación:** `src/BoundedContexts/`
- **Testing:** PHPUnit (Unit + Integration)

### Backend (Infrastructure Layer)
- **Framework:** Laravel 11 (solo como adapter)
- **Base de Datos:** MySQL 8.0+ / PostgreSQL 14+
- **ORM:** Eloquent (como implementación de repositorios)
- **Cache:** Redis 7+
- **Queue:** Redis / Laravel Horizon
- **Auth:** Laravel Sanctum (stateful + token)
- **API:** REST (OpenAPI 3.0)

### Frontend Web
- **Framework:** Vue 3 (Composition API)
- **Lenguaje:** TypeScript 5+
- **Patrón:** Inertia.js (SSR)
- **Estilos:** TailwindCSS 3+
- **Build:** Vite 5+
- **Testing:** Vitest + Vue Test Utils

### Frontend Mobile (Planificado)
- **Opciones:** Ionic / React Native
- **API:** REST (`/api/v1/*`)
- **Auth:** Sanctum token-based

### DevOps
- **Contenedores:** Docker + Docker Compose
- **CI/CD:** GitHub Actions / GitLab CI
- **Hosting:** AWS / GCP / Azure
- **CDN:** CloudFront / Cloudflare
- **Monitoring:** Sentry + New Relic / DataDog
- **Logs:** ELK Stack / CloudWatch

---

## 📁 Estructura del Proyecto

```
termosalud/
├── src/                      # 🎯 CORE - Framework-agnostic
│   ├── Catalog/              # Módulo de Catálogo
│   │   ├── Domain/
│   │   │   ├── Product.php                      # Aggregate Root
│   │   │   ├── ProductRegulatoryVariant.php
│   │   │   ├── ProductRepository.php            # Interface (Port)
│   │   │   ├── ValueObjects/
│   │   │   │   ├── ProductCode.php
│   │   │   │   ├── Slug.php
│   │   │   │   └── UdiDi.php
│   │   │   └── Events/
│   │   │       └── ProductCreated.php
│   │   ├── Application/
│   │   │   ├── Create/
│   │   │   │   └── ProductCreator.php
│   │   │   ├── Find/
│   │   │   │   ├── ProductFinder.php
│   │   │   │   └── ProductsByCriteria.php
│   │   │   ├── Update/
│   │   │   │   └── ProductUpdater.php
│   │   │   └── Delete/
│   │   │       └── ProductDeleter.php
│   │   └── Infrastructure/
│   │       ├── Eloquent/
│   │       │   └── ProductEloquentModel.php
│   │       └── Persistence/
│   │           └── EloquentProductRepository.php  # Adapter
│   ├── Treatments/           # Módulo de Tratamientos
│   │   ├── Domain/
│   │   ├── Application/
│   │   └── Infrastructure/
│   ├── Content/              # Motor unificado: Blog + News + Press
│   │   ├── Domain/
│   │   │   ├── ContentArticle.php               # Aggregate Root
│   │   │   ├── ContentArticleRepository.php
│   │   │   └── ValueObjects/
│   │   │       ├── ArticleType.php              # Enum: blog, news, press
│   │   │       └── ArticleSlug.php
│   │   ├── Application/
│   │   │   ├── Create/
│   │   │   ├── Find/
│   │   │   └── Publish/
│   │   └── Infrastructure/
│   ├── Corporate/            # Módulo Corporativo
│   ├── Landings/             # Módulo de Landings
│   ├── GeoTargeting/         # Módulo de Mercados
│   ├── I18n/                 # Módulo de Idiomas
│   ├── SEO/                  # Módulo SEO
│   ├── Auth/                 # Módulo de Autenticación
│   └── Shared/               # 🎯 CRÍTICO: Código compartido
│       ├── Domain/
│       │   ├── Aggregate/
│       │   │   └── AggregateRoot.php
│       │   ├── ValueObject/
│       │   │   ├── IntValueObject.php
│       │   │   ├── StringValueObject.php
│       │   │   ├── DateValueObject.php
│       │   │   ├── Slug.php
│       │   │   └── Uuid.php
│       │   ├── Bus/
│       │   │   ├── Command/
│       │   │   │   ├── Command.php
│       │   │   │   └── CommandBus.php
│       │   │   ├── Query/
│       │   │   │   ├── Query.php
│       │   │   │   └── QueryBus.php
│       │   │   └── Event/
│       │   │       ├── DomainEvent.php
│       │   │       └── EventBus.php
│       │   ├── Criteria/
│       │   │   ├── Criteria.php
│       │   │   ├── Filter.php
│       │   │   ├── FilterField.php
│       │   │   ├── FilterOperator.php
│       │   │   └── Order.php
│       │   ├── Collection.php
│       │   ├── Assert.php
│       │   └── UuidGenerator.php
│       └── Infrastructure/
│           ├── Bus/
│           │   ├── LaravelCommandBus.php
│           │   ├── LaravelQueryBus.php
│           │   └── LaravelEventBus.php
│           ├── Persistence/
│           │   ├── EloquentRepository.php      # Base repository
│           │   └── EloquentCriteriaConverter.php
│           └── Helpers/
│               ├── ImageHelper.php
│               ├── SlugHelper.php
│               └── DateHelper.php
├── app/                      # 🔌 INFRASTRUCTURE - Laravel adapters
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   └── V1/
│   │   │   │       ├── ProductController.php
│   │   │   │       ├── ContentArticleController.php
│   │   │   │       └── Backoffice/
│   │   │   │           ├── ProductController.php
│   │   │   │           └── WorkflowController.php
│   │   │   └── Web/
│   │   │       ├── ProductController.php
│   │   │       ├── ContentArticleController.php
│   │   │       └── LandingController.php
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/               # Eloquent Models (Infrastructure)
│   │   ├── Product.php
│   │   ├── ContentArticle.php
│   │   └── ...
│   ├── Providers/
│   ├── Console/
│   └── Exceptions/
├── resources/                # 🎨 FRONTEND
│   ├── js/
│   │   ├── app.ts
│   │   ├── types/
│   │   │   ├── index.d.ts
│   │   │   ├── inertia.d.ts
│   │   │   └── models.d.ts
│   │   ├── Pages/
│   │   │   ├── Public/
│   │   │   │   ├── Home.vue
│   │   │   │   ├── Products/
│   │   │   │   │   ├── Index.vue
│   │   │   │   │   └── Show.vue
│   │   │   │   ├── Treatments/
│   │   │   │   ├── Content/       # Blog, News, Press
│   │   │   │   │   ├── Index.vue
│   │   │   │   │   └── Show.vue
│   │   │   │   ├── Corporate/
│   │   │   │   └── Landing.vue
│   │   │   └── Backoffice/
│   │   │       ├── Dashboard.vue
│   │   │       ├── Catalog/
│   │   │       ├── Content/
│   │   │       ├── Markets/
│   │   │       └── Languages/
│   │   ├── Components/
│   │   │   ├── Public/
│   │   │   │   ├── Layout/
│   │   │   │   │   ├── Header.vue
│   │   │   │   │   └── Footer.vue
│   │   │   │   └── Blocks/
│   │   │   │       ├── Hero.vue
│   │   │   │       └── ProductHero.vue
│   │   │   └── Backoffice/
│   │   ├── Composables/
│   │   │   ├── useI18n.ts
│   │   │   ├── useMarket.ts
│   │   │   └── useSEO.ts
│   │   └── Layouts/
│   │       ├── PublicLayout.vue
│   │       └── BackofficeLayout.vue
│   ├── css/
│   │   └── app.css
│   └── views/
│       └── app.blade.php
├── routes/
│   ├── web.php               # Public routes (explicit)
│   ├── api.php               # REST API v1
│   └── backoffice.php        # Admin routes
├── database/
│   ├── migrations/
│   └── seeders/
├── tests/
│   ├── Unit/
│   │   ├── Catalog/
│   │   ├── Content/
│   │   └── ...
│   └── Feature/
│       ├── Api/
│       └── Web/
├── config/
├── storage/
├── public/
├── .env.example
├── composer.json
├── package.json
├── tsconfig.json
├── tailwind.config.js
├── vite.config.ts
├── phpunit.xml
└── README.md
```

**Separación clara:**
- `src/` = Módulos de dominio + Shared/ (migrable a cualquier framework)
- `src/Shared/` = Base classes y utilidades reutilizables (AggregateRoot, ValueObjects, Bus, Criteria)
- `app/` = Laravel infrastructure (reemplazable)
- `resources/` = Frontend (consumidor de la API)

**Arquitectura Híbrida (Domain Entities vs CMS Pages):**
- **Domain Entities** (Product, Treatment, Lead): Lógica compleja, validaciones, integraciones
  - Tienen su propio Finder: `ProductFinder`, `TreatmentFinder`, `LeadFinder`
  - Routing explícito: `/products/{slug}`, `/treatments/{slug}`
- **CMS Pages** (Landing, Corporate, Custom): Solo contenido visual, sin lógica de negocio
  - Un solo Finder: `PageFinder` (catch-all)
  - Routing fallback: `/{slug}` (último en orden)

**Por qué sin carpeta BoundedContexts/:**
- Rutas más limpias: `src/Catalog/` vs `src/BoundedContexts/Catalog/`
- Enfoque pragmático y probado en producción (TermoExpertis)
- Shared/ al mismo nivel que los módulos para fácil acceso
- Menos anidamiento = más legibilidad

---

## 🛠️ Instalación y Setup (Guía Inicial)

**Ubicación del código:** Laravel está en la raíz del proyecto; la documentación vive en `docs/`.

### Requisitos Previos
- Opción recomendada (Sail): Docker Engine/Compose instalados
- Opción manual: PHP 8.2+, Composer 2.6+, Node.js 20+ (npm/pnpm), MySQL 8.0+, Redis 7+

### Opción A: Laravel Sail (Docker, recomendada)

```bash
cp .env.example .env  # ya existe un .env generado; ajusta si necesitas
# Puertos host ya ajustados para evitar conflictos locales: MySQL -> 3307, Redis -> 6381, Mailpit -> 8026
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev   # o "npm run build" para build de producción
```

Notas rápidas Sail:
- Acceso web: http://localhost (puerto configurable con APP_PORT en `.env`).
- Base de datos desde el host: 127.0.0.1:3307 / usuario `sail` / pass `password`.
- Redis desde el host: 127.0.0.1:6381.
- Mailpit dashboard: http://localhost:8026.

### Opción B: Instalación manual (sin Docker)

```bash
composer install
npm install   # o pnpm install
cp .env.example .env
php artisan key:generate

# Configura base de datos local
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=termosalud
# DB_USERNAME=root
# DB_PASSWORD=

php artisan migrate --seed
npm run dev
php artisan serve
```

---

## 🧪 Testing

### Backend (PHPUnit)
```bash
# Unit tests
php artisan test --testsuite=Unit

# Feature tests
php artisan test --testsuite=Feature

# Coverage
php artisan test --coverage
```

### Frontend (Vitest)
```bash
# Tests de componentes
npm run test

# Watch mode
npm run test:watch

# Coverage
npm run test:coverage
```

### E2E (Laravel Dusk)
```bash
php artisan dusk
```

---

## 📈 Roadmap de Desarrollo

### Fase 1: Fundación Arquitectónica (Semanas 1-4)
- ✅ Especificación completa v4.0 (DONE)
- ⏳ Setup de proyecto Laravel 11 con estructura hexagonal
- ⏳ Crear estructura `src/BoundedContexts/` desacoplada
- ⏳ Setup frontend Vue 3 + TypeScript + Inertia
- ⏳ Configuración Docker + CI/CD
- ⏳ Migraciones base de datos
- ⏳ API REST base (`/api/v1/`)

### Fase 2: Core Features + API (Semanas 5-10)
- ⏳ Bounded Context: GeoTargeting (mercados dinámicos)
- ⏳ Bounded Context: I18n (idiomas dinámicos)
- ⏳ Bounded Context: Catalog (productos/equipos)
- ⏳ Bounded Context: Content (ContentArticle unificado)
- ⏳ Routing explícito sin complejidad accidental
- ⏳ API REST pública (markets, products, content-articles)
- ⏳ Variantes regulatorias (MDR, FDA)

### Fase 3: Backoffice & API de Gestión (Semanas 11-16)
- ⏳ Backoffice Web UI (Vue + Inertia)
- ⏳ API Backoffice (`/api/v1/backoffice/*`)
- ⏳ Editor de contenidos multi-idioma
- ⏳ Gestor de layouts y bloques
- ⏳ Workflow de calidad (draft → approved → published)
- ⏳ Gestión de mercados e idiomas desde UI
- ⏳ Taxonomías (categorías y tags)

### Fase 4: SEO & Performance (Semanas 17-20)
- ⏳ Generación de hreflang automática
- ⏳ Sitemaps dinámicos por market+language
- ⏳ Sistema de redirects 301
- ⏳ Optimización Core Web Vitals
- ⏳ Schema.org markup
- ⏳ CDN y caching strategy

### Fase 5: Testing & Launch (Semanas 21-24)
- ⏳ Testing completo (Unit src/, Feature app/)
- ⏳ API testing (Postman/Insomnia collections)
- ⏳ Documentación OpenAPI 3.0
- ⏳ Auditoría de seguridad
- ⏳ Auditoría de accesibilidad (WCAG 2.1)
- ⏳ Migración de contenido legacy
- ⏳ Deployment a producción
- ⏳ Setup monitoring y alertas

### Fase 6: Mobile App (Post-Launch)
- 📋 Diseño de app móvil (Ionic/React Native)
- 📋 Desarrollo consumiendo API REST existente
- 📋 Testing en iOS + Android
- 📋 Publicación en stores

---

## 👥 Roles y Permisos

### Marketing
- Crear/editar contenido en draft
- Crear landings personalizadas con URLs libres
- Asignar categorías y tags
- Gestionar disponibilidad de contenido por mercado
- Previsualizar contenido no publicado
- Indicar si contenido corporativo es compartido o específico de mercado

### Communication
- Crear/editar contenido en draft
- Gestionar blog y noticias
- Programar publicaciones

### Quality
- Revisar contenido regulado
- Aprobar/rechazar publicaciones
- Gestionar workflow de aprobación

### Admin
- Control total del sistema
- Gestión de usuarios y roles
- **Configuración dinámica de mercados** (añadir, editar, activar/desactivar)
- **Configuración dinámica de idiomas** (añadir, editar, activar/desactivar)
- Asignación de idiomas disponibles por mercado
- Configuración de regiones regulatorias
- Gestión de redirects y sitemaps
- Configuración de slugs de sección localizados

---

## 📚 Recursos y Referencias

### Estándares Regulatorios
- **EU MDR:** [Reglamento (UE) 2017/745](https://eur-lex.europa.eu/eli/reg/2017/745/oj)
- **FDA 21 CFR:** [Code of Federal Regulations](https://www.accessdata.fda.gov/scripts/cdrh/cfdocs/cfcfr/cfrsearch.cfm)
- **UDI Database:** [EUDAMED](https://ec.europa.eu/health/medical-devices-eudamed_en)

### Guías SEO
- [Google Multi-Regional Guidelines](https://developers.google.com/search/docs/specialty/international/managing-multi-regional-sites)
- [Hreflang Best Practices](https://developers.google.com/search/docs/specialty/international/localized-versions)
- [Core Web Vitals](https://web.dev/vitals/)

### Inspiración de Diseño
- [Siemens Multi-Market Site](https://www.siemens.com) - Estrategia de URLs
- [Xiaomi Product Pages](https://www.mi.com) - Layouts flexibles
- [Apple Product Pages](https://www.apple.com) - Storytelling visual

---

## 📝 Notas de Desarrollo

### Convenciones de Código
- **PHP:** PSR-12 + Laravel Pint
- **TypeScript:** ESLint + Prettier
- **Vue:** Vue Style Guide (Priority A + B)
- **Commits:** Conventional Commits

### Variables de Entorno Clave
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://termosalud.com

# Idioma y mercado por defecto
DEFAULT_MARKET=es
DEFAULT_LANGUAGE=es

# GeoIP
GEOIP_SERVICE=maxmind
MAXMIND_LICENSE_KEY=xxxxx

# CDN
CDN_URL=https://cdn.termosalud.com

# Cache
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# SEO
SITEMAP_CACHE_TTL=3600
HREFLANG_X_DEFAULT=https://termosalud.com/us/en
```

---

## 🤝 Contribución

Este proyecto sigue el modelo de desarrollo **Git Flow**:
- `main` - Producción
- `develop` - Desarrollo integrado
- `feature/*` - Nuevas funcionalidades
- `hotfix/*` - Correcciones urgentes

### Proceso de PR
1. Fork del proyecto
2. Crear rama feature desde develop
3. Commits con mensajes descriptivos
4. Tests pasando (coverage > 80%)
5. PR a develop con descripción detallada
6. Code review por 2+ personas
7. Merge solo con aprobación de Quality (para contenido regulado)

---

## 📞 Contacto y Soporte

- **Email Técnico:** dev@termosalud.com
- **Email Quality:** quality@termosalud.com
- **Documentación:** [Wiki interno](https://wiki.termosalud.com)
- **Issue Tracker:** GitHub Issues

---

## 📄 Licencia

Proyecto propietario - Termosalud © 2024-2026

---

**🚀 Estado del Proyecto:** En fase de especificación y planificación inicial  
**📅 Última Actualización:** 19 de noviembre de 2025  
**👨‍💻 Mantenedores:** Equipo de Desarrollo Termosalud
