# Guía de Desarrollo - Termosalud Corporate

¡Bienvenido al equipo de desarrollo! Esta guía te enseñará paso a paso cómo crear una nueva pieza de software (un módulo o "Bounded Context") siguiendo nuestra **Arquitectura Hexagonal**.

Sabemos que al principio puede parecer que escribimos "muchos archivos" para hacer algo simple, pero recuerda la regla de oro: **Diseñamos para que el código dure años y sea fácil de testear, no para terminar hoy rápido.**

---

## 🚀 Cómo crear un nuevo módulo desde cero

Imagina que nos piden crear un módulo de `Consultas` (Inquiries). Así es como lo estructuraríamos paso a paso.

### 1. La capa de dominio (`src/Inquiries/Domain/`)

> [!NOTE]
> **¿Qué es el dominio?**
> Es el corazón de tu código. Aquí no sabes nada de Laravel, ni de bases de datos, ni de APIs. Solo escribes "PHP Puro" que representa las reglas del negocio.

```php
// 1. Value Object (El ID blindado)
namespace Termosalud\Inquiries\Domain;
use Termosalud\Shared\Domain\ValueObject\StringValueObject;

// En vez de usar un string suelto tipo $id = "123", creamos una clase.
// Esto evita que accidentalmente pasemos un ID de Producto donde iba un ID de Consulta.
final class InquiryId extends StringValueObject {}

// 2. La entidad principal (Aggregate Root)
namespace Termosalud\Inquiries\Domain;
use Termosalud\Shared\Domain\Aggregate\AggregateRoot;

final class Inquiry extends AggregateRoot
{
    // Las propiedades son privadas. Nadie puede modificarlas desde fuera sin pasar por nuestros métodos.
    public function __construct(
        private readonly InquiryId $id,
        private readonly string $subject
    ) {}
    
    // Método estático de creación (Factory Method)
    public static function create(InquiryId $id, string $subject): self
    {
        return new self($id, $subject);
    }
    
    // Convertimos nuestro objeto rico a un array tonto para poder guardarlo luego
    public function toPrimitives(): array
    {
        return [
            'id' => $this->id->value(),
            'subject' => $this->subject
        ];
    }
    
    // Reconstruimos el objeto rico desde un array tonto (sacado de la BBDD)
    public static function fromPrimitives(array $data): self
    {
        return new self(
            new InquiryId($data['id']),
            $data['subject']
        );
    }
}

// 3. El contrato del repositorio (Interfaces)
namespace Termosalud\Inquiries\Domain;

// Solo decimos QUÉ queremos hacer, no CÓMO. El dominio no sabe de SQL o Eloquent.
interface InquiryRepository
{
    public function save(Inquiry $inquiry): void;
    public function search(InquiryId $id): ?Inquiry;
}
```

---

### 2. La capa de aplicación (`src/Inquiries/Application/`)

> [!NOTE]
> **¿Qué es la aplicación?**
> Son los Casos de Uso. Las acciones específicas que el usuario quiere hacer (Crear una consulta, Buscar una consulta). Orquestan el Dominio pero siguen sin saber si es por Web, API o Consola.

```php
// Caso de uso: Crear (Command)
namespace Termosalud\Inquiries\Application\Create;

use Termosalud\Inquiries\Domain\Inquiry;
use Termosalud\Inquiries\Domain\InquiryRepository;
use Termosalud\Inquiries\Domain\InquiryId;

final class InquiryCreator
{
    // Pedimos el contrato (Interface), no la implementación de Eloquent.
    // Esto se llama Inyección de Dependencias.
    public function __construct(
        private readonly InquiryRepository $repository
    ) {}
    
    // Al método principal solemos llamarlo __invoke para usar la clase como función
    public function __invoke(string $id, string $subject): void
    {
        // 1. Usamos el Dominio para crear
        $inquiry = Inquiry::create(new InquiryId($id), $subject);
        
        // 2. Usamos el contrato para guardar
        $this->repository->save($inquiry);
    }
}
```

---

### 3. La capa de infraestructura (`src/Inquiries/Infrastructure/`)

