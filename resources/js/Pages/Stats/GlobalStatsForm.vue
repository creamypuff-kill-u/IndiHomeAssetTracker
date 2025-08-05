<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import { doc, getDoc, setDoc } from 'firebase/firestore'
import { firestore } from '@/lib/firebase'
import Swal from 'sweetalert2'; // Untuk notifikasi yang lebih baik

const db = firestore
const isLoading = ref(true)
const globalStatsData = ref(null) // Untuk menyimpan data statistik dari Firestore

// Inisialisasi form dengan struktur data yang diharapkan
const form = useForm({
    tenagaKerja: {
        headOfServiceArea: 0,
        officerServiceArea: 0,
        koordinatorLapangan: 0,
        maintenance: 0,
        warehouseServiceOfficer: 0,
        psb: 0,
        assurance: 0,
    },
    alatProduksi: {
        odp: 0,
        odc: 0,
        olt: 0,
        ftm: 0,
    },
    alatMaterialKerja: {
        splicer: 0,
        opmOls: 0,
        otdr: 0,
        tangga: 0,
        sepedaMotor: 0,
        mobil: 0,
    },
    listPelanggan: {
        regular: 0,
        hvc: 0,
        astinetSpbu: 0,
        listNodeB: 0,
        daftarDataInternet: 0,
    },
    // Anda bisa menambahkan field lain di sini jika ada
});

// Fungsi untuk mengambil data statistik dari Firestore
const fetchGlobalStats = async () => {
    isLoading.value = true;
    try {
        const statsDocRef = doc(db, 'stats', 'global');
        const statsDocSnap = await getDoc(statsDocRef);

        if (statsDocSnap.exists()) {
            globalStatsData.value = statsDocSnap.data();
            // Isi form dengan data yang ada
            form.tenagaKerja = { ...form.tenagaKerja, ...globalStatsData.value.tenagaKerja };
            form.alatProduksi = { ...form.alatProduksi, ...globalStatsData.value.alatProduksi };
            form.alatMaterialKerja = { ...form.alatMaterialKerja, ...globalStatsData.value.alatMaterialKerja };
            form.listPelanggan = { ...form.listPelanggan, ...globalStatsData.value.listPelanggan };
            console.log('Data statistik global berhasil diambil:', globalStatsData.value);
        } else {
            console.log('Dokumen statistik global (stats/global) tidak ditemukan. Form akan diisi dengan nilai default (0).');
            globalStatsData.value = null; // Menandakan tidak ada data sebelumnya
        }
    } catch (error) {
        console.error('Gagal mengambil data statistik global:', error);
        Swal.fire('Error', 'Gagal mengambil data statistik: ' + error.message, 'error');
    } finally {
        isLoading.value = false;
    }
};

// Fungsi untuk menyimpan/memperbarui data statistik ke Firestore
const submitForm = async () => {
    try {
        const statsDocRef = doc(db, 'stats', 'global');
        // Menggunakan setDoc dengan merge: true akan membuat dokumen jika tidak ada,
        // atau memperbarui field yang ada tanpa menimpa seluruh dokumen.
        await setDoc(statsDocRef, form.data(), { merge: true });
        Swal.fire('Berhasil!', 'Data statistik berhasil disimpan.', 'success');
        console.log('Data statistik berhasil disimpan:', form.data());
    } catch (error) {
        console.error('Gagal menyimpan data statistik:', error);
        Swal.fire('Error', 'Gagal menyimpan data statistik: ' + error.message, 'error');
    }
};

// Fungsi untuk menghapus data statistik global
const deleteStats = async () => {
    Swal.fire({
        title: 'Anda yakin?',
        text: "Anda tidak akan bisa mengembalikan ini!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const statsDocRef = doc(db, 'stats', 'global');
                // Untuk menghapus seluruh dokumen
                await deleteDoc(statsDocRef); // Pastikan Anda mengimpor deleteDoc dari 'firebase/firestore'
                globalStatsData.value = null;
                form.reset(); // Reset form setelah dihapus
                Swal.fire('Dihapus!', 'Data statistik telah dihapus.', 'success');
                console.log('Data statistik global berhasil dihapus.');
            } catch (error) {
                console.error('Gagal menghapus data statistik:', error);
                Swal.fire('Error', 'Gagal menghapus data statistik: ' + error.message, 'error');
            }
        }
    });
};


onMounted(() => {
    fetchGlobalStats();
});
</script>

