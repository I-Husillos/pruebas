# Termosalud - Sitio web corporativo global

**Estado:** Pre-Alfa (En desarrollo)  
**Sitio Web:** <www.termosalud.com>

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3-4FC08D?style=flat&logo=vue.js)](https://vuejs.org)
[![Inertia.js](https://img.shields.io/badge/Inertia.js-2.0-6B46C1?style=flat)](https://inertiajs.com)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-4.0-06B6D4?style=flat&logo=tailwind-css)](https://tailwindcss.com)

---

> [!NOTE]
> **Guía para desarrolladores**
> ¡Bienvenido al proyecto Termosalud web! Este es un proyecto ambicioso diseñado para ser robusto, escalable y mantenible. No es una web típica de WordPress, sino una plataforma corporativa global completa.
> En esta documentación y en la carpeta `docs/`, encontrarás las explicaciones del **por qué** detrás de cada decisión técnica (DDD, SEO, SSR, etc.). Lee con atención para entender el negocio médico y las bases de nuestra arquitectura.

## 📋 Descripción del proyecto

Plataforma global **multimercado** y **multi-idioma** para la venta e información de equipos de medicina estética.
Para lograr un sistema altamente mantenible, utilizamos **Arquitectura Hexagonal (DDD)**, un diseño orientado a consumir mediante APIs (REST API-first), gestión avanzada de contenido regulado (cumplimiento con normativas MDR y FDA), un catálogo de equipos médicos muy especializado, y una lógica de negocio totalmente **desacoplada del framework Laravel**.

### Características principales (el "por qué")

* **🌍 Multimercado y multi-idioma:** Enrutamiento dinámico `/{mercado}/{idioma}/...` (Ej. España, México, EE.UU., Francia). *¿Por qué?* Porque un mismo producto de medicina estética necesita diferentes certificaciones, descripciones de marketing y precios según el país. No vendemos igual a la UE (normativa MDR) que a EE.UU. (normativa FDA).
* **🏛️ Arquitectura hexagonal:** Nuestra lógica de negocio real reside en la carpeta `src/`, 100% aislada de Laravel. *¿Por qué?* Las reglas del negocio médico no cambian porque actualicemos Laravel de la versión 11 a la 12. De esta forma, nuestra lógica clave es segura, duradera y altamente testeable (no requiere conexión a base de datos para pruebas unitarias).
* **🎨 Frontend moderno (WebApp):** Vue 3 + TypeScript + Inertia.js + TailwindCSS. *¿Por qué una webapp?* Queremos la reactividad y velocidad de una SPA (Single Page Application) sin construir una API y cliente por separado para el frontend de usuario final, Inertia.js une la SPA con Laravel limpiamente.
* **🔍 Optimizado para SEO (SSR):** Implementación completa de hreflang, URLs regionalizadas e Inertia SSR (Server-Side Rendering). *¿Por qué SSR?* Una SPA pura carga en blanco y luego pide datos por JavaScript, lo cual es terrible para Google (SEO). Con SSR, procesamos el Vue en el servidor y mandamos a Google HTML puro ya formateado, logrando indexación perfecta.
* **📦 Catálogo de productos complejo:** Equipamiento médico con SKU, UDI-DI, variables normativas y precios multimercado.
* **🏥 Sistema de tratamientos:** Los tratamientos están relacionados con indicaciones médicas, contraindicaciones y productos específicos. La salud y la seguridad son prioridad.

---

## 🚀 Inicio rápido

### Requisitos previos

* Docker y Docker Compose (Recomendado para igualar entornos)
* O alternativamente: PHP 8.2+, Composer 2.6+, Node.js 20+, MySQL 8.0+, Redis 7+

### Instalación usando Laravel Sail (recomendado)

Laravel Sail nos provee de un entorno Docker basado en las necesidades exactas del proyecto.

```bash
# Clonar el repositorio
git clone https://github.com/Termosalud/mitermosalud.git
cd mitermosalud

# Copiar el archivo de entorno
cp .env.example .env

# Levantar los contenedores de Docker
./vendor/bin/sail up -d

# Ejecutar las migraciones y seeders de la base de datos
./vendor/bin/sail artisan migrate --seed

# Instalar dependencias del frontend (Vue/Inertia)
./vendor/bin/sail npm install

# Compilar los estáticos para entorno de desarrollo
./vendor/bin/sail npm run dev

# Acceder a la aplicación
open http://localhost
```

### Detalles de los contenedores (qué hace cada cosa)

| Servicio | Contenedor | Puerto Host | Propósito |
| :--- | :--- | :--- | :--- |
| Aplicación | `termosalud-web-app` | 80 | El código principal de Laravel servido con Nginx/PHP-FPM. |
| MySQL | `termosalud-web-mysql` | 3307 | Base de datos relacional para la persistencia. |
| Redis | `termosalud-web-redis` | 6381 | Manejo ultrarrápido de caché y sesiones distribuidas. |
| Mailpit | `termosalud-web-mailpit` | 8026 (UI), 1026 | Atrapador local de correos para probar envíos sin spam real. |
| Meilisearch | `termosalud-web-meilisearch` | 7701 | Motor de búsqueda potente (para búsquedas de productos/blog). |

---

## 🏗️ Arquitectura del sistema

### Arquitectura hexagonal (puertos y adaptadores) + SOLID + CQRS

**Lógica de negocio independiente del framework** en el directorio `src/`, modelada mediante conceptos de Domain-Driven Design (DDD).

**Patrones académicos implementados:**

1. **Arquitectura hexagonal:** Separar lo que importa (el dominio) de las herramientas (bases de datos, controladores, frameworks).
2. **CQRS (Segregación de Responsabilidad de Comandos y Consultas):** Separar las clases que *modifican* datos (Commands) de las que *devuelven* datos (Queries). Nos permite escalar la lectura y escritura por separado.
3. **Patrón repositorio:** Escondemos Eloquent (base de datos) detrás de interfaces. Nuestro dominio no sabe qué es SQL ni Eloquent.
4. **Controladores de acción única:** Métodos `__invoke()` (1 archivo = 1 ruta). Evita controladores gigantes con 20 métodos.
5. **Objetos de valor (Value Objects):** Clases inmutables que aseguran que los datos siempre sean válidos (ej. un email siempre será válido si se crea sin dar error).

```text
src/                          # 🏛️ Lógica de negocio (Agnóstica al framework)
├── Shared/                   # Primitivas compartidas
│   ├── Domain/
│   │   ├── Aggregate/AggregateRoot.php
│   │   ├── ValueObject/ (StringVO, IntVO, Slug)
│   │   └── Criteria/ (Búsquedas y filtros)
│   └── Infrastructure/
│       ├── Laravel/ApiController.php
│       └── Persistence/Eloquent/
├── Catalog/                  # Módulo de Catálogo de Productos
│   ├── Domain/
│   │   ├── Product.php (Entidad Principal / Raíz de Agregado)
│   │   ├── ProductId.php (Value Object)
│   │   └── ProductRepository.php (Interfaz, el contrato)
│   ├── Application/          # Casos de Uso (CQRS: Lo que se puede "hacer")
│   │   ├── Create/ProductCreator.php
│   │   ├── Find/ProductFinder.php
│   │   └── ProductResponse.php (DTO para salir hacia afuera)
│   └── Infrastructure/
│       └── Persistence/EloquentProductRepository.php (Implementación real SQL)
...

app/                          # 🚀 Adaptadores de Laravel (La Infraestructura)
├── Http/Controllers/
│   ├── API/V1/               # 🔐 API REST (Consumida por móviles u otros sistemas)
│   ├── Admin/                # 🎛️ Panel de control visual con Inertia
│   └── Web/                  # 🌐 Frontend público
├── Models/                   # Modelos nativos de Eloquent (solo para mapeo de datos)
└── Providers/
    └── RepositoryServiceProvider.php  # Donde unimos el Dominio con la Infraestructura
```

📚 **Visita obligada:** Lee la guía en [HEXAGONAL_ARCHITECTURE.md](docs/HEXAGONAL_ARCHITECTURE.md) y [ARCHITECTURE.md](docs/ARCHITECTURE.md) para un nivel profundo técnico.

---

## 🌍 Sistema multimercado e idiomas

Un error muy común es atar el idioma a un contenido fijo o limitarse a mostrar "/en" como versión genérica global.

**Estructura URL adoptada:**
`/{codigo-mercado}/{idioma}/{seccion}/{contenido}`

**Ejemplos reales:**

* `/es/es/productos/zionic-pro-max` → España (Idioma Español). Cumple normativa CE/MDR.
* `/mx/es/productos/zionic-pro-max` → México (Idioma Español). Cumple registro sanitario específico e impuestos diferentes.
* `/us/en/products/zionic-pro-max` → EE.UU. (Idioma Inglés). Cumple con el extricto reglamento FDA.

**¿Por qué este gran nivel de separación?**

1. **SEO internacional óptimo:** Todo está explícito, el buscador rastrea de un vistazo a qué idioma y país nos dirigimos.
2. **No hay "inglés hardcodeado":** Los slugs cambian de un mercado a otro (`/productos/` vs `/products/` vs `/produits/`).
3. **Redirecciones seguras:** Si un slug (como el nombre de un equipo) debe cambiar legalmente, el sistema permite forzar un Redirect 301 automático al nuevo slug.

---

## 🎨 Entendiendo el frontend (Vue 3 + Inertia + Tailwind)

A priori podrías preguntarte: *"Si usamos Laravel, ¿por qué no usar plantillas Blade?"*

**Las plantillas Blade tradicionales** obligan a recargar la página entera cada vez que haces clic en un link. Resulta en tiempos de carga y flasheos molestos para el usuario.
**Una API aislada consumida por Vue (SPA)** es grandiosa por reactividad, pero penaliza el posicionamiento (SEO), la gestión de roles se vuelve doble, y requiere configurar tokens, CORS, manejo de librerías extra, etc.

**Nuestra solución:** Utilizamos **Inertia.js**. Inertia actúa como un simple pegamento. Seguimos usando los controladores tradicionales y el router nativo de Laravel (`route(...)`), pero en vez de `return view('blade-file')`, hacemos `return Inertia::render('VueComponent')`. Obtenemos lo mejor de ambas plataformas de una forma transparente.

El directorio principal para todo el frontal está en `resources/js/`:

```text
resources/js/
├── Pages/               # Las "Vistas" principales que renderiza Inertia
│   ├── Home.vue
│   ├── Products/
│   │   ├── Index.vue
│   │   └── Show.vue
├── Layouts/             # Componentes base que nunca cambian durante la navegación
│   └── FrontendLayout.vue
└── Components/          # Pequeños fragmentos visuales modulares reutilizables
```

📚 **Profundiza aquí:** Para entender a fondo cómo lidiamos con el gran obstáculo de Inertia (el SEO), lee: [INERTIA_SSR_SEO.md](docs/INERTIA_SSR_SEO.md).

---

## 🔐 API REST segura

Adicionalmente, el proyecto cuenta con un conector puro para integraciones externas, asegurado usando el estándar global **OAuth2** (vía Laravel Passport).

Nuestra API es "ciega" respecto a cómo funciona el frontend propio. Puedes pedirle productos o información con este simple estándar.

*Obteniendo Token (Petición de Ejemplo):*

```bash
curl -X POST http://localhost/oauth/token \
  -H "Content-Type: application/json" \
  -d '{
    "grant_type": "password",
    "client_id": "CLIENT_ID",
    "client_secret": "CLIENT_SECRET",
    "username": "user@termosalud.com",
    "password": "password"
  }'
```

📚 Tienes toda la documentación del API localizable en [API.md](docs/API.md)

---

## 🗃️ Estructura de la base de datos y JSON fields

Hemos solucionado de una manera elegante la internacionalización de campos usando columnas directas guardadas como JSON. Tradicionalmente crearíamos múltiples tablas unidas o plugins, sumando queries a SQL y penalizando tiempos de carga y lógica de guardado.

Con nuestro sistema, todas las entidades base que requieran traducción tienen campos directos `JSON`.
Ejemplo (`name` de la tabla `products` no es un VARCHAR de texto, es un JSON en base de datos):

```json
{
  "name": {"es": "Zionic Pro Max", "en": "Zionic Extreme Pro", "fr": "Zionic Élite"},
  "slug": {"es": "zionic-pro-max", "en": "zionic-extreme-pro", "fr": "zionic-elite"}
}
```

Esto reduce la complicación a la hora de buscar contenido: filtramos por la "clave" (el `lang` que navega el usuario actual) sobre estos JSON usando Query Builder modernos.

---

## 🧪 Testing y validación (el "por qué")

Un proyecto tan crítico donde las diferencias de descripción técnica en un equipo podrían llevarnos a disputas legales (FDA/CE) requiere testing al más alto nivel. No "probamos visualmente en el navegador a ver si cae" en proyectos robustos.

```bash
# Correr TODO
./vendor/bin/sail artisan test

# Solo ciertas partes (Ejemplo, el API)
./vendor/bin/sail artisan test --testsuite=Feature
```

El test debe pasar en verde en local antes de tan siquiera tratar de realizar un Pull Request al repositorio de GitHub original.

---

## 📚 Documento maestro de documentación interna

Tu aprendizaje acaba de empezar. Tenemos desglosado meticulosamente el ecosistema de este desarrollo. Navega a la carpeta `/docs` para ir dominando cada concepto aislado a la perfección como Ingeniero Semi-Senior/Senior.

* 📥 [EXECUTIVE_SUMMARY.md](docs/EXECUTIVE_SUMMARY.md) - Panorama táctico del proyecto completo (Visión corporativa)
* 🏛️ [ARCHITECTURE.md](docs/ARCHITECTURE.md) / [HEXAGONAL_ARCHITECTURE.md](docs/HEXAGONAL_ARCHITECTURE.md) - Bases teóricas del diseño DDD
* 📌 [FEATURES.md](docs/FEATURES.md) - Porqués funcionales precisos de multimercado y de reglas y normativas médicas complejas
* ⚙️ [INERTIA_SSR_SEO.md](docs/INERTIA_SSR_SEO.md) - Todo nuestro esfuerzo Frontend y reglas estrictas de SSR vs CSR para Search Engine Op.
* 📃 [FORMS_SYSTEM.md](docs/FORMS_SYSTEM.md) - Nuestro sistema anti-spam inteligente (formularios GDPR)
* 💻 [DEVELOPMENT_GUIDE.md](docs/DEVELOPMENT_GUIDE.md) - Guía obligatoria sobre estándares de codificación dentro del proyecto
* 📡 [API.md](docs/API.md) - Lista completa de endpoins servidos
* 🚀 [DEPLOYMENT.md](docs/DEPLOYMENT.md) - Guía completa de despliegue automatizado con GitHub Actions

---
