<script setup>
import { defineProps } from 'vue';

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({
      tenagaKerja: {},
      alatProduksi: {},
      alatMaterialKerja: {},
      listPelanggan: {},
      competitors: [],
      stakeholders: [],
    })
  }
});

// Fungsi pembantu untuk mendapatkan ikon SVG (Anda bisa mengganti ini dengan ikon Tailwind atau Heroicons)
const getIcon = (type) => {
  switch (type) {
    case 'tenagaKerja':
      return `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 mr-1"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>`; // Pekerja
    case 'alatProduksi':
      return `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 mr-1"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" /></svg>`; // Alat
    case 'alatMaterialKerja':
      return `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 mr-1"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" /></svg>`; // Toolbox
    case 'listPelanggan':
      return `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 mr-1"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" /></svg>`; // Pengguna
    case 'internetProvider':
      return `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 mr-1"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>`; // Pakai ikon pelanggan lagi
    case 'stakeholder':
      return `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 mr-1"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" /></svg>`; // Pakai ikon pelanggan lagi
    default:
      return '';
  }
};
</script>

<template>
  <div class="grid grid-cols-1 md:grid-cols-4 gap-6 p-4">
    <div class="border border-blue-400 rounded-lg p-4 bg-white" style="border-style: dotted;">
      <h4 class="text-lg font-semibold text-[#991B1B] mb-3 flex items-center">
        <span v-html="getIcon('tenagaKerja')"></span>
        Tenaga Kerja
      </h4>
      <ul class="space-y-1 text-gray-700 text-sm">
        <li class="flex justify-between items-center" v-if="props.stats.tenagaKerja.headOfServiceArea !== undefined">
          Head of Service Area <span class="font-medium">{{ props.stats.tenagaKerja.headOfServiceArea }}</span>
        </li>
        <li class="flex justify-between items-center" v-if="props.stats.tenagaKerja.officerServiceArea !== undefined">
          Officer Service Area <span class="font-medium">{{ props.stats.tenagaKerja.officerServiceArea }}</span>
        </li>
        <li class="flex justify-between items-center" v-if="props.stats.tenagaKerja.koordinatorLapangan !== undefined">
          Koordinator Lapangan <span class="font-medium">{{ props.stats.tenagaKerja.koordinatorLapangan }}</span>
        </li>
        <li class="flex justify-between items-center" v-if="props.stats.tenagaKerja.maintenance !== undefined">
          Maintenance <span class="font-medium">{{ props.stats.tenagaKerja.maintenance }}</span>
        </li>
        <li class="flex justify-between items-center" v-if="props.stats.tenagaKerja.warehouseServiceOfficer !== undefined">
          Warehouse Service Officer <span class="font-medium">{{ props.stats.tenagaKerja.warehouseServiceOfficer }}</span>
        </li>
        <li class="flex justify-between items-center" v-if="props.stats.tenagaKerja.psb !== undefined">
          PSB <span class="font-medium">{{ props.stats.tenagaKerja.psb }}</span>
        </li>
        <li class="flex justify-between items-center" v-if="props.stats.tenagaKerja.assurance !== undefined">
          Assurance <span class="font-medium">{{ props.stats.tenagaKerja.assurance }}</span>
        </li>
      </ul>
    </div>

    <div class="border border-blue-400 rounded-lg p-4 bg-white" style="border-style: dotted;">
      <h4 class="text-lg font-semibold text-[#991B1B] mb-3 flex items-center">
        <span v-html="getIcon('alatProduksi')"></span>
        Alat Produksi
      </h4>
      <ul class="space-y-1 text-gray-700 text-sm">
        <li class="flex justify-between items-center" v-if="props.stats.alatProduksi.odp !== undefined">
          Optical Distribution Point <span class="font-medium">{{ props.stats.alatProduksi.odp }}</span>
        </li>
        <li class="flex justify-between items-center" v-if="props.stats.alatProduksi.odc !== undefined">
          Optical Distribution Cabinet <span class="font-medium">{{ props.stats.alatProduksi.odc }}</span>
        </li>
        <li class="flex justify-between items-center" v-if="props.stats.alatProduksi.olt !== undefined">
          Optical Line Terminal <span class="font-medium">{{ props.stats.alatProduksi.olt }}</span>
        </li>
        <li class="flex justify-between items-center" v-if="props.stats.alatProduksi.ftm !== undefined">
          Fiber Termination Management <span class="font-medium">{{ props.stats.alatProduksi.ftm }}</span>
        </li>
      </ul>
    </div>

    <div class="border border-blue-400 rounded-lg p-4 bg-white" style="border-style: dotted;">
      <h4 class="text-lg font-semibold text-[#991B1B] mb-3 flex items-center">
        <span v-html="getIcon('alatMaterialKerja')"></span>
        Alat & Material Kerja
      </h4>
      <ul class="space-y-1 text-gray-700 text-sm">
        <li class="flex justify-between items-center" v-if="props.stats.alatMaterialKerja.splicer !== undefined">
          Splicer <span class="font-medium">{{ props.stats.alatMaterialKerja.splicer }}</span>
        </li>
        <li class="flex justify-between items-center" v-if="props.stats.alatMaterialKerja.opmOls !== undefined">
          OPM & OLS <span class="font-medium">{{ props.stats.alatMaterialKerja.opmOls }}</span>
        </li>
        <li class="flex justify-between items-center" v-if="props.stats.alatMaterialKerja.otdr !== undefined">
          OTDR <span class="font-medium">{{ props.stats.alatMaterialKerja.otdr }}</span>
        </li>
        <li class="flex justify-between items-center" v-if="props.stats.alatMaterialKerja.tangga !== undefined">
          Tangga <span class="font-medium">{{ props.stats.alatMaterialKerja.tangga }}</span>
        </li>
        <li class="flex justify-between items-center" v-if="props.stats.alatMaterialKerja.sepedaMotor !== undefined">
          Sepeda Motor <span class="font-medium">{{ props.stats.alatMaterialKerja.sepedaMotor }}</span>
        </li>
        <li class="flex justify-between items-center" v-if="props.stats.alatMaterialKerja.mobil !== undefined">
          Mobil <span class="font-medium">{{ props.stats.alatMaterialKerja.mobil }}</span>
        </li>
      </ul>
    </div>

    <div class="border border-blue-400 rounded-lg p-4 bg-white" style="border-style: dotted;">
      <h4 class="text-lg font-semibold text-[#991B1B] mb-3 flex items-center">
        <span v-html="getIcon('listPelanggan')"></span>
        List Pelanggan
      </h4>
      <ul class="space-y-1 text-gray-700 text-sm">
        <li class="flex justify-between items-center" v-if="props.stats.listPelanggan.regular !== undefined">
          Regular <span class="font-medium">{{ props.stats.listPelanggan.regular }}</span>
        </li>
        <li class="flex justify-between items-center" v-if="props.stats.listPelanggan.hvc !== undefined">
          HVC <span class="font-medium">{{ props.stats.listPelanggan.hvc }}</span>
        </li>
        <li class="flex justify-between items-center font-semibold" v-if="props.stats.listPelanggan.astinetSpbu !== undefined">
          Astinet SPBU <span class="font-medium">{{ props.stats.listPelanggan.astinetSpbu }}</span>
        </li>
        <li class="flex justify-between items-center font-semibold" v-if="props.stats.listPelanggan.listNodeB !== undefined">
          List Node B <span class="font-medium">{{ props.stats.listPelanggan.listNodeB }}</span>
        </li>
        <li class="flex justify-between items-center font-semibold" v-if="props.stats.listPelanggan.daftarDataInternet !== undefined">
          Daftar Data Internet <span class="font-medium">{{ props.stats.listPelanggan.daftarDataInternet }}</span>
        </li>
      </ul>
    </div>

    <div class="border border-blue-400 rounded-lg p-4 bg-white md:col-span-2" style="border-style: dotted;">
      <h4 class="text-lg font-semibold text-[#991B1B] mb-3 flex items-center">
        <span v-html="getIcon('internetProvider')"></span>
        Internet Provider Competitor
      </h4>
      <div class="grid grid-cols-3 gap-4">
        <div class="aspect-w-1 aspect-h-1 flex items-center justify-center rounded-lg overflow-hidden">
            <img src="/images/first-media.png" alt="First Media Logo" class="w-full h-full object-contain">
        </div>
        <div class="aspect-w-1 aspect-h-1 flex items-center justify-center rounded-lg overflow-hidden">
            <img src="/images/biznet.png" alt="Biznet Logo" class="w-full h-full object-contain">
        </div>
        <div class="aspect-w-1 aspect-h-1 flex items-center justify-center rounded-lg overflow-hidden">
            <img src="/images/dnet.png" alt="D'net Logo" class="w-full h-full object-contain">
        </div>
        <div class="aspect-w-1 aspect-h-1 flex items-center justify-center rounded-lg overflow-hidden">
            <img src="/images/mnc-play.png" alt="MNC Play Logo" class="w-full h-full object-contain">
        </div>
        <div class="aspect-w-1 aspect-h-1 flex items-center justify-center rounded-lg overflow-hidden">
            <img src="/images/myrepublic.png" alt="MyRepublic Logo" class="w-full h-full object-contain">
        </div>
    </div>
    </div>

    <div class="border border-blue-400 rounded-lg p-4 bg-white md:col-span-2" style="border-style: dotted;">
      <h4 class="text-lg font-semibold text-[#991B1B] mb-3 flex items-center">
        <span v-html="getIcon('stakeholder')"></span>
        Stake Holder
      </h4>
      <ul class="list-disc list-inside text-gray-700 text-xl">
        <li>Telkom</li>
        <li>Telkomsel</li>
        <li>TIF</li>
        <li>TA</li>
        <li>Mitra</li>
      </ul>
    </div>

