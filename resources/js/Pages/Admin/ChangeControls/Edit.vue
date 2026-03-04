<template>
    <AdminLayout>
        <Breadcrumbs :items="[
            { label: 'Control de Cambios', route: route('admin.change-controls.index') },
            { label: 'Editar Solicitud' }
        ]" />

        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-bold leading-9 text-gray-900">Editar Solicitud</h1>
                <p class="mt-2 text-sm text-gray-700">Visualiza y edita los detalles de la solicitud #{{ changeControl.id }}</p>
            </div>
            <!-- Status Badge -->
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                 <span :class="getStatusBadgeClass(changeControl.status)" class="inline-flex rounded-full px-4 py-1 text-sm font-semibold leading-5">
                    {{ getStatusLabel(changeControl.status) }}
                </span>
            </div>
        </div>

        <div class="mt-8 shadow ring-1 ring-gray-900/5 sm:rounded-lg bg-white p-8 max-w-4xl">
            <form @submit.prevent="submit" class="space-y-6">
                
                <!-- Diff Viewer -->
                <div v-if="changeControl.payload" class="mb-8">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Revisión de Cambios</h3>
                    <DiffViewer 
                        :original="changeControl.changeable || {}" 
                        :proposed="changeControl.payload" 
                    />
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Título</label>
                    <div class="mt-2">
                        <input v-model="form.title" type="text" name="title" id="title" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required />
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Descripción</label>
                    <div class="mt-2">
                        <textarea v-model="form.description" id="description" name="description" rows="4" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required></textarea>
                    </div>
                </div>

                <!-- Reason -->
                <div>
                    <label for="reason" class="block text-sm font-medium leading-6 text-gray-900">Razón / Justificación</label>
                    <div class="mt-2">
                        <textarea v-model="form.reason" id="reason" name="reason" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                    </div>
                </div>

                <!-- Impact -->
                <div>
                    <label for="impact" class="block text-sm font-medium leading-6 text-gray-900">Impacto Esperado</label>
                    <div class="mt-2">
                        <textarea v-model="form.impact" id="impact" name="impact" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                    </div>
                </div>

                <!-- Metadata (Read-only) -->
                <div class="bg-gray-50 p-4 rounded-md border border-gray-200 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="block text-xs text-gray-500 font-medium uppercase">Solicitante</span>
                        <span class="block text-sm text-gray-900">{{ changeControl.requester?.name }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 font-medium uppercase">Fecha Creación</span>
                        <span class="block text-sm text-gray-900">{{ new Date(changeControl.created_at).toLocaleDateString() }}</span>
                    </div>
                    <div>
                         <span class="block text-xs text-gray-500 font-medium uppercase">Tipo Cambio</span>
                        <span class="block text-sm text-gray-900 font-mono">{{ changeControl.type }}</span>
                    </div>
                    <div v-if="changeControl.changeable_type">
                         <span class="block text-xs text-gray-500 font-medium uppercase">Recurso</span>
                        <span class="block text-sm text-gray-900 font-mono">{{ changeControl.changeable_type }} #{{ changeControl.changeable_id }}</span>
                    </div>
                    <div v-if="changeControl.approval_date">
                        <span class="block text-xs text-gray-500 font-medium uppercase">Fecha Aprobación</span>
                        <span class="block text-sm text-gray-900">{{ new Date(changeControl.approval_date).toLocaleDateString() }}</span>
                    </div>
                    <div v-if="changeControl.implementation_date">
                        <span class="block text-xs text-gray-500 font-medium uppercase">Fecha Implementación</span>
                        <span class="block text-sm text-gray-900">{{ new Date(changeControl.implementation_date).toLocaleDateString() }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row justify-between gap-4 pt-4 border-t border-gray-100">
                    <div class="flex gap-2">
                        <!-- Approve/Reject Actions -->
                        <button v-if="changeControl.status === 'pending'" type="button" @click="approveChange" class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
                            Aprobar y Aplicar
                        </button>
                        <button v-if="changeControl.status === 'pending'" type="button" @click="rejectChange" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                            Rechazar
                        </button>
                    </div>

                    <div class="flex gap-x-4">
                        <Link :href="route('admin.change-controls.index')" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancelar</Link>
                        <button type="submit" :disabled="form.processing" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-50">
                            {{ form.processing ? 'Guardando...' : 'Guardar Cambios' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Breadcrumbs from '@/Components/Admin/Breadcrumbs.vue';
import DiffViewer from '@/Components/Admin/DiffViewer.vue';
import { Link, useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    changeControl: Object,
});

const form = useForm({
    title: props.changeControl.title,
    description: props.changeControl.description,
    reason: props.changeControl.reason,
    impact: props.changeControl.impact,
    status: props.changeControl.status,
    approval_date: props.changeControl.approval_date,
    implementation_date: props.changeControl.implementation_date,
});

const submit = () => {
    form.put(route('admin.change-controls.update', props.changeControl.id));
};

const approveChange = () => {
    if (confirm('¿Estás seguro de APROBAR este cambio? Se aplicará inmediatamente a los datos en vivo.')) {
        router.post(route('admin.change-controls.approve', props.changeControl.id));
    }
};

const rejectChange = () => {
    const reason = prompt('Por favor ingrese la razón del rechazo:');
    if (reason !== null) {
        router.post(route('admin.change-controls.reject', props.changeControl.id), {
            reason: reason
        });
    }
};

const getStatusLabel = (status) => {
    const labels = {
        draft: 'Borrador',
        pending: 'Pendiente',
        approved: 'Aprobado',
        implemented: 'Implementado',
        rejected: 'Rechazado',
    };
    return labels[status] || status;
};

const getStatusBadgeClass = (status) => {
    const classes = {
        draft: 'bg-gray-100 text-gray-800',
        pending: 'bg-yellow-100 text-yellow-800',
        approved: 'bg-blue-100 text-blue-800',
        implemented: 'bg-green-100 text-green-800',
        rejected: 'bg-red-100 text-red-800',
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};
</script>
