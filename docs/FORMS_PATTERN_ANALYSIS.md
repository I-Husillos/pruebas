# Análisis de patrones: de TermoCRM a Termosalud Corporate

> [!NOTE]
> **La Lección de Ingeniería: ¿Reescribir o Reusar?**
> A menudo la empresa ya tiene código que resuelve un problema. TermoCRM, nuestro software interno, ya sabía gestionar formularios JSON dinámicamente.
> La pregunta en arquitectura es: *"¿Podemos copiar y pegar esto a la Web Pública de Termosalud?"*
> La respuesta es: **Parcialmente sí (Patrón de Datos), Absolutamente no (Contexto de Seguridad).**

Este análisis te resume cómo extrajimos el músculo de una aplicación y lo vacunamos para sobrevivir en internet.

---

## 1. El músculo extraído (lo que funcionaba en TermoCRM)

Nos llevamos lo mejor de aquél CMS: el **Modelo Dinámico de Entidad**.

* **La BBDD Flexible:** Que Marketing guarde todos los `elements` técnicos de cómo es el formulario en puro `JSON` dentro de una columna de MySQL.
* **El EAV Inverso:** Guardar las repuestas (`FormSubmissionResponse`) como filas estilo valor-clave en vez de crear complejas columnas dinámicas.
* **Service Layer Aislado:** Copiamos 1:1 las clases Creadoras (`Creator`), Buscadoras (`Finder`) porque son puros ladrillos desconectados y sin estado (Reglas DDD puestas de manifiesto con éxito).

### Ventajas de copiar esto

* El equipo de devs ya se lo conoce de memoria.

* Ya está testeado empíricamente por sus 100 usuarios en oficina.
* ¡Tardamos semanas menos en programar algo idéntico!

---

## 2. Lo que NO pudimos copiar (por el contexto hostil)

TermoCRM vivía dentro de una intranet idílica. Sus usuarios tenían Usuario y Autenticación. Terminaba el formulario con éxito y ahí acababa el viaje.

**La Web de TermoSalud vive en un Océano de Tiburones:**

1. Tráfico Público (Bots, Crawlers Rusos, Scrappers maliciosos).
2. Tienen que enviar y salir al mundo exterior (a TermoCRM).
3. Existen reglas Multi-geopolíticas (Latam vs Europa).

### 🛠️ Las adaptaciones mayores (lo que tuvimos que construir encima)

1. **Destruir Lógica Polimórfica:** En el CRM podías atar un formulario a un Evento, o a un Curso escolar. En la Web, los formularios cuelgan sueltos al infinito. Simplificamos las tablas borrando columnas de `submittable_type`.

2. **Inyección Inmunológica (Anti-Spam SDK):** Programamos desde 0 un servicio `SpamProtection` que impone 6 escudos durísimos antes de molestar siquiera a la Base de Datos. Honeypots, bloqueadores de IPs agresivos `RateLimit`, reCaptcha asíncronos y prohibición de emails piratas `@10minmail.com`.

3. **Trazabilidad Forense:** De cada envío extrajimos forzosamente por controlador la IP original, si venía navegando en móvil, a qué mercado refería (`market_code`) y por dónde se coló al formulario (el `Referrer`), inyectándolo al JSON General.

4. **El Puente Asíncrono (`Queue Jobs`):** La joya de la corona. Como ahora debíamos enviar ese dato seguro a TermoCRM sin retrasar a la persona real mirando el portátil, empaquetamos el "POST" a la API oficial usando encriptación `OAuth2` dentro de Trabajos Secundarios de Background (`SendToCrmJob`).

---

## Diferencias rápidas y visuales

| Concepto Técnico | Allá (TermoCRM Interno) | Acá (Termosalud WEB Corporate) |
| --- | --- | --- |
| **Audiencia Diaria** | 100 Trabajadores logueados | X,XXX Visitantes Mundiales Anónimos |
| **Protección Bot** | Ninguna (Ambiente seguro) | Hexagonal (6 Filtros HTTP y JavaScript) |
| **Rutas (Destino)** | Mismo Servidor y misma BBDD | Servidor Externo vía HTTP API |
| **Lógica Polimórfica** | Sí (Asociado internamente) | No (Comportamiento Aislado por Idioma) |
| **Aviso al Administrador** | Popups visuales Dashboard | Jobs de Correo Automatizado |

*Al comprender estas decisiones de diseño, un desarrollador entiende por qué el patrón general se sostiene, pero el envoltorio defensivo diverge hacia un enfoque mucho más moderno y securizado en entornos abiertos.*
