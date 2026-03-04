# Documento de requisitos del proyecto (PRD) - Termosalud

## 1. Visión general

Termosalud es una plataforma web integral diseñada para gestionar el contenido corporativo, catálogos de productos y tratamientos, con un fuerte enfoque en el soporte multi-mercado y multi-idioma. El sistema actúa tanto como sitio web corporativo público como herramienta avanzada de administración interna. El objetivo principal es proporcionar una experiencia de usuario premium, moderna y escalable, migrando de soluciones monolíticas hacia una arquitectura orientada al dominio.

## 2. Stack tecnológico

- **Backend:** Laravel 12.x, PHP 8.2+
- **Frontend:** Vue.js 3, Inertia.js, TailwindCSS
- **Base de Datos:** MySQL (con migración desde WordPress)
- **Autenticación:** Laravel Breeze + Passport para API
- **Arquitectura:** DDD (Domain-Driven Design) / Arquitectura Hexagonal

## 3. Arquitectura y diseño

El proyecto sigue los patrones de **Domain Driven Design (DDD)** para organizar la lógica de negocio de forma clara y desacoplada de la infraestructura. La estructura se organiza dentro del directorio `src/`, dividiéndose en módulos (Catalog, Content, Forms, GeoTargeting, Users, Treatments).

### Estructura de Capas

- **Dominio:** Contiene las entidades, Value Objects, interfaces de repositorios y lógica de negocio pura.
- **Aplicación:** Implementa los casos de uso mediante el uso de Queries y Commands (CQRS). Utiliza Response DTOs para estandarizar la salida de datos.
- **Infraestructura:** Contiene las implementaciones específicas de persistencia (Eloquent), controladores de Laravel y proveedores de servicios.

## 4. Funcionalidades principales

### 4.1 Catálogo de productos y tratamientos

- **Gestión de productos:** Catálogo completo organizado por categorías.
- **Tratamientos especializados:** Listado y detalles de tratamientos estéticos y de salud.
- **Búsqueda avanzada:** Implementación del patrón `SearchByCriteria` para filtrado y paginación flexible en todas las entidades.

### 4.2 Gestión de contenidos (CMS)

- **Landings personalizadas:** Creación de páginas estáticas y dinámicas.
- **Blog automático:** Sistema de artículos con categorías y soporte multi-idioma.
- **Menús y widgets:** Gestión dinámica de la navegación y componentes de la interfaz.

### 4.3 Multi-mercado y multi-idioma

- Soporte para múltiples mercados geográficos con configuraciones específicas.
- Sistema de idiomas habilitado por mercado.
- Enrutamiento dinámico basado en el contexto `/{mercado}/{idioma}`.

### 4.4 Panel de administración (`/trmadmin`)

- Dashboard intuitivo con estadísticas en tiempo real.
- CRUDs estandarizados para todas las entidades del sistema.
- Editores WYSIWYG (Tiptap) integrados para la gestión de contenido rico.
- Sistema de gestión de medios (imágenes/archivos) centralizado.

## 5. Control de cambios y calidad

Para asegurar la excelencia en los datos publicados, el sistema incorpora un flujo de **Control de cambios**:

- Las modificaciones importantes pueden requerir un flujo de "Borrador y aprobación".
- Los cambios realizados por los editores son revisados por el departamento de calidad.
- Trazabilidad completa de las acciones realizadas en el panel administrativo.

## 6. Despliegue y optimización

- Pipeline de despliegue automatizado mediante **GitHub Actions**.
- Optimización de imágenes y caché de consultas para garantizar altos rendimientos.
- Enfoque SEO dinámico con metadatos gestionados desde el panel administrativo.