<!-- <div class="border border-blue-400 rounded-lg p-4 bg-white" style="border-style: dotted;">
      <h4 class="text-lg font-semibold text-red-600 mb-3 flex items-center">
        <span v-html="getIcon('internetProvider')"></span>
        Internet Provider Competitor
      </h4>
      <div class="grid grid-cols-3 gap-4">
        <div class="bg-gray-200 aspect-w-1 aspect-h-1 flex items-center justify-center rounded-lg overflow-hidden">
          <img src="/images/first-media.png" alt="First Media Logo" class="w-full h-full object-cover">
        </div>
        <div class="bg-gray-200 aspect-w-1 aspect-h-1 flex items-center justify-center rounded-lg overflow-hidden">
          <img src="/images/biznet.png" alt="Biznet Logo" class="w-full h-full object-cover">
        </div>
        <div class="bg-gray-200 aspect-w-1 aspect-h-1 flex items-center justify-center rounded-lg overflow-hidden">
          <img src="/images/dnet.png" alt="D'net Logo" class="w-full h-full object-cover">
        </div>
        <div class="bg-gray-200 aspect-w-1 aspect-h-1 flex items-center justify-center rounded-lg overflow-hidden">
          <img src="/images/mnc-play.png" alt="MNC Play Logo" class="w-full h-full object-cover">
        </div>
        <div class="bg-gray-200 aspect-w-1 aspect-h-1 flex items-center justify-center rounded-lg overflow-hidden">
          <img src="/images/myrepublic.png" alt="MyRepublic Logo" class="w-full h-full object-cover">
        </div>
        <div class="bg-gray-200 aspect-w-1 aspect-h-1 flex items-center justify-center rounded-lg overflow-hidden">
          <img src="/images/other-logo.png" alt="Other Logo" class="w-full h-full object-cover">
        </div>
      </div>
    </div>

    <div class="border border-blue-400 rounded-lg p-4 bg-white" style="border-style: dotted;">
      <h4 class="text-lg font-semibold text-red-600 mb-3 flex items-center">
        <span v-html="getIcon('stakeholder')"></span>
        Stake Holder
      </h4>
      <ul class="list-disc list-inside text-gray-700 text-sm">
        <li>Telkom</li>
        <li>Telkomsel</li>
        <li>TIF</li>
        <li>TA</li>
        <li>Mitra</li>
      </ul>
    </div> -->


  </div>
</template>

<style scoped>
/* Anda bisa menambahkan gaya kustom jika diperlukan */
</style>