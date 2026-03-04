# 🌐 Nueva web corporativa Termosalud - Resumen arquitectónico ejecutivo

> [!NOTE]
> **Nota: El Overview**
> Este documento funciona como una infografía o "Cheat Sheet" muy veloz para cualquier técnico o manager que necesita saber a mil kilómetros de altura de qué va Termosalud sin leer documentación profunda. Te ayuda a entender dónde encajan las diferentes piezas del rompecabezas antes de mirar el código.

**Estado:** ✅ Proyecto en Desarrollo - Arquitectura Base Definida

---

## 🎯 Misión del software

Desarrollar una infraestructura centralizada, **Multi-Mercado y Multi-Idioma**, gobernada por reglas médicas, que no dependa orgánicamente de programadores para su escalabilidad futura.

---

## 📊 Arquitectura de vuelo de pájaro

Esta es la forma en la que los datos y visitantes fluyen por nuestras arterias digitales.

```text
┌─────────────────────────────────────────────────────────────────┐
│                      USUARIOS GLOBALES                           │
│  Llegan desde Google EE.UU., México, España o un Partner API    │
└──────────────────┬──────────────────────────────────────────────┘
                   │
         ┌─────────▼─────────┐
         │ Router MultiPaís  │  <-- ⚙️ Aquí el SEO (hreflang)
         │ /es/es/productos/ │      se vuelve magia negra y posiciona.
         │ /mx/es/productos/ │
         └─────────┬─────────┘
                   │
┌──────────────────▼───────────────────────────────────────────────┐
│              ECOSISTEMA TERMOSALUD (Capa Aplicación)             │
│   ┌──────────┐  ┌──────────┐  ┌───────────┐  ┌─────────────┐   │
│   │ Catálogo │  │ Landings │  │ Motor CMS │  │ Formularios │   │
│   │ Médico   │  │ Promos   │  │ (Noticias)│  │ & Leads CRM │   │
│   └──────────┘  └──────────┘  └───────────┘  └──────┬──────┘   │
└─────────────────────────────────────────────────────┼──────────┘
                                                      │
                       ┌──────────────────────────────▼──────┐
                       │  Limpieza de Leads (Anti-Spam)      │ <-- Escudos: Bots,
                       └───────────────┬─────────────────────┘     Mailinator, DDoS
                                       │
                       ┌───────────────▼─────────────────────┐
                       │   Base de Datos CRM Corporativo     │ <-- Central de Ventas
                       └─────────────────────────────────────┘
```

---

## 🌍 Cómo modelamos el mundo médico (multimercado)

No es lo mismo vender radiofrecuencia en tu país que en otro continente.

Imagina este modelo de base de Datos con el `Zionic Pro Max`:

1. **Info General**: Cargas sus espectaculares fotos, logo y datos técnicos puros.
2. **Aplicación Legal (El "Override")**:
    - **En `ESPAÑA (EU_MDR)`**: Renderiza el texto "*Tratamientos médicos no invasivos clase IIa*".
    - **En `ESTADOS UNIDOS (FDA)`**: Renderiza "*Non-invasive body contouring 510(k)*".
    - **En `MÉXICO (LATAM)`**: Se muestra como "*Tratamientos estéticos corporales*" o simplemente no aparece por falta de registro sanitario.

> [!TIP]
> Por eso usamos un patrón API-First (Reglas estrictas) acoplado a Vue SSR. Si esto formara parte del código "duro" del Template HTML tradicional como hace 10 años, controlar esta división legal sería imposible.

---

## 📦 Motor unificado (evitando duplicación)

Todos los artículos de lectura (ya sean del Blog oficial educativo, de las Notas de Prensa puras corporativas o de Noticias de congresos) residen en el **mismo** esqueleto de Base de Datos.
Diferenciándolos únicamente por un enumerador `type`, salvamos tener tres ramas gigantescas de código, acelerando el desarrollo (Principio *Don't Repeat Yourself* puro).

---

## 📋 Integración vital: el lead viajero

Nuestra web de 2026 no manda "Emails sueltos" al contacto de la web esperando ser respondidos. Sigue flujos corporativos pesados:

1. **Cliente potencial** solicita Demo Zionic.
2. Servidor filtra IPs, emails quemados, y analiza que sea un humano vía reCaptcha Invisible.
3. Se enlaza el "Lead Validado" por OAuth2 de forma blindada al servidor comercial MiTermosalud (El Sistema CRM interno).
4. El CRM rastrea en qué URL (Ej: `/mx/es/`) estaba parado el usuario y asigna la pre-venta directamente al agente Mexicano correcto de la corporación. Total Automatización en **10 Segundos**.

---

## 🔧 Snapshot del stack (para programadores)

**Si metes mano al código, verás esto:**

- **Backend:** PHP 8+ y Laravel 11. Organizado *estrictamente* en Carpetas `Domain`, `Application` e `Infrastructure` de Arquitectura Hexagonal.
- **Frontend interactivo SEO:** Vue.js 3, vitaminado por Inertia.js y Node Server-Side Rendering (La combinación que hace que tu JavaScript loco sea legible por Google).
- **Estilos:** TailwindCSS v4 de utilidad. Diseñamos con clases al aire, compilando ultrarrápido con Vite.
- **Seguridad:** Laravel Passport manda y gobierna sobre todo API Call al sistema.

---
*En resumen: Construirá tu código una App Reactiva Moderna a nivel humano, hiperveloz para Google (Pura Web Semántica) y con un fuerte núcleo empresarial (Hexagonal/DDD) que blinda el catálogo legal de cambios fatales.*
