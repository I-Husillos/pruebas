<template>
    <div v-if="data.total > data.per_page" class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
        <!-- Mobile view (simple) -->
        <div class="flex flex-1 justify-between sm:hidden">
            <Link :href="data.prev_page_url || '#'" 
                  :class="[!data.prev_page_url ? 'pointer-events-none opacity-50' : '', 'relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50']">
                Anterior
            </Link>
            <Link :href="data.next_page_url || '#'" 
                  :class="[!data.next_page_url ? 'pointer-events-none opacity-50' : '', 'relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50']">
                Siguiente
            </Link>
        </div>

        <!-- Desktop view -->
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Mostrando
                    <span class="font-medium">{{ data.from }}</span>
                    a
                    <span class="font-medium">{{ data.to }}</span>
                    de
                    <span class="font-medium">{{ data.total }}</span>
                    resultados
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <!-- First Page -->
                    <Link :href="data.first_page_url"
                          class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                        <span class="sr-only">Primera</span>
                        <ChevronDoubleLeftIcon class="h-5 w-5" aria-hidden="true" />
                    </Link>

                    <!-- Previous Page -->
                    <Link :href="data.prev_page_url || '#'"
                          :class="[!data.prev_page_url ? 'pointer-events-none opacity-50' : '', 'relative inline-flex items-center px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0']">
                        <span class="sr-only">Anterior</span>
                        <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
                    </Link>

                    <!-- Page Numbers (Custom Logic) -->
                    <template v-for="(page, index) in pageNumbers" :key="index">
                        <span v-if="page === '...'"
                              class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">
                            ...
                        </span>
                        <Link v-else
                              :href="data.path + '?page=' + page"
                              :class="[page === data.current_page ? 'z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600' : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0', 'relative inline-flex items-center px-4 py-2 text-sm font-semibold focus:z-20']">
                            {{ page }}
                        </Link>
                    </template>

                    <!-- Next Page -->
                    <Link :href="data.next_page_url || '#'"
                          :class="[!data.next_page_url ? 'pointer-events-none opacity-50' : '', 'relative inline-flex items-center px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0']">
                        <span class="sr-only">Siguiente</span>
                        <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
                    </Link>

                     <!-- Last Page -->
                     <Link :href="data.last_page_url"
                          class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                        <span class="sr-only">Última</span>
                        <ChevronDoubleRightIcon class="h-5 w-5" aria-hidden="true" />
                    </Link>
                </nav>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { ChevronLeftIcon, ChevronRightIcon, ChevronDoubleLeftIcon, ChevronDoubleRightIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    data: Object,
});

// Custom Logic: 3 first, 3 last, ellipsis, and current window if needed?
// The user request said: "saca los 3 primeros y los 3 últimos dejando ir a primera página a última anterior y s iguiente con flechas"
// Interpreting strictly: Show [1, 2, 3] ... [Last-2, Last-1, Last].
// But checking "si hay muchos números pon puntos suspensivos".
// And "dejando ir a primera página a última anterior y siguiente con flechas" -> These are the arrows.
// So the number list should strictly be first 3 and last 3? What about current page if it's in the middle?
// Usually UX requires showing the current page. I will assume if current page is outside these ranges, I should show it or expand the range.
// Let's implement: [1, 2, 3] ... [Last-2, Last-1, Last]. If current page is 10 in a 100 page list, user can't see 10? That's bad UX.
// "saca los 3 primeros y los 3 últimos" -> output the 3 first and 3 last.
// I will stick to this literal interpretation but add the current page in the middle if it's not covered, for usability.
// Actually, I'll follow the "3 first and 3 last" literal instruction combined with "if many numbers".
const pageNumbers = computed(() => {
    const total = props.data.last_page;
    const current = props.data.current_page;
    const pages = [];

    if (total <= 8) {
        // Show all if few
        for (let i = 1; i <= total; i++) pages.push(i);
    } else {
        // First 3
        pages.push(1, 2, 3);

        // Check if we need middle section (User didn't explicitly ask for middle, but it's weird without it if current is 50)
        // If I strict follow "3 first and 3 last", user is lost if they are on page 50.
        // I will interpret "saca los 3 primeros y los 3 últimos" as the fixed anchor points.
        // I will add the current page context if it's far from start/end.
        
        // Let's ensure there is a gap
        // If current is > 4 (since 3 is shown) and current < total - 3 (since last 3 shown)
        
        let addedMiddle = false;
        if (current > 4 && current < total - 3) {
             pages.push('...');
             pages.push(current - 1, current, current + 1); // Context around current
             addedMiddle = true;
        } else {
             pages.push('...');
        }
        
        // Remove duplicate ellipsis if my logic added it incorrectly or double.
        // Actually, cleaner logic:
        
        // Set: [1, 2, 3]
        // Set: [Last-2, Last-1, Last]
        // If current is not in either set, add it.
        
        // Refined Logic based on request:
        // Always 1, 2, 3.
        // Always ...
        // Always Last-2, Last-1, Last.
        // Plus Arrows.
    }

    // Resetting to the STRICT user request as best interpreted:
    // "saca los 3 primeros y los 3 últimos" (show the 3 first and 3 last)
    // "si hay muchos números pon puntos suspensivos" (if many numbers, put ellipsis)
    
    if (total > 8) {
        // Re-calculate strictly
        const securePages = new Set([1, 2, 3, total-2, total-1, total]);
        // To avoid bad UX, I MUST include current page if execution implies it. But let's start with strict.
        // If I only show 1,2,3 ... 98,99,100 and I am on 50, it looks broken.
        // I will include current page if it's unsupported.
        if (current > 3 && current < total - 2) {
            securePages.add(current);
        }
        
        const sorted = Array.from(securePages).sort((a, b) => a - b);
        const result = [];
        let prev = 0;
        for (const p of sorted) {
            if (prev > 0 && p - prev > 1) {
                result.push('...');
            }
            result.push(p);
            prev = p;
        }
        return result;
    }

    return pages;
});
</script>
