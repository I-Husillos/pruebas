# Arquitectura del sistema - Termosalud Corporate

> [!NOTE]
> **Nota para Desarrolladores**
> Si vienes de aprender Laravel o cualquier otro framework MVC tradicional, esta sección puede resultar chocante al principio. Aquí explicamos **por qué** hemos decidido separar la lógica de negocio (el "corazón" de la aplicación médica) del propio framework Laravel, utilizando **Arquitectura Hexagonal** y **Domain-Driven Design (DDD)**.

## 🎯 Filosofía: El Framework es un "Detalle"

En un proyecto estándar, solemos escribir toda la lógica en los Controladores o en los Modelos de base de datos (Eloquent). El problema de esto en aplicaciones empresariales y médicas complejas es que **acoplamos nuestras reglas de negocio a herramientas externas**.

Este proyecto implementa **Arquitectura Hexagonal (también llamada Puertos y Adaptadores)** para lograr:

- ✅ **Independencia del framework**: Nuestra lógica del negocio médico vive en la carpeta `src/` y no sabe qué es Laravel, qué es una petición HTTP o qué es una base de datos MySQL.
- ✅ **Alta Testabilidad**: Al no depender de la base de datos, podemos ejecutar miles de tests unitarios en milisegundos para validar nuestras reglas de negocio.
- ✅ **Longevidad**: Si mañana Laravel queda obsoleto y queremos usar Symfony o cualquier otra herramienta, nuestra carpeta `src/` (nuestro dominio) se mantiene intacta.
- ✅ **Flexibilidad de Entrada/Salida**: Podemos conectar nuestra lógica a una Web, a una API REST para aplicaciones móviles, o a comandos de consola (CLI), todo usando el mismo código central.

---

## 📐 Diagrama de capas (la "cebolla")

Imagina la aplicación como una cebolla. Las capas exteriores son los mecanismos de entrega (la web, la base de datos) y el núcleo central es el negocio. La regla de oro es que **las dependencias siempre apuntan hacia adentro**. El núcleo no sabe nada del exterior.

```text
┌────────────────────────────────────────────────────────────┐
│                    EL MUNDO EXTERIOR                       │
│  • Navegador Web (Vue 3 + Inertia.js SSR)                  │
│  • Aplicaciones Móviles (consumiendo API REST)             │
│  • Cron Jobs / Comandos CLI                                │
└────────────────┬───────────────────────────────────────────┘
                 │ HTTP / REST
                 ↓
┌────────────────────────────────────────────────────────────┐
│          CAPA DE INFRAESTRUCTURA (Carpeta app/)            │
│          Laravel 12 - Actúa como "Adaptador"               │
│                                                            │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ Controladores HTTP (Web + API)                       │  │
│  │  Reciben la petición y llaman a los Casos de Uso     │  │
│  └──────────────────────────────────────────────────────┘  │
│                          ↓                                 │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ Modelos de Eloquent (Mapeadores de Datos)            │  │
│  │  Solo se usan para leer/escribir de la BBDD          │  │
│  └──────────────────────────────────────────────────────┘  │
│                          ↓                                 │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ Implementaciones de Repositorios (Adaptadores)       │  │
│  │  Ej. EloquentProductRepository (Sabe cómo guardar    │  │
│  │      productos usando Eloquent)                      │  │
│  └──────────────────────────────────────────────────────┘  │
└────────────────┬───────────────────────────────────────────┘
                 │ Implementa las "Interfaces" (Puertos)
                 ↓
┌────────────────────────────────────────────────────────────┐
│        CAPA DE APLICACIÓN (Carpeta src/.../Application)    │
│        Casos de Uso o "Lo que el usuario puede hacer"      │
│                                                            │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ Casos de Uso (CQRS: Commands y Queries)              │  │
│  │  Ej. CrearProducto, EliminarTratamiento              │  │
│  └──────────────────────────────────────────────────────┘  │
│                          ↓                                 │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ DTOs (Objetos de Transferencia de Datos)             │  │
│  │  Estructuras simples para devolver datos hacia fuera │  │
│  └──────────────────────────────────────────────────────┘  │
└────────────────┬───────────────────────────────────────────┘
                 │ Usa Entidades y Reglas del Dominio
                 ↓
┌────────────────────────────────────────────────────────────┐
│           CAPA DE DOMINIO (Carpeta src/.../Domain)         │
│           Lógica de Negocio Pura - CERO Dependencias       │
│                                                            │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ Entidades (Modelos Ricos del Dominio)                │  │
│  │  Ej. Producto, TratamientoMédico, Artículo           │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                            │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ Objetos de Valor (Value Objects - Inmutables)        │  │
│  │  Ej. CodigoProducto, ID, Precio, Email               │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                            │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ Interfaces de Repositorios ("Puertos")               │  │
│  │  Ej. Interfaz ProductRepository (Define QUÉ métodos  │  │
│  │      deben existir, pero no CÓMO se implementan)     │  │
│  └──────────────────────────────────────────────────────┘  │
└────────────────────────────────────────────────────────────┘
```

---

## 🗂️ Estructura de carpetas detallada y roles