> [!NOTE]
> **¿Qué es la infraestructura?**
> ¡Por fin Laravel! Aquí es donde implementamos los contratos usando herramientas reales: SQL, APIs externas, Redis, etc.

```php
// El repositorio real (Eloquent)
namespace Termosalud\Inquiries\Infrastructure\Persistence;

use App\Models\Inquiry as InquiryModel; // El modelo clásico de Laravel
use Termosalud\Inquiries\Domain\Inquiry;
use Termosalud\Inquiries\Domain\InquiryRepository; // El Contrato
use Termosalud\Inquiries\Domain\InquiryId;

// Aquí FIRMAMOS el contrato (implements InquiryRepository)
final class EloquentInquiryRepository implements InquiryRepository
{
    public function save(Inquiry $entity): void
    {
        $data = $entity->toPrimitives(); // Transformamos a primitivas
        
        InquiryModel::updateOrCreate(
            ['id' => $data['id']],
            $data
        ); // Guardamos con magia negra de Laravel
    }
    
    public function search(InquiryId $id): ?Inquiry
    {
        $model = InquiryModel::find($id->value());
        
        if (!$model) return null;
        
        // Rehidratamos la entidad de Dominio pura y se la devolvemos a la Aplicación
        return Inquiry::fromPrimitives($model->toArray());
    }
}
```

---

### 4. La puerta de entrada: El endpoint Laravel (`app/Http/Controllers/...`)

> [!TIP]
> **Regla estricta:** Los controladores solo deben recibir la HTTP Request, sacar los `strings`, llamar a la capa de Aplicación, y devolver JSON. **Cero lógica IF/ELSE de negocio aquí.**

```php
// app/Http/Controllers/API/V1/Inquiries/InquiryPostController.php
namespace App\Http\Controllers\API\V1\Inquiries;

use App\Http\Controllers\ApiController;
use Termosalud\Inquiries\Application\Create\InquiryCreator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class InquiryPostController extends ApiController
{
    // Inyectamos nuestro Caso de Uso
    public function __construct(
        private readonly InquiryCreator $creator
    ) {}
    
    public function __invoke(Request $request)
    {
        // 1. Validación tonta de formato
        $request->validate(['subject' => 'required|string']);
        
        // 2. Generamos un ID Universal (ULID)
        $id = (string) Str::ulid();
        
        // 3. Ejecutamos nuestra capa mágica 
        ($this->creator)($id, $request->input('subject'));
        
        // 4. Devolvemos respuesta estándar unificada
        return $this->sendResponse(['id' => $id], 'Consulta enviada con éxito', 201);
    }
}
```

---

### 5. Atando cabos: El Service Provider

Como en la Aplicación pedimos la Interface (El `InquiryRepository`), Laravel explotará porque no sabe qué clase real usar. Hay que enseñarle el camino en `app/Providers/RepositoryServiceProvider.php`.

```php
public function register(): void
{
    // "Oye Laravel, cuando alguien pida el InquiryRepository, pásale el EloquentInquiryRepository"
    $this->app->bind(
        \Termosalud\Inquiries\Domain\InquiryRepository::class,
        \Termosalud\Inquiries\Infrastructure\Persistence\EloquentInquiryRepository::class
    );
}
```

---

## 📋 Checklist rápida para tickets diarios

Si te han asignado crear una feature nueva, asegúrate de haber checkeado esto:

- [ ] He creado la Entidad en `Domain/` sin depender de Laravel (ni imports de Illuminate).
- [ ] He creado un Contrato de Repositorio (Interface) en `Domain/`.
- [ ] He creado mi creador/buscador en `Application/` que orquesta la clase de arriba.
- [ ] He programado en `Infrastructure/` el acceso a SQL mediante Eloquent.
- [ ] He unido todo en mi `Controller` de `/app/Http/...`.
- [ ] He avisado a Laravel en el `RepositoryServiceProvider` sobre qué clase usar.
- [ ] Las rutas están protegidas en `routes/api.php`.

¡Poco a poco te saldrá natural! Piensa siempre de adentro hacia afuera: **Dominio → Aplicación → Infraestructura.**
