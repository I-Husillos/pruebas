# Características Clave del Sistema - Guía para desarrolladores

Este documento detalla las funcionalidades "Core" del modelo de negocio de Termosalud. Comprender el **por qué** detrás del diseño técnico te ayudará a no romper reglas críticas.

---

## 🚀 1. Diseño API-First

A diferencia de webs clásicas que devuelven HTML directamente desde Laravel atando los datos a la vista, nuestro sistema está diseñado de base con un enfoque **API-First**.

### ¿Por qué lo hacemos así?

Termosalud no es solo una página web. Imagina que el año que viene lanzamos una App móvil para Doctores, o que permitimos que las clínicas integren nuestro catálogo técnico a sus propios ERPs. Si atamos nuestra lógica a las vistas de Laravel, ninguna App externa o partner podría usarlo.
Construyendo una API robusta bajo las rutas `/api/v1/`, garantizamos que:

- Vue (Inertia) consume la misma API (a nivel lógico de Servicios de Aplicación) que usaría una aplicación externa.
- Toda regla de negocio se cumple matemáticamente y las interacciones con nuestra base de datos están vigiladas de forma universal bajo autorización OAuth2 de *Laravel Passport*.

---

## 📝 2. Motor unificado de contenidos

### Menos código = menos bugs (principio DRY)

Históricamente, muchos proyectos crean una tabla, un modelo y un controlador diferente para el **Blog**, luego copian-y-pegan el código y crean la tabla/controlador para **Noticias**, y de nuevo repiten todo para las **Notas de Prensa**.

Mantener TRES sitios paralelos para algo que en esencia es "Título, Fotografía y Cuerpo" es una pesadilla de mantenimiento.

En Termosalud usamos el patrón de **Single Table Inheritance** (Herencia de tabla única adaptada). Solo existe la Entidad `ContentArticle`, que cuenta internamente con un campo `type` (`enum: blog, news, press`).

- Un único CRUD en el backoffice.
- Reutilización de categorías y etiquetas.
- Mismos flujos lógicos de publicación.

Las direcciones web que el usuario ve diferirán gracias al controlador (`/es/es/blog/` vs `/es/es/noticias/`), pero el núcleo tecnológico es solo uno. ¡No te repitas! (Dont Repeat Yourself).

---

## 🌐 3. Gestión dinámica de mercados e idiomas

Esta es tal vez la característica más compleja e importante de Termosalud Web.

### ¿Por qué la arquitectura "Multi-Market"?

*Imagina vender una televisión. Ya sea que estés en Madrid, Nueva York o Tokio, el aparato es básicamente el mismo.*
**En equipamiento médico-estético, esa regla no aplica.**

Los aparatos que fabricamos sufren férreas regulaciones gubernamentales.

- En España (Europa), un producto debe cumplir la normativa `EU_MDR` (Medical Device Regulation).
- En Estados Unidos, el diseño y afirmaciones deben estar visados bajo la estricta `FDA`.
- En Latinoamérica (`LATAM_GENERIC`), las métricas, potencias e indicaciones varían.

Por tanto, **no podemos usar un sistema que solo cambia el "idioma"**. Un sistema de traducción clásico (como instalar WPML en Wordpress o solo cambiar strings) mandaría la ficha técnica Europea en inglés a los Estados Unidos, arriesgándonos a sanciones internacionales.

### Funcionamiento de la separación

Nuestra estructura base es: `/{codigo-mercado}/{idioma}/{seccion}`

Ejemplos:

- `/es/es/productos/zionic` -> Un español de España consume la legalidad **EU_MDR** en español.
- `/us/en/products/zionic` -> Un americano de USA consume la legalidad **FDA** en inglés.
- `/us/es/products/zionic` -> Un hispano de USA consume la legalidad **FDA** pero totalmente traducida a español nativo.

> [!CAUTION]
> **Nunca vincules la información regulatoria médica (potencia, certificaciones) ni el inventario (precio, availability) al "Idioma"**. El idioma solo dicta la traducción literal. **Es el "Mercado" (Market) el encargado legal de dictaminar qué máquina de base de datos se le presenta al controlador.**

Los administradores pueden añadir nuevos Mercados y enchufar idiomas a dicho mercado instantáneamente desde el panel Admin sin tocar código, expandiéndonos a nuevos nichos globalmente al instante.

---

## 🏢 4. Contenido corporativo compartido

A pesar del caso tan estricto con los mercados normativos (arriba descrito), no queremos escribir de nuevo los datos generales de la empresa por cada país que agreguemos.

Para resolver este rompecabezas arquitectónico, las directivas crearon la lógica del **contenido corporativo compartido con override local.**

### Lógica

- ¿Quién es el fundador? ¿Cuál es la misión de empresa? Esto es exactamente igual en todos lados. Se guardan con `is_shared_across_markets = true` en base a idioma.
- ¿Los teléfonos y la dirección de la Sede central de contacto? Puede variar. Termosalud México debe mostrar sus propias oficinas. En este caso el sistema permite inyectar un *Override* JSON en BBDD para sustituir el contenido base por la excepción local para ese mercado.

### Qué ganas con esto

Ciframos que el 90% de los textos institucionales se unifican globalmente facilitando su actualización centralizada desde España, salvando dolores de cabeza inmensos.

---

## 🎨 5. Landings personalizadas con URLs libres

Los departamentos de Marketing modernos no pueden estar limitados a lo que los programadores les ofrecen fijo en su menú.

Crear campañas de "Black Friday" u "Oferta de Lanzamiento Webinar 2026" requiere una mezcla única de Videos, Formularios, Contadores, Precios... ¡Y todo bajo su propia URL libre! (no escondido dentro de `/blog/promocion`).

En el sistema `Custom Landings` los bloques visuales de Vue3 han sido creados independientemente (Drag&Drop), y Marketing puede combinarlos a voluntad y publicarlos sobre direcciones inventadas de primer nivel en cada mercado (ej. `/es/es/oferta-exclusiva-junio`).

---

## 🔀 6. Sistema de prioridad de routing

Al tener *Landings de URLs libres*, entra un gran problema: **El fraude de enrutamiento**. ¿Qué pasa si Marketing, sin querer, crea una Landing en la URL libre e inventada `/productos`?
Colisionaría con nuestro código oficial de Laravel que renderiza la tienda entera.

Para eso previene a Laravel para que priorice rutas duras blindadas, y los controladores del CMS hacen validaciones antes de guardar ninguna URL maliciosa.

```text
*Orden de Resolución Interno del Rúter de Laravel:*

1. ¿Es la Home? (Atendido por el HomeController principal)
2. ¿Es un Sección Dura Predefinida? (ej. /productos/, validado y atendido por ProductController)
3. ¿Páginas Corporativas Bases? (CorporateController)
4. Finalmente, comprueba en las Tablas de BBDD "CustomLanding", a ver si es algo de Marketing suelto.
5. Error 404 (Esa URL no lleva a nada programado ni dinámico en BBDD en este mercado/idioma).
```

---

Con estas características, la Base 4.0 de la plataforma corporativa TermoSalud alcanza grados de empresa de élite tecnológica, proveyendo al mismo tiempo escalabilidad multi-plataforma e inserción precisa para SEO y regulaciones legales médicas.
