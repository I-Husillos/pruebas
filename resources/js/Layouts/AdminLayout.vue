<template>
    <div class="min-h-screen bg-gray-100 flex">
        <!-- Sidebar -->
        <Sidebar />

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col ml-64 transition-all duration-300">
            <!-- Top Navigation -->
            <nav class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                           <!-- Left side of top bar (can be used for title or breadcrumbs if moved here) -->
                           <div class="flex-shrink-0 flex items-center">
                                <!-- Replaced Title with Sidebar Logo, so maybe just empty or Breadcrumbs placeholder -->
                           </div>
                           <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                                <!-- View Main Web Link -->
                                <a :href="route('home', { market: 'es', lang: 'es' })" target="_blank"
                                      class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                                    Ver web principal
                                </a>
                           </div>
                        </div>
                        <div class="hidden sm:ml-6 sm:flex sm:items-center">
                            <div class="ml-3 relative">
                                <div class="text-sm text-gray-700 font-medium">
                                    {{ $page.props.auth?.user?.name || 'Admin' }}
                                </div>
                            </div>
                            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    class="block rounded-lg bg-indigo-600 px-4 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors"
                                >
                                    Cerrar sesión
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="py-10 flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <!-- Flash Messages -->
                    <!-- Flash Messages -->
                    <div v-for="(msg, type) in $page.props.flash" :key="type">
                        <div v-if="msg" :class="{
                            'bg-green-50 border-green-200 text-green-800': type === 'success',
                            'bg-red-50 border-red-200 text-red-800': type === 'error',
                            'bg-yellow-50 border-yellow-200 text-yellow-800': type === 'warning',
                            'bg-blue-50 border-blue-200 text-blue-800': type === 'info'
                        }" class="mb-4 rounded-md p-4 shadow-sm border flex">
                            <div class="ml-3">
                                <p class="text-sm font-medium">
                                    {{ msg }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Global Error Alert -->
                    <div v-if="Object.keys($page.props.errors).length > 0" class="mb-4 rounded-md bg-red-50 p-4 shadow-sm border border-red-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Hay errores en el formulario</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul role="list" class="list-disc pl-5 space-y-1">
                                        <li v-for="(error, key) in $page.props.errors" :key="key">{{ error }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import Sidebar from '@/Components/Admin/Sidebar.vue';
import { Link } from '@inertiajs/vue3';
</script>
