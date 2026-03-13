# Sistema de formularios - Termosalud Corporate

## Guía educativa: del CRM al público abierto

**Estado:** Diseño Final

> [!IMPORTANT]
> **Por qué esto te importa:**
> Este proyecto se basa fuertemente en código creado anteriormente para el CRM interno de la empresa (TermoCRM). Vamos a heredar su forma de lidiar con Formularios dinámicos, pero con un giro gigantesco: **TermoCRM era seguro porque solo los empleados entraban; la Web de Termosalud está expuesta a Google y a piratas informáticos**.

---

## 🎯 ¿Qué debemos conseguir?

El objetivo es tener un sistema donde Marketing pueda arrastrar y soltar campos de texto (ej. "Añadir campo Teléfono") creando formularios infinitos, y que un usuario desde Colombia rellene ese `Formulario de Contacto`, superando protecciones invisibles, y cayendo suavemente como una Oportunidad de Venta (Lead) en nuestro CRM interno de forma automática.

---

## 📐 El patrón TermoCRM (la arquitectura EAV adaptada)

Reutilizaremos la técnica "Entity-Attribute-Value" (EAV) que TermoCRM usaba con éxito para crear formularios dinámicos sin tener que añadir columnas SQL cada vez que Marketing se inventa una pregunta nueva.

### 1. La "Plantilla" (`forms`)

Contiene la configuración del formulario. Si hoy inventan uno que se llame `Encuesta Satisfacción` con 20 preguntas `JSON`, la estructura del esquema se guarda aquí.

### 2. El "Envío" (`form_submissions`)

El paraguas general del intento del usuario.
**Agregados críticos para la Web Pública vs TermoCRM:**

- `market_code` & `language`: Porque no es lo mismo que el formulario lo envíen desde Francia que desde México, para su posterior gestión del Lead.
- `spam_score` & `spam_flags`: Registros de Defensa Automática.
- `status`: Si está pendiente, validado, marcado como spam, o finalmente `sent_to_crm`.

### 3. Las "Respuestas" (`form_submission_responses`)

En lugar de forzar un campo de texto en una columna plana SQL (que explotaría si nos piden 40 campos), usamos el patrón EAV:
Se guarda 1 registro ("fila") en BBDD por cada caja de texto que el usuario validó. (Si llenó Nombre, Email y Móvil, se guardan 3 filas apuntando al Envío general).

---

## 🛡️ La fortaleza: sistema Anti-Spam (6 capas)

Aquí reside la principal diferencia de código contra el CRM interno. Así es cómo tú, como programador de la web, blindarás el sistema usando el Servicio `SpamProtection`:

1. **Honeypot (Caja de miel):** Pintamos un campo HTML invisible oculto por CSS. Los humanos no lo ven, las arañas robot rellena-formularios lo verán obligatoriamente visible en su lógica pura y lo rellenarán. Si nuestro backend recibe que el campo `website` tiene texto: 100% es un Robot, y expulsamos la petición.
2. **Time Throttle:** Evaluamos el JS Date of Load vs Date of POST. Si rellenaste 6 campos en menos de 3 Segundos: O eres The Flash, o eres un Bot. Expulsado.
3. **Google reCAPTCHA v3:** El de verdad (Invisible). Analiza micro-movimientos de ratón. Si el Score HTTP de Google nos dice que la señal es `< 0.5`: Robot. Expulsado.
4. **Rate Limit de IP:** Si tu enrutador manda más de `5 formularios / Hora`: Expulsado temporalmente.
5. **Rate Limit de Email:** Si el mail `testeos_locos@gmail.com` entra `3 veces por hora`: Bloqueado.
6. **Blocklist Desechable:** Rechazamos en `Regex` dominios tóxicos estilo `@mailinator.com` para evitar que saturen nuestro CRM Comercial de porquería de QA Testing pública.

---

## 🔄 El viaje del Lead (Jobs en background)

> [!NOTE]
> **Performance Tip:**
> Nunca dejes a un visitante en la web esperando ("El girasol dando vueltas de carga") mientras tú te conectas por cURL a APIs lentas para comprobar cosas que no dependen de la validación.

Cuando el usuario da al botón y pasa las reglas locales anti-spam, le decimos *"Gracias, se envió correctamente!"*, y guardamos en BBDD local.

**DÉCIMAS DE SEGUNDO DESPUÉS (Background / Redis Worker queue):**

1. Nuestro sistema lanza silenciamente la clase Job `SendToCrmJob`.
2. Esa clase "Disfrázase" usando Tokens Bearer Seguros (`OAuth2`) y toca la puerta de `MiTermosalud CRM`.
3. Le inyecta toda la Data (El Lead, el Referrer, el Idioma).
4. El CRM dice "Recibido", le asigna un comercial humano, y nosotros anotamos la ID exitosa generada bajo nuestro `form_submissions.status = sent_to_crm`.

*(Si el CRM estuviera caído temporalmente por reinicios, la lógica `Retries = 3 / Backoff = 60s` re-intentará pasados unos minutos sin perder jamás la ficha del cliente).*

---

## 🔁 Qué viene de TermoCRM y qué es nuevo

Este sistema hereda el patrón de formularios dinámicos del CRM interno (TermoCRM), pero con adaptaciones críticas para un entorno público. La regla es: **reutilizamos la estructura de datos, rehacemos la seguridad**.

| Concepto | TermoCRM (interno) | Termosalud Web (público) |
|---|---|---|
| **Audiencia** | ~100 empleados autenticados | Miles de visitantes anónimos |
| **Protección bot** | Ninguna (entorno seguro) | 6 filtros anti-spam |
| **Destino del envío** | Misma BD local | API externa vía OAuth2 |
| **Polimorfismo** | Formulario ligado a Eventos, Cursos… | Formularios independientes (sin `submittable_type`) |
| **Trazabilidad** | Básica | IP, user-agent, `market_code`, referrer registrados en cada envío |
| **Notificaciones** | Popups en dashboard | Jobs de cola con reintentos automáticos |

Lo que se reutilizó: el modelo flexible EAV (plantilla JSON + respuestas por filas), las clases `Creator` y `Finder` del dominio, y la lógica de `FormSubmission`.

Lo que se construyó desde cero: el servicio `SpamProtection`, el job `SendToCrmJob` con autenticación OAuth2, y el rastreo geopolítico del envío.
