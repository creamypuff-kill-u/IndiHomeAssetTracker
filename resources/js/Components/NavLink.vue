<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
    // ✅ Hapus prop iconSrc jika Anda tidak akan menggunakan gambar PNG/JPG
    // Jika ingin fleksibel untuk Gambar PNG/JPG dan SVG, Anda bisa biarkan iconSrc
    // tapi kalau untuk icon yang warnanya berubah, SVG jauh lebih baik.
    // Untuk saat ini, kita fokus ke SVG (seperti yang ada di AuthenticatedLayout)
});

const classes = computed(() => {
    // Styling dasar untuk semua item NavLink
    const baseClasses = 'flex items-center gap-3 px-4 py-2 text-base font-medium rounded-lg transition-colors duration-200 ease-in-out';

    // Styling khusus untuk active state (warna dari contoh screenshot)
    const activeClasses = 'bg-red-100 text-red-700';

    // Styling khusus untuk inactive state
    const inactiveClasses = 'text-gray-600 hover:bg-gray-50 hover:text-gray-800';

    return props.active
        ? `${baseClasses} ${activeClasses}`
        : `${baseClasses} ${inactiveClasses}`;
});
</script>

<template>
    <Link :href="href" :class="classes">
        <slot name="icon" />
        <slot />
    </Link>
</template>