<template>
    <FrontendLayout>
        <h1>{{ category.name }}</h1>
        <div>
            <!-- Filtros (ejemplo: precio) -->
            <form @submit.prevent="applyFilters">
                <input v-model="filters.precioMin" type="number" placeholder="Precio mínimo" />
                <input v-model="filters.precioMax" type="number" placeholder="Precio máximo" />
                <button type="submit">Filtrar</button>
            </form>
        </div>
        <div>
            <ul>
                <li v-for="article in articles" :key="article.id">
                    {{ article.title }}
                </li>
            </ul>
        </div>
        <div>
            <button :disabled="pagination.page === 1" @click="goToPage(pagination.page - 1)">Anterior</button>
            <span>Página {{ pagination.page }} de {{ pagination.last_page }}</span>
            <button :disabled="pagination.page === pagination.last_page"
                @click="goToPage(pagination.page + 1)">Siguiente</button>
        </div>
    </FrontendLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';

const props = defineProps({
    category: Object,
    articles: Array,
    pagination: Object,
    market: String,
    lang: String,
});

const filters = ref({
    precioMin: '',
    precioMax: '',
});

function applyFilters() {
    let extra = [];
    if (filters.value.precioMin && filters.value.precioMax) {
        extra.push(`precio-${filters.value.precioMin}-${filters.value.precioMax}`);
    }
    // Puedes añadir más filtros aquí
    router.get(route('front', {
        market: props.market,
        lang: props.lang,
        slug: props.category.slug,
        extra: extra.join('_'),
    }));
}

function goToPage(page) {
    let extra = [];
    // Mantén los filtros actuales
    if (filters.value.precioMin && filters.value.precioMax) {
        extra.push(`precio-${filters.value.precioMin}-${filters.value.precioMax}`);
    }
    extra.push(`pagina-${page}`);
    router.get(route('front', {
        market: props.market,
        lang: props.lang,
        slug: props.category.slug,
        extra: extra.join('_'),
    }));
}
</script>