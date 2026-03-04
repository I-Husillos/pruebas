# Arquitectura hexagonal y principios de diseño

> [!NOTE]
> **El por qué de esta guía**
> Como desarrollador, seguramente hayas aprendido a construir aplicaciones siguiendo el clásico MVC (Modelo-Vista-Controlador). Este documento sirve para entender por qué, a nivel académico y práctico, hemos descartado el MVC clásico en Termosalud a favor de una estructura guiada por el dominio y regida por los Principios SOLID.

## 🎯 Principios SOLID aplicados en el proyecto

Los principios SOLID son 5 reglas básicas de la programación orientada a objetos orientadas a crear software de alta mantenibilidad. Así es como los aplicamos aquí:

### 1. Single Responsibility Principle (SRP) - Principio de Responsabilidad Única

**Regla:** Una clase debe tener solo una razón para cambiar (hacer solo una cosa).

- **Cómo lo aplicamos:** En lugar de tener un "ProductController" gigante de 2000 líneas que cree, borre, cambie fotos y mande emails, separamos todo.
- Tenemos **Casos de Uso** súper específicos: existe un script `ProductCreator.php` (solo crea) y un `ProductDeleter.php` (solo elimina). El controlador de Laravel se limita a un único endpoint.

### 2. Open/Closed Principle (OCP) - Principio Abierto/Cerrado

**Regla:** El software debe estar abierto para extenderse, pero cerrado a modificarse.

- **Cómo lo aplicamos:** Si mañana queremos guardar los Logs de los formularios también en un archivo de texto en vez de en Base de Datos, no modificamos la lógica del negocio (`FormSubmissionCreator.php`). Simplemente construimos un nuevo `TxtFormSubmissionRepository` que implemente la Interfaz y cambiamos una línea en la configuración. Extendemos la app sin tocar el código viejo.

### 3. Liskov Substitution Principle (LSP) - Principio de Sustitución de Liskov

**Regla:** Las clases hijas deben poder sustituir a las clases padre sin romper la aplicación.

- **Cómo lo aplicamos:** Cualquier implementación de nuestro `ProductRepository` (ej. si programamos un `MongoProductRepository.php` o un `CacheProductRepository.php`) devolverá siempre exactamente el mismo tipo de objeto Entidad (el modelo de dominio de producto) que devuelve el MySQL. El resto del proyecto jamás notará la diferencia de quién originó el almacenamiento.

### 4. Interface Segregation Principle (ISP) - Principio de Segregación de Interfaces

**Regla:** Es mejor tener muchas interfaces pequeñas de propósito único que una muy grande.

- **Cómo lo aplicamos:** No hacemos contratos generales obligando a un proceso que solo necesita borrar a implementar forzosamente el código necesario de guardar.

### 5. Dependency Inversion Principle (DIP) - Principio de Inversión de Dependencias

**Regla:** Módulos de alto nivel (el núcleo, el negocio) no deben depender de módulos de bajo nivel (bases de datos, framework). Ambos deben depender de abstracciones (interfaces).

- **Cómo lo aplicamos:** Es la **base** de toda nuestra Arquitectura Hexagonal. Nuestro Dominio puro solo sabe "llamar" a los esquemas de funciones dictados por las Interfaces (las abstracciones). En otras palabras, la regla de negocio no depende de Eloquent.

---

## 🧩 Patrones específicos implementados

### 1. Patrón Repositorio (Repository Pattern)

Es simplemente una clase cuya única responsabilidad es **extraer o enviar** datos hacia el modelo de base de datos ocultando esa complejidad. El resto del proyecto llama al Repositorio, y este internamente, invoca la línea SQL de Eloquent, la traduce a una lista plana, y la devuelve hacia el sistema. Sirve de barrera mental y tecnológica.

### 2. CQRS (Segregación de Comandos y Consultas) y los Buses de Mensajes

