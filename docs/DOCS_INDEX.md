# 📚 Índice de documentación para desarrolladores - Termosalud Corporate (Pre-Alfa)

¡Hola! Bienvenido al proyecto **Termosalud Corporate (Pre-Alfa)**. Esta documentación está escrita pensando en ti. Sabemos que entrar a un proyecto empresarial grande puede abrumar, así que hemos organizado todo de mayor a menor importancia para tu día a día.

---

## 🎯 1. Por dónde empezar hoy mismo

### 🌟 [README.md](./README.md)

**Tu campamento base. Léelo de principio a fin.**

Aquí te explicamos *qué* estamos construyendo (y *por qué* usamos Laravel + Vue + Inertia). Aprenderás la visión general del negocio, cómo se estructura el modelo multimercado (las URLs como `/es/es/`) y cómo levantar el proyecto en tu máquina por primera vez.

---

## 🏗️ 2. Entendiendo el código (para desarrolladores)

Una vez que tengas el proyecto corriendo y hayas leído el README, toca entender **cómo** programamos aquí.

### 📐 [ARCHITECTURE.md](./ARCHITECTURE.md)

**La joya de la corona: Arquitectura Hexagonal y DDD.**

Si vienes de hacer tutoriales clásicos de Laravel (donde todo va en la carpeta `app/`), este documento te volará la cabeza (para bien). Explicamos didácticamente por qué separamos el código del negocio (`src/`) del código del framework de PHP clásico. Aprenderás sobre Puertos y Adaptadores.

### 🚀 [FEATURES.md](./FEATURES.md)

**El mapa de las misiones principales.**

Te explicamos cómo funciona nuestro diseño "API-First" y las características estrella del proyecto: cómo se comportan dinámicamente los idiomas y los mercados, y cómo funciona nuestro motor unificado para Blog/Noticias.

---

## 📋 3. Guías de tareas específicas

Cuando tu Tech Lead te asigne tu primer ticket (tarea), consulta estos documentos:

### 🛠️ [DEVELOPMENT_GUIDE.md](./DEVELOPMENT_GUIDE.md)

**El paso a paso para crear nuevo código.**

Una guía práctica (tipo receta de cocina) sobre cómo crear un módulo completo desde cero en Termosalud, respetando las complejas reglas de Arquitectura Hexagonal que acabas de aprender.

### 📝 [FORMS_SYSTEM.md](./FORMS_SYSTEM.md)

**El sistema de captura de clientes (Nivel: Crítico).**

Si te toca programar algo relacionado con formularios, lee esto. Explica nuestra obsesión con la seguridad (6 capas anti-spam) y cómo mandamos los datos que nos entran por la web hacia nuestro CRM de Ventas en background sin que el usuario tenga que esperar cargando.

### 🔬 [FORMS_PATTERN_ANALYSIS.md](./FORMS_PATTERN_ANALYSIS.md)

**Por qué programamos los formularios así.**

Un análisis arquitectónico de por qué reutilizamos el código antiguo del CRM, y qué le tuvimos que adaptar para que sobreviviera en la salvaje Internet pública (Defensas Anti-Spam).

---

## 👔 4. Documentos de negocio (el contexto)

Como desarrollador Senior en potencia, no solo debes escribir código, debes entender **para qué** sirve a la empresa.

### 💼 [EXECUTIVE_SUMMARY.md](./EXECUTIVE_SUMMARY.md) & [EXECUTIVE_OVERVIEW.md](./EXECUTIVE_OVERVIEW.md)

**Lo que le importa a los jefes.**

Documentos breves, sin jerga técnica, que se enviaron a los directores para que aprobaran presupuesto. Te ayudarán a empatizar con los departamentos de Marketing, Calidad y Exportación, y entender por qué tu trabajo programando el multi-idioma les salva tantas semanas de trabajo manual al año.

---

## 🗂️ 5. La biblia técnica

### 📄 [termosalud_corporate_2026_master_spec.json](./termosalud_corporate_2026_master_spec.json)

**La Especificación Maestra.**

Este es nuestro documento de la verdad absoluta (SSOT - Single Source of Truth). Si tienes dudas sobre cómo se llama una tabla SQL, qué JSON devuelve un Endpoint del API, o si un mercado lleva la abreviatura `us` o `usa`, la respuesta está siempre aquí.

---

## 🚀 6. Despliegue y operaciones

### 🌐 [DEPLOYMENT.md](./DEPLOYMENT.md)

**Cómo llevamos el código a producción.**

Explica el flujo automatizado con GitHub Actions para compilar el frontend (junto a `node_modules`) y enviarlo al VPS de forma segura y eficiente.

---

## 📞 ¿Necesitas ayuda?

Nunca te quedes bloqueado más de 1 hora intentando resolver algo de arquitectura.
Si ya leíste el [DEVELOPMENT_GUIDE.md](./DEVELOPMENT_GUIDE.md) y sigues trabado escribiendo tu repositorio Eloquent, **levanta la mano y pide ayuda a tu Tech Lead / Senior o Compañero de Pair Programming.** ¡Estamos para ayudarte a subir de nivel!
