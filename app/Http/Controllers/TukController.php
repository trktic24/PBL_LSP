<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TukController extends Controller
{
    /**
     * Menampilkan daftar Tempat Uji Kompetensi (TUK).
     * Untuk rute /info-tuk
     */
    public function index()
    {
        // --- SIMULASI DATA DAFTAR TUK ---
        // PENTING: Menambahkan 'sub_nama' dan 'kontak' agar sesuai dengan info-tuk.blade.php
        $tuks = [
            [
                'id' => 1, 
                'nama' => 'Politeknik Negeri Semarang', 
                'sub_nama' => 'Gedung Kuliah Terpadu', // DITAMBAHKAN
                'alamat' => 'Jl. Prof. Soedarto',
                'kontak' => '(024) 7473417 ext.256' // DITAMBAHKAN
            ],
            [
                'id' => 2, 
                'nama' => 'LSP Polines', 
                'sub_nama' => 'MST LT3', // DITAMBAHKAN (Sesuaikan dengan video Anda)
                'alamat' => 'Jl. Prof. Soedarto, Semarang',
                'kontak' => '25 Oktober 2025' // DITAMBAHKAN
            ],
            [
                'id' => 3, 
                'nama' => 'Politeknik Negeri Semarang', 
                'sub_nama' => 'Gedung Sekolah Satu', // DITAMBAHKAN
                'alamat' => 'Tembalang, Semarang',
                'kontak' => '(024) 7473417 ext.256' // DITAMBAHKAN
            ],
        ];
        // ---------------------------------
        
        return view('landing_page.page_tuk.info-tuk', [
            'tuks' => $tuks 
        ]);
    }


    /**
     * Menampilkan detail spesifik dari satu TUK berdasarkan ID.
     * Untuk rute /detail-tuk/{id}
     */
    public function showDetail($id)
    {


        // --- SIMULASI PENGAMBILAN DATA TUK BERDASARKAN ID ---
        // PENTING: Menyesuaikan 'key' dengan detail-tuk.blade.php yang sudah diperbaiki sebelumnya
        
        $data_tuk = [
            'id' => $id,
            'nama_lengkap' => "Politeknik Negeri Semarang", // 'key' yang digunakan di detail-tuk.blade.php
            'alamat_detail' => 'Jalan Prof. Soedarto No. 1, Semarang', // 'key' yang digunakan di detail-tuk.blade.php
            'telepon' => '082185585493', // 'key' yang digunakan di detail-tuk.blade.php
            'koordinat_maps' => '#', 
        ];
        // ----------------------------------------------------
        
        
        return view('landing_page.page_tuk.detail-tuk', [
            'data_tuk' => $data_tuk,
        ]);
    }
}