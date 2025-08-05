<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue' // Tambahkan onMounted untuk fetching data
import { collection, getDocs } from 'firebase/firestore'
import { firestore } from '@/lib/firebase' // Import instance firestore

// Komponen Anak
import AreaMap from '@/Pages/Area/AreaMap.vue'
import AreaList from '@/Pages/Area/AreaList.vue'
// import AreaForm from '@/Pages/Area/AreaForm.vue' // Hapus atau jadikan kondisional jika tidak perlu di sini

const db = firestore
const areas = ref([])
const isLoading = ref(true) // State untuk loading

// Ambil data dari Firebase Firestore
const fetchAreas = async () => {
    isLoading.value = true;
    try {
        const snapshot = await getDocs(collection(db, 'sectors'))
        areas.value = snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }))
        console.log('Data area berhasil diambil oleh AreaPage:', areas.value);
    } catch (error) {
        console.error('Gagal mengambil data area oleh AreaPage:', error);
        // Anda bisa tambahkan logic untuk menampilkan error ke user di sini
    } finally {
        isLoading.value = false;
    }
}

// Panggil fungsi fetchAreas saat komponen terpasang ke DOM
onMounted(() => {
    fetchAreas()
})
</script>

<template>
    <Head title="Area Operasi" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Area Operasi
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div v-if="isLoading" class="text-center py-8">
                        <p>Memuat data lokasi...</p>
                        </div>

                    <div v-else class="flex flex-col md:flex-row gap-6 h-[700px]"> <div class="flex-1 border rounded-lg overflow-hidden flex flex-col">
                            <h3 class="bg-red-200 text-white font-bold p-3 text-center rounded-t-lg">Peta Lokasi</h3>
                            <div class="flex-1">
                                <AreaMap :areas="areas" />
                            </div>
                        </div>

                        <div class="w-full md:w-1/3 border rounded-lg overflow-hidden flex flex-col">
                            <h3 class="bg-red-200 text-white font-bold p-3 text-center rounded-t-lg">Daftar Properti</h3>
                            <div class="flex-1 overflow-y-auto p-4">
                                <AreaList :areas="areas" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Anda bisa menambahkan gaya kustom di sini jika diperlukan,
   misalnya jika h-[700px] atau kelas flexbox Tailwind tidak bekerja sesuai keinginan. */
</style>