# 📚 Índice de documentación para desarrolladores - Termosalud Corporate

Bienvenido al proyecto **Termosalud Corporate**. Esta documentación está organizada de mayor a menor prioridad para acelerar el onboarding y el trabajo diario.

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

### 🗂️ [CONTENT_SYSTEM.md](./CONTENT_SYSTEM.md)

**El sistema de contenidos multi-mercado y multi-idioma (Nivel: Crítico).**

Si tocas páginas, productos, artículos o tratamientos, **lee esto antes de escribir una sola línea**. Explica en detalle el patrón de Tablas de Localización (la forma canónica de almacenar contenido diferenciado por mercado **e** idioma), el formato JSON que genera el BlockEditor para el campo `content`, y el estado de implementación de cada módulo. Incluye el esquema exacto de base de datos, ejemplos de payload de API y la estructura completa de todos los tipos de bloque disponibles.

### 🔌 [API.md](./API.md)

**Guía rápida de autenticación y endpoints activos.**

Resumen práctico de OAuth2/Passport, convención de respuestas y rutas disponibles. Para contratos exactos por endpoint, apunta a Swagger en `/api/documentation`.

---

## 👔 4. Documentos de negocio (el contexto)

Como desarrollador Senior en potencia, no solo debes escribir código, debes entender **para qué** sirve a la empresa.

### 💼 [EXECUTIVE_SUMMARY.md](./EXECUTIVE_SUMMARY.md)

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
