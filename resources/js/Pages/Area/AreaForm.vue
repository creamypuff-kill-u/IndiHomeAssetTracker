<script setup>
import { ref, watchEffect } from 'vue'
import { addDoc, updateDoc, collection, doc } from 'firebase/firestore'
import { firestore } from '@/lib/firebase'

const props = defineProps({
    initialData: {
        type: Object,
        default: null
    },
    isEditMode: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['formSubmitted'])

const name = ref('')
const address = ref('') // ✅ Field baru untuk alamat
const lat = ref('')
const lng = ref('')
const imageUrl = ref('')
const status = ref('Aktif') // ✅ Field baru untuk status (default 'Aktif')
const stoName = ref('') // ✅ Field baru untuk Nama STO
const capacity = ref('') // ✅ Field baru untuk Kapasitas

const formProcessing = ref(false)
const errorMessage = ref(null)
const successMessage = ref(null)

// WATCH EFFECT: Mengisi form saat initialData berubah (untuk mode edit)
watchEffect(() => {
    if (props.isEditMode && props.initialData) {
        name.value = props.initialData.name || ''
        // ✅ Sesuaikan mapping field:
        address.value = props.initialData.address || '' // Ambil dari 'address' di Firestore
        lat.value = props.initialData.lat || ''
        lng.value = props.initialData.lng || ''
        imageUrl.value = props.initialData.image || ''
        status.value = props.initialData.status || 'Aktif' // Ambil dari 'status' di Firestore
        stoName.value = props.initialData.stoName || '' // Ambil dari 'stoName' di Firestore
        capacity.value = props.initialData.capacity || '' // Ambil dari 'capacity' di Firestore

        errorMessage.value = null;
        successMessage.value = null;
    } else {
        // Reset form saat tidak dalam mode edit atau initialData null
        name.value = ''
        address.value = '' // Reset alamat
        lat.value = ''
        lng.value = ''
        imageUrl.value = ''
        status.value = 'Aktif' // Reset status ke default
        stoName.value = '' // Reset STO Name
        capacity.value = '' // Reset capacity
        
        errorMessage.value = null;
        successMessage.value = null;
    }
})

// Fungsi untuk menyimpan data ke Firestore
const saveFirestoreData = async (imgUrl, parsedLatValue, parsedLngValue) => {
    try {
        const dataToSave = {
            name: name.value,
            address: address.value,     // ✅ Simpan alamat
            lat: parsedLatValue,
            lng: parsedLngValue,
            image: imgUrl || null,
            status: status.value,       // ✅ Simpan status
            stoName: stoName.value,     // ✅ Simpan STO Name
            capacity: parseFloat(capacity.value) || null // ✅ Simpan kapasitas (pastikan angka)
        };

        if (props.isEditMode && props.initialData && props.initialData.id) {
            await updateDoc(doc(firestore, 'sectors', props.initialData.id), dataToSave);
            successMessage.value = 'Area berhasil diperbarui!';
            console.log('Area berhasil diperbarui di Firestore!');
        } else {
            await addDoc(collection(firestore, 'sectors'), dataToSave);
            successMessage.value = 'Area berhasil ditambahkan!';
            console.log('Area berhasil ditambahkan ke Firestore!');
        }
        
        emit('formSubmitted');
    } catch (error) {
        console.error('Gagal menyimpan data ke Firestore:', error);
        errorMessage.value = `Gagal menyimpan data ke Firestore: ${error.message}`;
        throw error;
    } finally {
        formProcessing.value = false;
    }
}

// Handle submit
const submit = async () => {
    errorMessage.value = null;
    successMessage.value = null;
    formProcessing.value = true;

    try {
        // ✅ Sesuaikan validasi dengan field baru
        if (!name.value || !address.value || !lat.value || !lng.value) {
            errorMessage.value = 'Mohon lengkapi semua field yang wajib (Nama, Alamat, Latitude, Longitude).';
            return;
        }

        const parsedLat = parseFloat(lat.value);
        const parsedLng = parseFloat(lng.value);
        const parsedCapacity = parseFloat(capacity.value); // Pastikan kapasitas juga angka

        if (isNaN(parsedLat) || isNaN(parsedLng)) {
            errorMessage.value = 'Latitude dan Longitude harus berupa angka yang valid.';
            return;
        }
        if (capacity.value && isNaN(parsedCapacity)) { // Kapasitas opsional tapi harus angka jika diisi
            errorMessage.value = 'Kapasitas harus berupa angka yang valid.';
            return;
        }

        let finalImageUrl = imageUrl.value;
        
        await saveFirestoreData(finalImageUrl, parsedLat, parsedLng);

    } catch (err) {
        console.error('Gagal menyimpan/memperbarui area (umum):', err);
        if (err.code) {
            errorMessage.value = `Terjadi kesalahan Firebase: ${err.code} - ${err.message}`;
        } else {
            errorMessage.value = 'Gagal menyimpan/memperbarui area. Silakan coba lagi. Detail error di konsol.';
        }
    } finally {
        formProcessing.value = false;
    }
}
</script>

<template>
    <div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
        <!-- <h2 class="text-xl font-bold mb-4">
            {{ props.isEditMode ? 'Edit Lokasi Area Operasi' : 'Tambah Lokasi Area k' }}
        </h2> -->

        <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ successMessage }}</span>
        </div>

        <div v-if="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ errorMessage }}</span>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Aset <span class="text-red-500">*</span></label>
                <input v-model="name" type="text" id="name" class="w-full border p-2 rounded" required />
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">Alamat <span class="text-red-500">*</span></label>
                <textarea v-model="address" id="address" class="w-full border p-2 rounded" required rows="2"></textarea>
            </div>


            <div class="flex gap-4">
                <div class="w-1/2">
                    <label for="lat" class="block text-sm font-medium text-gray-700">Latitude <span class="text-red-500">*</span></label>
                    <input v-model="lat" type="number" step="any" id="lat" class="w-full border p-2 rounded" required />
                </div>
                <div class="w-1/2">
                    <label for="lng" class="block text-sm font-medium text-gray-700">Longitude <span class="text-red-500">*</span></label>
                    <input v-model="lng" type="number" step="any" id="lng" class="w-full border p-2 rounded" required />
                </div>
            </div>

            <div class="flex gap-4">
                <div class="w-1/2">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select v-model="status" id="status" class="w-full border p-2 rounded">
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>
                <div class="w-1/2">
                    <label for="capacity" class="block text-sm font-medium text-gray-700">Kapasitas (Angka)</label>
                    <input v-model="capacity" type="number" step="1" id="capacity" class="w-full border p-2 rounded" placeholder="Contoh: 288" />
                </div>
            </div>

            <div>
                <label for="stoName" class="block text-sm font-medium text-gray-700">Nama STO</label>
                <input v-model="stoName" type="text" id="stoName" class="w-full border p-2 rounded" placeholder="Contoh: KLJ" />
            </div>

            <div>
                <label for="imageUrl" class="block text-sm font-medium text-gray-700">Gambar Aset (URL)</label>
                <input v-model="imageUrl" type="text" id="imageUrl" class="w-full border p-2 rounded" placeholder="Tempel URL gambar di sini, cth: https://firebasestorage.com/..." />
                
                <p v-if="imageUrl" class="mt-2 text-sm text-gray-600">
                    Gambar saat ini: 
                    <a :href="imageUrl" target="_blank" class="text-blue-500 hover:underline">Lihat Gambar</a>
                    <span v-if="!imageUrl.startsWith('http')" class="text-red-500 ml-2">(URL mungkin tidak valid)</span>
                </p>
                <div v-if="imageUrl" class="mt-2">
                    <img :src="imageUrl" alt="Pratinjau Gambar" class="max-w-xs h-auto border rounded shadow-sm" @error="imageUrl = ''">
                </div>
            </div>

            <button
                type="submit"
                :disabled="formProcessing" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span v-if="formProcessing">Menyimpan...</span>
                <span v-else>{{ props.isEditMode ? 'Perbarui Lokasi' : 'Simpan Lokasi' }}</span>
            </button>
        </form>
    </div>
</template>