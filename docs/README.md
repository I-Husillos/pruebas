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
- **Motor unificado de contenidos**: tabla `articles` con campo `type` (blog, news, press) compartida por todos los contenidos editoriales
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

**🔧 100% configurable desde backoffice** - Tanto mercados como idiomas son entidades gestionables que pueden añadirse, editarse o desactivarse sin modificar código.

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

El sistema sigue **Arquitectura Hexagonal (Ports & Adapters)**. Ver **[ARCHITECTURE.md](./ARCHITECTURE.md)** para la guía completa con diagramas, estructura de carpetas real y ejemplos de código.

**Mapa rápido:**

| Carpeta | Rol |
|---|---|
| `src/Web/` | Núcleo de negocio puro. Sin Laravel. Módulos: `Product`, `Page`, `Article`, `Treatment`, `Form`, `Market`, `Language`… |
| `app/` | Infraestructura Laravel: Controllers, Eloquent Models, Form Requests, Jobs |
| `resources/js/` | Frontend Vue 3 + TypeScript + Inertia.js |
| `routes/` | Rutas explícitas (nunca resolución genérica multi-query) |
| `database/migrations/` | Migraciones. El esquema de contenidos sigue el patrón de tablas `*_localizations` |

**API REST:** base URL `/api/v1/`. Autenticación Laravel Passport OAuth2. Documentación interactiva (Swagger) en `/api/documentation`.

**Contenidos multi-mercado:** ver **[CONTENT_SYSTEM.md](./CONTENT_SYSTEM.md)** — documenta el esquema de tablas de localización y el formato JSON del BlockEditor.

**Principios clave:**
- Core de negocio en `src/Web/*` (sin dependencia directa de Laravel).
- Laravel (`app/`, `routes/`, `database/`) actúa como capa de entrada/adaptadores.
- El frontend web consume casos de uso vía Inertia y la API REST.

**Lectura recomendada por tema:**
- Diseño de capas y patrones: **[ARCHITECTURE.md](./ARCHITECTURE.md)**
- Contratos y endpoints reales: **[API.md](./API.md)** y Swagger en `/api/documentation`
- Modelo de localizaciones y contenido BlockEditor: **[CONTENT_SYSTEM.md](./CONTENT_SYSTEM.md)**

---

## 🗂️ Contenido y Routing Actual

El modelo de contenido editorial usa `articles` con campo `type` (`blog`, `news`, `press`) y localizaciones en `article_localizations`.

Para el detalle de estructura JSON del contenido (`content`) y SEO por localización, consultar **[CONTENT_SYSTEM.md](./CONTENT_SYSTEM.md)**.

La resolución de rutas públicas es explícita y está definida en `routes/frontoffice.php`:

```php
GET /
GET /{market}/{lang}

GET /{market}/{lang}/productos
GET /{market}/{lang}/products
GET /{market}/{lang}/productos/categoria/{categorySlug}
GET /{market}/{lang}/products/category/{categorySlug}
GET /{market}/{lang}/productos/{slug}
GET /{market}/{lang}/products/{slug}

GET /{market}/{lang}/tratamientos
GET /{market}/{lang}/treatments
GET /{market}/{lang}/tratamientos/{slug}
GET /{market}/{lang}/treatments/{slug}

GET /{market}/{lang}/p/{slug}
GET /{market}/{lang}/forms/{key}
```

---


## 🛠️ Instalación y Setup (Guía Inicial)

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

## 📄 Licencia

Proyecto propietario - Termosalud © 2024-2026
