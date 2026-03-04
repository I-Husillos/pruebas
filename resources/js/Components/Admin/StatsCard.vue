<template>
  <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
    <div class="flex items-center">
      <div v-if="icon" class="flex-shrink-0 p-3 rounded-lg bg-indigo-50 text-indigo-600">
        <component :is="icon" class="h-6 w-6" aria-hidden="true" />
      </div>
      <div :class="[icon ? 'ml-5' : '']" class="w-0 flex-1">
        <dl>
          <dt class="truncate text-sm font-medium text-gray-500 uppercase tracking-wider">{{ title }}</dt>
          <dd class="flex items-baseline">
            <div class="text-2xl font-bold text-gray-900">{{ value }}</div>
            <div v-if="change" class="ml-2 flex items-baseline text-sm font-semibold" :class="changeType === 'increase' ? 'text-green-600' : 'text-red-600'">
              <span v-if="changeType === 'increase'">↑</span>
              <span v-else>↓</span>
              {{ change }}
            </div>
          </dd>
        </dl>
      </div>
    </div>
    <div v-if="link" class="mt-4 border-t border-gray-50 pt-4">
      <Link :href="link" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
        Ver detalles <span aria-hidden="true">&rarr;</span>
      </Link>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  title: String,
  value: [String, Number],
  icon: [Object, Function],
  link: String,
  change: String,
  changeType: {
    type: String,
    default: 'increase'
  }
});
</script>
