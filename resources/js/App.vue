<template>
    <router-view></router-view>
</template>

<script>
export default {
    name: 'App'
}
</script>

<style>
@import 'tailwindcss/base';
@import 'tailwindcss/components';
@import 'tailwindcss/utilities';

/* Custom styles */
.card {
    @apply bg-gray-800 rounded-lg shadow-lg p-6;
}

.input-field {
    @apply bg-gray-700 border-gray-600 text-white rounded-md;
}

.btn-primary {
    @apply bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded;
}

.btn-secondary {
    @apply bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded;
}

.badge {
    @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
}

.badge-success {
    @apply bg-green-100 text-green-800;
}

.badge-primary {
    @apply bg-blue-100 text-blue-800;
}

.badge-warning {
    @apply bg-yellow-100 text-yellow-800;
}

.badge-info {
    @apply bg-indigo-100 text-indigo-800;
}

.badge-secondary {
    @apply bg-gray-100 text-gray-800;
}
</style> 