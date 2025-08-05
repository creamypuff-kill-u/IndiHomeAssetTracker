<script setup>
import { ref, onMounted } from 'vue'
import { getFirestore, doc, getDoc } from 'firebase/firestore'
import { useRoute } from 'vue-router' // atau pakai prop

const db = getFirestore()
const route = useRoute()
const area = ref(null)

onMounted(async () => {
  const docRef = doc(db, 'sectors', route.params.id)
  const snapshot = await getDoc(docRef)
  area.value = snapshot.exists() ? snapshot.data() : null
})
</script>

<template>
  <div class="max-w-3xl mx-auto py-6">
    <div v-if="area" class="bg-white rounded shadow p-6 space-y-4">
      <img
        :src="area.image ? `/images/${area.image}` : '/images/default.png'"
        alt="Area Image"
        class="w-12 h-12 object-cover rounded mr-2"
      />
      <h2 class="text-2xl font-bold">{{ area.name }}</h2>
      <p class="text-gray-600">{{ area.description }}</p>
      <p class="text-sm text-gray-500">Koordinat: {{ area.lat }}, {{ area.lng }}</p>
    </div>
    <div v-else class="text-center text-gray-500">Data area tidak ditemukan.</div>
  </div>
</template>
