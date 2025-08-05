<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import { collection, getDocs, deleteDoc, doc } from 'firebase/firestore'
import { firestore } from '@/lib/firebase'

// Impor Komponen Anak
import AreaForm from '@/Pages/Area/AreaForm.vue'
import AreaList from '@/Pages/Area/AreaList.vue' // ✅ Pastikan ini diimpor

const db = firestore
const areas = ref([])
const isLoading = ref(true)
const editingArea = ref(null)
const isEditMode = ref(false)
const showForm = ref(false); // Kontrol visibilitas modal form

// --- Fungsi Pengambilan Data ---
const fetchAreas = async () => {
    isLoading.value = true;
    try {
        const snapshot = await getDocs(collection(db, 'sectors'))
        areas.value = snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }))
        console.log('Data area berhasil diambil oleh AreaFormPage:', areas.value);
    } catch (error) {
        console.error('Gagal mengambil data area oleh AreaFormPage:', error);
        alert('Gagal mengambil data area. Periksa konsol dan aturan keamanan Firestore.');
    } finally {
        isLoading.value = false;
    }
}

// --- Handler Event dari AreaList.vue ---

// tombol Edit dari AreaList
const handleEdit = (areaToEdit) => {
    editingArea.value = { ...areaToEdit }
    isEditMode.value = true
    showForm.value = true; // Tampilkan modal form saat edit
}

// tombol Delete dari AreaList
const handleDelete = async (areaId) => {
    try {
        await deleteDoc(doc(db, 'sectors', areaId))
        alert('Area berhasil dihapus!')
        fetchAreas() // Ambil ulang data setelah penghapusan
    } catch (error) {
        console.error('Gagal menghapus area:', error)
        alert('Gagal menghapus area. Periksa konsol dan aturan keamanan Firestore (terutama untuk delete).')
    }
}

// tombol Show dari AreaList
const handleShow = (areaId) => {
    console.log('Show details for Area ID:', areaId);
    alert(`Melihat detail untuk Area ID: ${areaId}. (Fitur ini dapat dikembangkan lebih lanjut)`);
}

// --- Handler Event dari AreaForm.vue ---

// form berhasil disubmit (baik add atau edit)
const handleFormSubmitted = () => {
    editingArea.value = null
    isEditMode.value = false
    showForm.value = false; // Sembunyikan modal setelah submit
    fetchAreas() // Refresh daftar
}

// --- Lain-lain ---

// Fungsi untuk membuka modal form untuk menambah area baru
const addNewArea = () => {
    editingArea.value = null; // Pastikan data edit kosong
    isEditMode.value = false; // Pastikan mode tambah
    showForm.value = true; // Tampilkan modal form
}

// Fungsi untuk menutup modal form
const closeModal = () => {
    editingArea.value = null;
    isEditMode.value = false;
    showForm.value = false;
}

// Panggil fungsi fetchAreas saat komponen terpasang ke DOM
onMounted(() => {
    fetchAreas()
})
</script>

<template>
    <Head title="Kelola Data Area Operasi" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Data Area Operasi
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <!-- <h1 class="text-lg font-bold mb-4">Daftar Data Area</h1> -->


                    <div class="mb-6 text-right">
                        <button
                            @click="addNewArea"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Tambah Area Baru
                        </button>
                    </div>

                    <div v-if="isLoading" class="text-center py-8">
                        <p>Memuat data area...</p>
                    </div>
                    <div v-else>
                        <AreaList
                            :areas="areas"
                            @editArea="handleEdit"
                            @deleteArea="handleDelete"
                            @showArea="handleShow"
                        />
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showForm" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div @click="closeModal" class="absolute inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>

            <div class="bg-white rounded-lg shadow-xl overflow-hidden transform transition-all sm:max-w-lg sm:w-full z-50">
                <div class="px-6 py-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">
                            {{ isEditMode ? 'Edit Lokasi Area Operasi' : 'Tambah Lokasi Area Operasi' }}
                        </h3>
                        <button @click="closeModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <AreaForm
                        :initialData="editingArea"
                        :isEditMode="isEditMode"
                        @formSubmitted="handleFormSubmitted"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>