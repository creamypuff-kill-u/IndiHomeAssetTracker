<script setup>
import { ref, computed } from 'vue'

const selectedArea = ref(null)
const showModal = ref(false)

function openModal(area) {
  selectedArea.value = area
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  selectedArea.value = null
}

const props = defineProps({
    areas: {
        type: Array,
        default: () => []
    },
    selectedAreaId: {
        type: String,
        default: null
    },
    displayMode: {
        type: String,
        default: 'table',
        validator: (value) => ['table', 'summary', 'card'].includes(value)
    }
})

const emit = defineEmits(['editArea', 'deleteArea', 'showArea'])

const handleEditClick = (area) => {
    emit('editArea', area)
}

const handleDeleteClick = (areaId) => {
    if (confirm('Apakah Anda yakin ingin menghapus area ini?')) {
        emit('deleteArea', areaId)
    }
}

const handleShowClick = (areaId) => {
    const clickedArea = props.areas.find(area => area.id === areaId);
    if (clickedArea) {
        emit('showArea', areaId);
        console.log('Detail Area yang Diklik:', clickedArea);
    } else {
        console.warn('Area dengan ID:', areaId, 'tidak ditemukan.');
    }
}

const sortedAreas = computed(() => {
    if (!props.areas || props.areas.length === 0) {
        return [];
    }
    return [...props.areas].sort((a, b) => {
        const nameA = a.name || '';
        const nameB = b.name || '';
        return nameA.localeCompare(nameB);
    });
});

// ✅ KODE PAGINASI BARU
const currentPage = ref(1);
const itemsPerPage = 10;

const paginatedAreas = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return sortedAreas.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(sortedAreas.value.length / itemsPerPage);
});

const goToPage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
};

const visiblePageNumbers = computed(() => {
    const pages = [];
    const maxVisible = 5; // Jumlah maksimal tombol halaman yang terlihat
    let startPage = Math.max(1, currentPage.value - Math.floor(maxVisible / 2));
    let endPage = Math.min(totalPages.value, startPage + maxVisible - 1);

    if (endPage - startPage + 1 < maxVisible) {
        startPage = Math.max(1, endPage - maxVisible + 1);
    }

    for (let i = startPage; i <= endPage; i++) {
        pages.push(i);
    }
    return pages;
});
</script>

<template>
  <div class="h-full overflow-y-auto p-4">
    <div v-if="props.areas.length === 0" class="text-gray-500">
      <p>Tidak ada aset yang ditemukan.</p>
      <p>Anda bisa menambahkannya menggunakan form.</p>
    </div>
    <div v-else>
      <div v-if="props.displayMode === 'table'" class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Latitude</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Longitude</th>
                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(area, index) in paginatedAreas" :key="area.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ index + 1 + (currentPage - 1) * itemsPerPage }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex-shrink-0 w-16 h-16 rounded-md overflow-hidden bg-gray-100 flex items-center justify-center">
                    <img
                    :src="area.image ? `/images/${area.image}` : '/images/default.png'"
                    alt="Area Image"
                    class="w-12 h-12 object-cover rounded mr-2"
                    />
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ area.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ area.lat }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ area.lng }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex justify-center space-x-2">
                    <button
                        @click="handleShowClick(area.id)"
                        class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold py-1 px-2 rounded"
                    >
                        Show
                    </button>
                    <button
                        @click="handleEditClick(area)"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-bold py-1 px-2 rounded"
                    >
                        Edit
                    </button>
                    <button
                        @click="handleDeleteClick(area.id)"
                        class="bg-red-500 hover:bg-red-600 text-white text-xs font-bold py-1 px-2 rounded"
                    >
                        Delete
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-else-if="props.displayMode === 'summary'" class="space-y-4">
            <div v-for="area in paginatedAreas" :key="area.id" class="flex items-start gap-4 p-4 border rounded-lg shadow-sm">
                <div class="flex-shrink-0 w-20 h-20 bg-gray-200 rounded-md overflow-hidden">
                    <img
                    :src="area.image ? `/images/${area.image}` : '/images/default.png'"
                    alt="Area Image"
                    class="w-12 h-12 object-cover rounded mr-2"
                    />
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-md text-gray-800">{{ area.name }}</h4>
                    <p class="text-sm text-gray-600 line-clamp-2">{{ area.description }}</p>
                    <p class="text-xs text-gray-500 mt-1">Lat: {{ area.lat }}, Lng: {{ area.lng }}</p>
                    <div v-for="area in sortedAreas" :key="area.id" class="border p-2">
                        <p>{{ area.name }}</p>
                        <button @click="openModal(area)">Lihat Detail</button>
                    </div>
                </div>
            </div>
        </div>

        <div v-else-if="props.displayMode === 'card'" class="grid gap-4">
            <div
                v-for="area in paginatedAreas"
                :key="area.id"
                :class="['border p-2 rounded-lg shadow-sm bg-white cursor-pointer hover:shadow-md transition-shadow', { 'border-blue-500 ring-2 ring-blue-500': area.id === selectedAreaId }]"
                @click="handleShowClick(area.id)"
            >
                <div class="flex items-start">
                        <img
                        :src="area.image ? `/images/${area.image}` : '/images/default.png'"
                        alt="Area Image"
                        class="w-12 h-12 object-cover rounded mr-2"
                        />
                    <div>
                        <p class="font-bold text-sm">{{ area.name }}</p>
                        <p class="text-xs text-gray-700 mb-1">{{ area.address || 'Alamat tidak tersedia' }}</p>
                        <p class="text-xs text-gray-500">Lat: {{ parseFloat(area.lat).toFixed(4) }}, Lng: {{ parseFloat(area.lng).toFixed(4) }}</p>
                    </div>
                </div>
                <button
                    @click.stop="handleShowClick(area.id)"
                    class="mt-2 bg-yellow-500 hover:bg-yellow-600 text-white text-xs px-3 py-1 rounded-md transition-colors duration-200 w-full"
                >
                    Lihat Detail
                </button>
            </div>
        </div>

        <div v-else class="p-4 text-red-500">
            Mode tampilan tidak dikenal: {{ props.displayMode }}
        </div>
    </div>

    <div v-if="totalPages > 1" class="flex justify-center items-center mt-4 space-x-2">
        <button
            @click="goToPage(currentPage - 1)"
            :disabled="currentPage === 1"
            class="px-3 py-1 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 disabled:opacity-50"
        >
            Sebelumnya
        </button>
        
        <span v-if="visiblePageNumbers[0] > 1">...</span>

        <button
            v-for="page in visiblePageNumbers"
            :key="page"
            @click="goToPage(page)"
            :class="['px-3 py-1 text-sm font-medium rounded-md', currentPage === page ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200']"
        >
            {{ page }}
        </button>

        <span v-if="visiblePageNumbers[visiblePageNumbers.length - 1] < totalPages">...</span>

        <button
            @click="goToPage(currentPage + 1)"
            :disabled="currentPage === totalPages"
            class="px-3 py-1 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 disabled:opacity-50"
        >
            Berikutnya
        </button>
    </div>
  </div>
</template>

<style scoped>
/* Gaya yang sudah ada */
.truncate {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>