Es el simple hecho de separar arquitectónicamente cualquier acceso de **escritura** de las búsquedas de **lectura**. Para orquestar esto en nuestra capa de Aplicación (especialmente con nuestro reciente refactoring), utilizamos el patrón **Message Bus** (Bus de Mensajes):

- **Command Bus (Bus de Comandos) y Commands:** Representan una intención de accion que **cambia o inserta** en la BBDD (modifican estado). El controlador no ejecuta la acción directamente, sino que "despacha" un `Command` (ej: `UpdatePageCommand`) al `CommandBus`. Un manejador aislado (`CommandHandler`) lo interceptará y ejecutará. Solo pueden lanzar excepciones o terminar con éxito completo.
- **Query Bus (Bus de Consultas) y Queries:** Representan una **pregunta** al sistema (solo lectura). El controlador hace una petición enviando una `Query` (ej: `FindPageBySlugQuery`) al `QueryBus` usando `ask()`, y obtiene directamente como respuesta un DTO inmutable. Jamás deben tocar o modificar las tablas de la base de datos temporalmente.

> [!TIP]
> **El Secreto de la Asincronía preparada para el futuro**
> Como programador, te preguntarás: *"¿Para qué mandar una caja (Command) a un Bus de Mensajes en vez de ejecutar la función directamente?"*
> Aunque actualmente estemos trabajando con **sincronía** en primera instancia (el código de PHP espera a que termine de procesar para devolver la vista al usuario), esta arquitectura nos regala flexibilidad absoluta. Si mañana determinamos que `PublishPageCommand` tarda 10 segundos en notificar a servicios externos, simplemente configuramos el `CommandBus` para encolarlo (mandarlo en background/Redis). Así pasa a ejecutarse de forma **asíncrona** en milisegundos de cara al usuario final, y **sin tener que reescribir nuestra lógica de negocio ni nuestros controladores**.

### 3. Controladores de Acción Única (Single Action Controllers)

En Laravel encontrarás controladores en `app/Http/Controllers/API` con un solo método `__invoke()`. No hay `@index`, `@store`, `@delete` en la misma clase. Cada método HTTP que recibe el router apunta físicamente a un controlador unitario por archivo (`ProductsGetController`, `ProductPostController`). Esto hace inmensamente fácil rastrear errores de un vistazo a las carpetas.

### 4. Objetos de valor (Value Objects) y raíces de agregado

Un *Value Object* no tiene identidad matemática. Un email válido de contacto es simplemente un concepto de email siempre validado. Creamos clases como `Email`, `Slug` o `ProductCode`.

- **Raíz de agregado (Aggregate Root):** Son nuestras Entidades principales. El "Producto" es la raíz, porque no puedes comprar "Variaciones Normativas Médicas" sueltas. No existen por separado. Pertenecen obligatoriamente y conforman el Producto Global. Cuando eliminamos/guardamos la "Raíz de agregado", también eliminamos/guardamos su ecosistema menor interno de forma encapsulada.

---

## ⚡ En resumen: el "por qué"

Nuestro equipo suele preguntarse: **"¿Por qué tanto trabajo y abstracción extra solo para hacer un SELECT de unos productos?"**

La respuesta es que el proyecto Termosalud web no es solo un gestor de catálogo web normal. Hay certificaciones de índole paramédico (por normativas como FDA de Estados Unidos y directivas MDD/MDR europeas) asociadas al correcto despliegue visual de datos en nuestra aplicación, y formularios críticos gestionando miles de leads mensuales sensibles y de alto valor conectados al CRM interno de ventas, además de contar con múltiples idiomas, territorios y cruces de contenido dinámico.

Si todo este conglomerado existiera dentro de simples vistas Blade enredadas de lógica IF en medio controladores Laravel gigantes y se rompiera la conexión a la base de datos o hubiera que actualizar Laravel a una versión incompatible, el core vital transaccional del negocio web desaparecería irrecuperablemente o sería intransferible a un equipo futuro.

Con la **Arquitectura Hexagonal**:
> **El negocio fluye independientemente del entorno tecnológico implementado en cada época**.
