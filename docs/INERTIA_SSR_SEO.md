# SEO y construcción frontend SSR - Guía para desarrolladores

**Framework Frontend:** Inertia.js (Basado sobre Vue 3 y enlazado a Laravel 11)  
**Objetivo Core:** Hacer una Web interactiva y moderna sin penalizar nunca el SEO en Buscadores.  

---

## 🎯 ¿Por qué Inertia.js y SSR?

> [!IMPORTANT]
> **El problema histórico del SEO en JavaScript**
> Cuando hacemos una Single Page Application (SPA) bonita usando Vue.js "puro", enviamos al navegador un archivo `.html` que está completamente en blanco, acompañado de archivos temporales pesados de JavaScript. El navegador carga el javascript que, milisegundos más tarde, hace peticiones al API para descargar texto o fotos y entonces pinta la pantalla para el humano.
> **¿De cara al usuario? Gran experiencia.**
> **¿De cara a Google (Googlebot)? Horroroso.** Muchos bots ven páginas en blanco porque no esperan o no saben ejecutar JavaScript complejo.

### La solución clásica: Nuxt.js / Next.js

Para solucionar esto, normalmente el mercado usa Nuxt (si es Vue) o Next (si es React). Estos frameworks levantan *otro servidor propio* que pre-renderizan internamente el HTML, actuando de middleware, y luego se conectan de nuevo al back y envían código HTML real a Google.
*Problema*: Nos condena a mantener dos infraestructuras separadas, manejar la autenticación el doble de veces y tener problemas cross-origin.

### Nuestra solución: "el pegamento" => Inertia.js + SSR

Inertia.js permite programar componentes visuales modulares en Vue.js puro, usando los mismos Controladores PHP que aprendiste sin tener que montar APIS REST en medio. En lugar de devolver Vistas Blade con `return view()`, el controlador dice `return Inertia::render()`.

Toda la magia web de "No Recargar la pantalla" la hace en background esta tecnología.

A esto sumamos **Server-Side Rendering (SSR)** para el SEO. Cuando montamos la web corporativa a nivel productivo y habilitamos SSR, cuando un robot de Google le solicita un listado de productos a nuestra web, Laravel e Inertia se unen "por detrás", y usando un microproceso de NodeJS local compilan e insuflan ya de base el puro e inmenso HTML resultante directamente por el tubo de red como si fuera la forma mágica de una web de antaño, lo que resulta en un ranking asombroso en buscadores.

```text
┌─────────────────────────────────────────────────────────────┐
│  Arquitectura de Negocio (El Core, src/)                    │
└──────────────────┬──────────────────────────────────────────┘
                   │
                   ↓
┌──────────────────────────────────────────────────────────────┐
│  LARAVEL 11 (Capa de Controladores)                          │
│  La puerta de entrada valida las peticiones HTTP             │
└──────────────────┬──────────────────────────────────────────┘
                   │
                   ↓ "Inertia hace de pegamento brillante"
                   │
┌──────────────────────────────────────────────────────────────┐
│  VUE 3 + TYPESCRIPT (El Frontend)                            │
│  Componentes UI muy modernos (Animaciones, reactividad)      │
│  Y en Producción, renderizados a HTML del tirón para SEO     │
└──────────────────────────────────────────────────────────────┘
```

---

## 🔍 Implementación optimizada para SEO

### 1. El controlador (pasando metadatos)

Fíjate en cómo funciona un controlador final que une arquitectura, web y SEO. Todo se prepara primero usando la lógica PHP, antes de que el Vue se empiece si quiera a montar o enviar al humano:

