# 📊 Nueva web corporativa global Termosalud - Resumen ejecutivo

> [!NOTE]
> **Nota: El porqué de este documento**
> A diferencia del código o la arquitectura de carpetas que solemos ver, cuando desarrollamos software para una gran empresa como Termosalud, todo ese código (DDD, Hexagonal, Vue, APIs) sirve a un único propósito: **Cumplir objetivos de negocio medibles**.
> Este documento (Executive Summary) es lo que leería un Director (Gerencia, Exportación, Marketing) para autorizar presupuesto. Entender lo que ellos valoran te convertirá en un programador Senior (no piques código por picar, pica código que ahorre costes o genere beneficios).

**Nivel de Audiencia:** Dirección General, Dirección de Exportación, Jefatura de Producto, Dirección de Marketing
---

## 🎯 El problema y nuestra solución (1 minuto)

### ¿El problema histórico?

La web actual de Termosalud, como cualquier CMS base, no está preparada para la agresiva expansión internacional. Cada vez que Gerencia quiere abrir negocio en un país (Ej. EE.UU. o México), TIene que pedirle a los programadores clonar webs, traducir a mano, y luchar contra regulaciones médicas distintas. Un esfuerzo manual hercúleo y caro.

### ¿Nuestra solución (propuesta de valor)?

Una plataforma web única, **multi-mercado y multi-idioma** real y centralizada.

- ✅ **Mercados sin Programar:** Marketing puede añadir "Francia" en el Backoffice, y automáticamente existe `/fr/fr/` con sitemaps SEO integrados.
- ✅ **Regulación Dinámica:** Zionic no se vende bajo las mismas promesas médicas en Europa (EU_MDR) que ante la estricta **FDA** Americana. Con nuestro sistema, según la URL del país visitado, la base de datos muestra las especificaciones legales filtradas. ¡Protegemos legalmente a la empresa mientras ahorramos trabajo!.
- ✅ **Gestión Central de Leads:** Adiós a perder clientes. El formulario web manda el contacto *Instantáneamente* en 5 segundos al CRM de la empresa para que Ventas asigne un asesor.

---

## 💼 Beneficios inmediatos por departamento

### 📈 Para dirección de exportación
>
> **Tip:** Al equipo de exportación no le importa Vue3 o PHP. Le importa llegar rápido a nuevos territorios.

- **El cambio:** Pasar de tardar *3 meses* en levantar una web alemana a tardar *1 día* dándole a un par de botones en el Admin.

### 🎨 Para Marketing
>
> **Tip:** Marketing necesita autonomía absoluta sin depender del equipo técnico cada día.

- **El cambio:** Dejan de rogar a programación por *Landings exclusivas* para Black-Friday. Nuestro sistema de diseño por "Bloques" de Vue inyectables les permite construir páginas estacionales, capturar nuevos leads medibles, y programarlas para auto-desactivarse al caducar.

### 🏭 Para jefatura de producto y calidad
>
> **Tip:** En tecnologías médicas, un error de texto vendiendo una máquina en un país no autorizado puede resultar en multas millonarias.

- **El cambio:** El ciclo de vida de un texto de producto pasa antes por un *Workflow de Aprobación*. Marketing propone, pero Calidad (Regulatory QA) aprueba la publicación del texto regulatorio exclusivo a ese mercado. Dejamos un log (historial) auditable para cada cambio médico.

---

## 🌍 ¿Cómo funciona por debajo? (simplicidad aparente)

### 1️⃣ Internacionalización dinámica

¿Queremos ir a Francia? `(Mercados > Nuevo Mercado: Nombre: Francia, Código: fr, Idioma: Francés)`.
*La Magia detrás:* Laravel genera todas las rutas localizadas bajo el paraguas normativo `EU_MDR`. Google lee el código `hreflang` y posiciona esa url (`/fr/fr/` y `/fr/en/`) a la primera en búsquedas locales parisinas.

### 2️⃣ Contenidos unificados ("DRY" para redactores)

Nuestra lógica "Compartida" dicta que si configuramos "Historia de Termosalud", un editor la escribe *una vez en Español*, y el sistema la reparte implícita e inteligentemente a todo mercado hispanohablante de base (España, Latam, USA Hispano).

### 3️⃣ Integración robusta para ventas (flujo de Lead)

```text
Cualquier Formulario Web (SPA Vue.js)
  ↓
Señal POST a la API (/api/v1/public/forms)
  ↓
Procesado Anti-Spam (6 filtros + reCAPTCHA)
  ↓
API interna conecta via OAuth2 con servidor "MiTermosalud CRM"
  ↓
En menos de 10s: Comercial de España asignado y notificado.
```

---

## 📊 Retorno de inversión (¿por qué vale la pena pagar por esto?)

Si bien como constructores de Software esta tecnología avanzada puede sonar cara, el Retorno de Inversión (ROI) para la directiva será absoluto en su primer año natural de vida:

| Tarea Clásica vs Nuestra Arquitectura | Ahorro Esperado |
| :------- | :-------- |
| Abrir mercado web internacional | **Reducción del 95% del tiempo (Milisegundos vs Meses)** |
| Landings promocionales express | **Reducción a horas (Autonomía de Marketing absoluta)** |
| Modificación Legal Internacional | **Un editor actualiza el mundo. Auditoría en tiempo real.** |
| Traducciones globales | **Una traducción impacta 15 dominios si es el mismo idioma.** |

Con esta presentación al C-Level (Directivos), la arquitectura compleja e infraestructural que programaremos queda justificada e ilusiona plenamente a las cabezas de negocio de la corporación.
