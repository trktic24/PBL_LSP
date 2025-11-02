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
        // --- DATA SIMULASI DAFTAR TUK (5 Jurusan) ---
        $tuks = [
            [
                'id' => 1, 
                'nama' => 'Politeknik Negeri Semarang', 
                'sub_nama' => 'Jurusan Teknik Elektro', 
                'alamat' => 'Gedung Teknik Elektro Lt. 3, Jl. Soedarto',
                'kontak' => '(024) 7473417 ext.301' 
            ],
            [
                'id' => 2, 
                'nama' => 'Politeknik Negeri Semarang', 
                'sub_nama' => 'Jurusan Teknik Mesin', 
                'alamat' => 'Workshop Utama Teknik Mesin, Kampus I',
                'kontak' => '(024) 7473417 ext.402'
            ],
            [
                'id' => 3, 
                'nama' => 'Politeknik Negeri Semarang', 
                'sub_nama' => 'Jurusan Teknik Sipil', 
                'alamat' => 'Laboratorium Terpadu Teknik Sipil',
                'kontak' => '(024) 7473417 ext.503'
            ],
            [
                'id' => 4, 
                'nama' => 'Politeknik Negeri Semarang', 
                'sub_nama' => 'Jurusan Administrasi Bisnis', 
                'alamat' => 'Gedung Administrasi Bisnis, Lt. 2',
                'kontak' => '(024) 7473417 ext.604'
            ],
            [
                'id' => 5, 
                'nama' => 'Politeknik Negeri Semarang', 
                'sub_nama' => 'Jurusan Akuntansi', 
                'alamat' => 'Laboratorium Akuntansi & Keuangan',
                'kontak' => '(024) 7473417 ext.705'
            ],
        ];
        // ---------------------------------
        
        return view('landing_page.page_tuk.info-tuk', [
            'tuks' => $tuks 
        ]);
    }


    /**
     * Menampilkan detail spesifik dari satu TUK berdasarkan ID.
     * Catatan: Data detail di sini juga perlu diperbarui untuk ID 4 dan 5!
     */
    public function showDetail($id)
    {
        // Untuk DEMO, kita buat data detail TUK berdasarkan ID yang diminta
        $data_tuk = [];
        
        switch ($id) {
            case 1:
                $data_tuk = [
                    'id' => 1,
                    'nama_lengkap' => "TUK Teknik Elektro", 
                    'alamat_detail' => 'Gedung Teknik Elektro Lt. 3, Jl. Prof. Soedarto',
                    'telepon' => '(024) 7473417 ext.301',
                    'koordinat_maps' => '#', 
                ];
                break;
            case 2:
                $data_tuk = [
                    'id' => 2,
                    'nama_lengkap' => "TUK Teknik Mesin", 
                    'alamat_detail' => 'Workshop Utama Teknik Mesin, Kampus I',
                    'telepon' => '(024) 7473417 ext.402',
                    'koordinat_maps' => '#', 
                ];
                break;
            case 3:
                $data_tuk = [
                    'id' => 3,
                    'nama_lengkap' => "TUK Teknik Sipil", 
                    'alamat_detail' => 'Laboratorium Terpadu Teknik Sipil',
                    'telepon' => '(024) 7473417 ext.503',
                    'koordinat_maps' => '#', 
                ];
                break;
            case 4:
                $data_tuk = [
                    'id' => 4,
                    'nama_lengkap' => "TUK Administrasi Bisnis", 
                    'alamat_detail' => 'Gedung Administrasi Bisnis, Lt. 2',
                    'telepon' => '(024) 7473417 ext.604',
                    'koordinat_maps' => '#', 
                ];
                break;
            case 5:
                $data_tuk = [
                    'id' => 5,
                    'nama_lengkap' => "TUK Akuntansi", 
                    'alamat_detail' => 'Laboratorium Akuntansi & Keuangan',
                    'telepon' => '(024) 7473417 ext.705',
                    'koordinat_maps' => '#', 
                ];
                break;
            default:
                // Jika ID tidak ditemukan (misalnya: ID 99)
                abort(404);
        }
        
        return view('landing_page.page_tuk.detail-tuk', [
            'data_tuk' => $data_tuk,
        ]);
    }
}