<template>
    <Head title="Kelola Statistik" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-[#991B1B] leading-tight">
                Kelola Statistik Operasional
            </h2>
            <p class="font-reguler mt-2 text-xs text-[#71717A] leading-tight">
                Kelola data statistik tenaga kerja, alat produksi, dan pelanggan.
            </p>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div v-if="isLoading" class="text-center py-8">
                        <p>Memuat data statistik...</p>
                    </div>
                    <div v-else>
                        <form @submit.prevent="submitForm">
                            <h3 class="text-lg font-semibold mb-4 text-gray-700">Tenaga Kerja</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                                <div>
                                    <label for="headOfServiceArea" class="block text-sm font-medium text-gray-700">Head of Service Area</label>
                                    <input type="number" id="headOfServiceArea" v-model.number="form.tenagaKerja.headOfServiceArea" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label for="officerServiceArea" class="block text-sm font-medium text-gray-700">Officer Service Area</label>
                                    <input type="number" id="officerServiceArea" v-model.number="form.tenagaKerja.officerServiceArea" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label for="koordinatorLapangan" class="block text-sm font-medium text-gray-700">Koordinator Lapangan</label>
                                    <input type="number" id="koordinatorLapangan" v-model.number="form.tenagaKerja.koordinatorLapangan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label for="maintenance" class="block text-sm font-medium text-gray-700">Maintenance</label>
                                    <input type="number" id="maintenance" v-model.number="form.tenagaKerja.maintenance" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label for="warehouseServiceOfficer" class="block text-sm font-medium text-gray-700">Warehouse Service Officer</label>
                                    <input type="number" id="warehouseServiceOfficer" v-model.number="form.tenagaKerja.warehouseServiceOfficer" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label for="psb" class="block text-sm font-medium text-gray-700">PSB</label>
                                    <input type="number" id="psb" v-model.number="form.tenagaKerja.psb" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label for="assurance" class="block text-sm font-medium text-gray-700">Assurance</label>
                                    <input type="number" id="assurance" v-model.number="form.tenagaKerja.assurance" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                            </div>

                            <h3 class="text-lg font-semibold mb-4 text-gray-700">Alat Produksi</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                                <div>
                                    <label for="odp" class="block text-sm font-medium text-gray-700">Optical Distribution Point (ODP)</label>
                                    <input type="number" id="odp" v-model.number="form.alatProduksi.odp" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label for="odc" class="block text-sm font-medium text-gray-700">Optical Distribution Cabinet (ODC)</label>
                                    <input type="number" id="odc" v-model.number="form.alatProduksi.odc" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label for="olt" class="block text-sm font-medium text-gray-700">Optical Line Terminal (OLT)</label>
                                    <input type="number" id="olt" v-model.number="form.alatProduksi.olt" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label for="ftm" class="block text-sm font-medium text-gray-700">Fiber Termination Management (FTM)</label>
                                    <input type="number" id="ftm" v-model.number="form.alatProduksi.ftm" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                            </div>

                            <h3 class="text-lg font-semibold mb-4 text-gray-700">Alat & Material Kerja</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                                <div>
                                    <label for="splicer" class="block text-sm font-medium text-gray-700">Splicer</label>
                                    <input type="number" id="splicer" v-model.number="form.alatMaterialKerja.splicer" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label for="opmOls" class="block text-sm font-medium text-gray-700">OPM & OLS</label>
                                    <input type="number" id="opmOls" v-model.number="form.alatMaterialKerja.opmOls" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label for="otdr" class="block text-sm font-medium text-gray-700">OTDR</label>
                                    <input type="number" id="otdr" v-model.number="form.alatMaterialKerja.otdr" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label for="tangga" class="block text-sm font-medium text-gray-700">Tangga</label>
                                    <input type="number" id="tangga" v-model.number="form.alatMaterialKerja.tangga" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label for="sepedaMotor" class="block text-sm font-medium text-gray-700">Sepeda Motor</label>
                                    <input type="number" id="sepedaMotor" v-model.number="form.alatMaterialKerja.sepedaMotor" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label for="mobil" class="block text-sm font-medium text-gray-700">Mobil</label>
                                    <input type="number" id="mobil" v-model.number="form.alatMaterialKerja.mobil" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                            </div>

                            <h3 class="text-lg font-semibold mb-4 text-gray-700">List Pelanggan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                                <div>
                                    <label for="regular" class="block text-sm font-medium text-gray-700">Regular</label>
                                    <input type="number" id="regular" v-model.number="form.listPelanggan.regular" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label for="hvc" class="block text-sm font-medium text-gray-700">HVC</label>
                                    <input type="number" id="hvc" v-model.number="form.listPelanggan.hvc" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label for="astinetSpbu" class="block text-sm font-medium text-gray-700">Astinet SPBU</label>
                                    <input type="number" id="astinetSpbu" v-model.number="form.listPelanggan.astinetSpbu" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label for="listNodeB" class="block text-sm font-medium text-gray-700">List Node B</label>
                                    <input type="number" id="listNodeB" v-model.number="form.listPelanggan.listNodeB" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label for="daftarDataInternet" class="block text-sm font-medium text-gray-700">Daftar Data Internet</label>
                                    <input type="number" id="daftarDataInternet" v-model.number="form.listPelanggan.daftarDataInternet" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                            </div>

                            <div class="flex items-center justify-between mt-6">
                                <button type="submit" :disabled="form.processing" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Simpan Statistik
                                </button>
                                <button type="button" @click="deleteStats" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Hapus Statistik Global
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Anda bisa menambahkan gaya kustom di sini jika diperlukan */
</style>