```php
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Termosalud\Catalog\Application\Find\ProductFinder;
use Termosalud\SEO\Application\SeoMetaGenerator;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function __construct(
        private ProductFinder $productFinder,
        private SeoMetaGenerator $seoGenerator
    ) {}

    public function show(string $market, string $lang, string $slug): Response
    {
        // 1. Caso de Uso Core: Buscar el producto base según mercado
        $product = $this->productFinder->findBySlug($slug, $market, $lang);
        
        // 2. Optimizador de SEO: Generar las Title tags, OpenGraphs y Canonical dinámicamente
        $seo = $this->seoGenerator->forProduct($product, $market, $lang);
        
        // 3. Devolvemos el array masivo de información formateada hacia el Componente Vue 'Show.vue'
        return Inertia::render('Products/Show', [
            'product' => $product->toArray(),
            'seo' => $seo,
            'relatedProducts' => $product->relatedProducts(4)->toArray(),
            'market' => $market,
            'language' => $lang,
        ]);
    }
}
```

### 2. El componente en Vue 3 y `<Head>`

Una vez que esos datos llegan a Vue, el componente oficial gratuito de `<Head>` de Inertia.js permite que nosotros inyectemos en puro código del lado-servidor asíncrono las etiquetas exactas que necesitamos antes que los rastreadores lo atrapen.

```vue
<!-- resources/js/Pages/Products/Show.vue -->
<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import ProductLayout from '@/Layouts/ProductLayout.vue'

// Recibimos exactamente lo que el Controlador envió arriba
const props = defineProps({
  product: Object,
  seo: Object,
  market: String,
  language: String
})
</script>

<template>
  <!-- 🔑 Cuando un Robot visite esta URL, leerá en tiempo real todas nuestras meta-tags construidas. -->
  <Head>
    <title>{{ seo.title }}</title>
    <meta name="description" :content="seo.description">
    
    <!-- URL Oficia Canónica -->
    <link rel="canonical" :href="seo.canonical">
    
    <!-- La joya del SEO: Etiquetados Internacionales Automáticos multiidioma  -->
    <link 
      v-for="(url, hreflang) in seo.hreflang" 
      :key="hreflang"
      rel="alternate" 
      :hreflang="hreflang" 
      :href="url"
    >
    
    <!-- Open Graph (Previas si pasamos la web por Whatsapp o Facebook) -->
    <meta property="og:title" :content="seo.ogTitle">
    <meta property="og:image" :content="seo.ogImage">
  </Head>
  
  <ProductLayout>
     <!-- Renderizamos un bonito portal visualmente -->
     <h1>{{ product.name }}</h1>
     <img :src="product.thumbnail" loading="eager" />
  </ProductLayout>
</template>
```

---

## ⚡ Core Web Vitals (puntuando alto en Google Audit)

Para no saturar a los desarrolladores, debes saber que Google premia a las páginas móviles rápidas. Con este framework lo suplimos atacando 3 factores "Vitals":

### 1. LCP (Pintado del elemento más grande)

Si tenemos una foto gigante (Hero Image), no dejamos que cargue por inercia o al final del JS (por defecto). Forzamos explícitamente en el componente `loading="eager"` o `fetchpriority="high"`. Es obligatorio aplicarlo al menos en la primera imagen base visible en monitores sin hacer scroll (las de abajo mejor que usen carga diferida "Lazy").

### 2. CLS (Cambios imprevistos visuales)

Hacer que Google entre, lea, y que un segundo después un anuncio o texto empuje todo el párrafo 2 centímetros abajo nos penaliza muchísimo ("Cumulative Layout Shift"). Utilizamos "Skeleton Loaders". Mientras tu web reactiva busca una base de datos lenta, pintaremos una caja gris que obligará y reservará las mismas dimensiones finales para que luego nada "baile". Usamos en fotografías `aspect-square object-cover` de Tailwind.

### 3. Precarga por interacciones falsas

Inertia realiza `Prefetch`. Cada vez que tú leas una noticia o navegues el Frontend normal, si pasas el ratón (`hover`) por un link al producto *Zionic*, por detrás el código de este sistema está precargando el Componente Zionic de Vue.JS a tu móvil. Así, cuando tú finalmente clicas un segundo después medio distraído al decidir, la página que ves se siente **instantánea** y de carga `0.00s`. Así conseguimos aplicaciones empresariales de muy altísima calidad tecnológica y rapidez global.
