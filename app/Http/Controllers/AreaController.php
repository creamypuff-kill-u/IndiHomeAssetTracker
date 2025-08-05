<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AreaController extends Controller
{
    // Method ini akan merender halaman yang berisi form input data
    public function index()
    {
        // ✅ Render komponen halaman form yang baru dibuat
        return Inertia::render('Area/AreaFormPage');
    }

    // Jika Anda punya metode store untuk menyimpan data dari form
    // (Form Anda saat ini mengirim langsung ke Firestore, jadi metode ini mungkin tidak terpakai untuk itu)
    // public function store(Request $request) { /* ... */ }
}