```text
src/
├── Catalog/                              # Módulo de Catálogo de Productos (Bounded Context)
│   ├── Domain/                           # ⭐ Capa de Dominio (Sin Laravel)
│   │   ├── Product.php                   # Entidad Principal (Raíz de Agregado)
│   │   ├── ProductRepository.php         # Puerto (Interfaz: Contrato que dice qué hay que hacer)
│   │   ├── ValueObjects/                 # Objetos inmutables que validan sus propios datos
│   │   │   ├── ProductCode.php           
│   │   │   └── ProductSlug.php           
│   │   └── Exceptions/                   # Errores específicos del negocio
│   │       └── ProductNotFoundException.php
│   │
│   ├── Application/                      # ⭐ Capa de Aplicación (Orquestadores)
│   │   ├── Create/
│   │   │   └── ProductCreator.php        # Caso de Uso CQRS: Script que "Crea un Producto"
│   │   ├── Find/
│   │   │   └── ProductFinder.php         # Caso de Uso CQRS: Script que "Busca un Producto"
│   │   └── Update/
│   │       └── ProductUpdater.php
│   │
│   └── Infrastructure/                   # ⭐ Capa de Infraestructura (Implementación técnica)
│       └── Persistence/
│           └── EloquentProductRepository.php  # Adaptador (Sabe de bases de datos y SQL/Eloquent)
│
├── Forms/                                # Módulo de Formularios Inteligentes / CRM
│   └── (Misma estructura: Domain, Application, Infrastructure)
│
└── Shared/                               # Todo el código transversal que usan todos los módulos
    ├── Domain/
    │   ├── ValueObject/
    │   │   ├── StringValueObject.php     # Reutilizable para cualquier texto
    │   │   └── Uuid.php                  # Reutilizable para crear IDs universales
    │   └── Criteria/                     # Sistema agnóstico para hacer búsquedas y paginación
    └── Infrastructure/
        └── Persistence/
            └── EloquentRepository.php    # Clases base para Eloquent
```

---

## 🔌 Inyección de dependencias: uniendo puertos y adaptadores

Si nuestra capa de Dominio y Aplicación no saben de bases de datos, pero en algún momento hay que guardar los datos... ¿cómo lo hacemos?

Utilizamos **Inyección de Dependencias**.
El Dominio define un *Puerto* (una `Interface` llamada `ProductRepository` con un método `save()`).
La capa de Infraestructura crea un *Adaptador* (una clase `EloquentProductRepository` que implementa esa interfaz y hace el código SQL).

Luego, en los archivos de configuración de Laravel (`AppServiceProvider.php`), le decimos al sistema:
*"Oye, cada vez que una clase pida un `ProductRepository`, entrégale un `EloquentProductRepository`"*.

```php
<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Termosalud\Catalog\Domain\ProductRepository;
use Termosalud\Catalog\Infrastructure\Persistence\EloquentProductRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Enlazar el Puerto (Interfaz) con el Adaptador (Implementación tecnológica)
        $this->app->bind(
            ProductRepository::class,
            EloquentProductRepository::class
        );
        
        // Magia: Si en el futuro cambiamos a base de datos en memoria o MongoDB, 
        // solo cambiamos esta línea. El resto de la "cebolla" se queda intacta.
    }
}
```

---

## 💡 Flujo completo: ejemplo paso a paso

Supongamos que un usuario quiere crear un nuevo Producto de Termosalud desde el Panel de Administración.

1. **La Petición HTTP llega a Laravel (`app/ Http/`)**
   El controlador validará si el JSON recibido es correcto.
   Luego instanciará un **Comando** (ej. `CreateProductCommand`) y lo enviará al **Command Bus**. No llama directamente a la lógica de negocio.

2. **El Manejador orquesta el Caso de Uso (`src/.../Application/Create/CreateProductCommandHandler.php` -> `ProductCreator.php`)**
   El sistema de buses dirige el comando a su manejador específico. Este llama al orquestador (`ProductCreator`), el cual creará los Objetos de Valor y Entidades (Dominio) usando los datos simples proporcionados en el comando.

3. **La Entidad Valida el Negocio (`src/.../Domain/Product.php`)**
   La Entidad `Product` es instanciada. Al llamarse a su función interna, valida desde su lógica de negocio pura si los datos provistos tienen sentido para un producto médico (ej. ¿El tipo de producto es EQUIPMENT, ACCESSORY o SOFTWARE?).

4. **El Caso de Uso delega en el Repositorio (`ProductCreator` -> `ProductRepository::save()`)**
   Una vez la entidad está correctamente creada, el constructor de la capa de aplicación le dice al contrato (interfaz): "Guárdame esto". No sabe si se va a guardar en un Excel, MySQL o en la nube de AWS.

5. **El Adaptador SQL escribe el dato (`src/.../Infrastructure/.../EloquentProductRepository.php`)**
   El código específico de Eloquent toma los datos puros de la Entidad de Dominio y mapea sus características de cara a la propia tabla y columnas de MySQL para ser persistidos por fin en disco.

---

## ✅ Checklist definitivo

Cuando vayas a crear una nueva funcionalidad, comprueba:

- [ ] ¿Mis **Entidades de Dominio** (`Entity.php`) están libres de `use Illuminate\...` o de Modelos de Base de datos?
- [ ] ¿He creado una **Interfaz de Repositorio** (Puerto) en el Domain?
- [ ] ¿He creado una **Implementación de Repositorio** (Adaptador) en la capa de Infrastructure que interactúa con bases de datos?
- [ ] ¿Mis **Casos de Uso** (Carpetas `Create`, `Find`, `Search`) orquestan las acciones pero no acceden de forma directa a SQL?
- [ ] ¿He configurado el **ServiceProvider** para enlazar la Interfaz y su Adaptador real?
- [ ] ¿Mi **Controlador** en `app/` llama al Caso de Uso o ensucia el código tratando él mismo la BBDD?

Si cumples esto, ¡estás desarrollando bajo el estándar oficial del proyecto Termosalud!

---

**Siguiente paso:** Para profundizar un poco más en la organización interna y en conceptos como los Principios SOLID en nuestro código, lee [HEXAGONAL_ARCHITECTURE.md](docs/HEXAGONAL_ARCHITECTURE.md).
