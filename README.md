<p align="center"><img src="images/telkom.png" width="200" alt="Telkom Logo"></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Dashboard Asset Tracker

Aplikasi web untuk memantau, mengelola, dan melacak aset-aset operasional (seperti ODC, ODP) milik Telkom Akses. Dashboard ini menyediakan tampilan visual berupa peta lokasi, daftar aset, serta ringkasan statistik operasional untuk mempermudah manajemen dan pemantauan.

## ✨ Fitur Utama

-   **Dashboard Interaktif:** Menampilkan peta interaktif yang memvisualisasikan lokasi aset.
-   **Visualisasi Aset:** Menggunakan ikon kustom (menara merah) untuk menandai lokasi aset di peta.
-   **Detail Aset:** Popup informatif muncul saat marker peta diklik, menampilkan detail aset seperti nama, status, kapasitas, dan alamat.
-   **Manajemen Statistik:** Halaman terpisah untuk mengelola statistik operasional (tenaga kerja, alat produksi, dll.) melalui form CRUD.
-   **Tampilan Statistik:** Ringkasan statistik operasional ditampilkan dalam layout 4-kolom yang rapi di dashboard.
-   **Daftar Aset:** Daftar aset ditampilkan dengan rapi, dilengkapi dengan fitur pengurutan abjad dan paginasi (10 item per halaman).
-   **Pengelolaan Aset:** Form untuk menambah, mengedit, dan menghapus data aset.
-   **Integrasi Firebase:** Menggunakan Firebase Firestore sebagai database real-time.

## 🚀 Tumpukan Teknologi (Tech Stack)

-   **Backend:** [Laravel](https://laravel.com/)
-   **Frontend:** [Vue 3](https://vuejs.org/) (dengan `script setup`)
-   **State Management & Routing:** [Inertia.js](https://inertiajs.com/)
-   **Database:** [Firebase Firestore](https://firebase.google.com/docs/firestore)
-   **Peta:** [Leaflet.js](https://leafletjs.com/) (menggunakan `@vue-leaflet/vue-leaflet`)
-   **Antarmuka Pengguna:** [Tailwind CSS](https://tailwindcss.com/)
-   **Notifikasi:** [SweetAlert2](https://sweetalert2.github.io/)
