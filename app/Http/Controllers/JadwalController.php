<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class JadwalController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        
        $jadwalList = [
            ['id' => 1, 'skema' => 'Junior Web Dev', 'pendaftaran_mulai' => Carbon::create(2025, 10, 1), 'pendaftaran_selesai' => Carbon::create(2025, 10, 15), 'tanggal_asesmen' => Carbon::create(2025, 10, 25), 'tuk' => 'Polines', 'kuota' => 30, 'terisi' => 25],
            ['id' => 2, 'skema' => 'Junior Web Dev', 'pendaftaran_mulai' => Carbon::create(2025, 10, 1), 'pendaftaran_selesai' => Carbon::create(2025, 10, 15), 'tanggal_asesmen' => Carbon::create(2025, 10, 25), 'tuk' => 'Polines', 'kuota' => 30, 'terisi' => 30],
            ['id' => 3, 'skema' => 'Junior Web Dev', 'pendaftaran_mulai' => Carbon::create(2025, 9, 1), 'pendaftaran_selesai' => Carbon::create(2025, 9, 15), 'tanggal_asesmen' => Carbon::create(2025, 9, 25), 'tuk' => 'Polines', 'kuota' => 30, 'terisi' => 28],
            ['id' => 4, 'skema' => 'Junior Web Dev', 'pendaftaran_mulai' => Carbon::create(2025, 12, 1), 'pendaftaran_selesai' => Carbon::create(2025, 12, 15), 'tanggal_asesmen' => Carbon::create(2025, 12, 25), 'tuk' => 'Polines', 'kuota' => 30, 'terisi' => 10],
        ];

        // Proses setiap jadwal untuk menentukan status otomatis
        foreach ($jadwalList as &$jadwal) {
            // Cek apakah kuota penuh
            if ($jadwal['terisi'] >= $jadwal['kuota']) {
                $jadwal['status'] = 'Full';
                $jadwal['statusColor'] = 'text-yellow-700';
                $jadwal['statusBg'] = 'bg-yellow-200';
                $jadwal['dapat_daftar'] = false;
            }
            // Cek apakah sudah melewati tanggal selesai pendaftaran
            elseif ($now->greaterThan($jadwal['pendaftaran_selesai'])) {
                $jadwal['status'] = 'Selesai';
                $jadwal['statusColor'] = 'text-gray-700';
                $jadwal['statusBg'] = 'bg-gray-200';
                $jadwal['dapat_daftar'] = false;
            }
            // Cek apakah belum dimulai pendaftaran
            elseif ($now->lessThan($jadwal['pendaftaran_mulai'])) {
                $jadwal['status'] = 'Akan datang';
                $jadwal['statusColor'] = 'text-blue-700';
                $jadwal['statusBg'] = 'bg-blue-100';
                $jadwal['dapat_daftar'] = false;
            }
            // Jika dalam periode pendaftaran dan masih ada kuota
            else {
                $jadwal['status'] = 'Dibuka';
                $jadwal['statusColor'] = 'text-teal-700';
                $jadwal['statusBg'] = 'bg-teal-100';
                $jadwal['dapat_daftar'] = true;
            }
        }

        return view('landing_page.jadwal', compact('jadwalList'));
    }

    // Method baru untuk detail jadwal
    public function detail($id)
    {
        $now = Carbon::now();
        
        // Data jadwal yang sama (idealnya simpan di method terpisah atau database)
        $jadwalList = [
            ['id' => 1, 'skema' => 'Junior Web Dev', 'pendaftaran_mulai' => Carbon::create(2025, 10, 1), 'pendaftaran_selesai' => Carbon::create(2025, 10, 15), 'tanggal_asesmen' => Carbon::create(2025, 10, 25), 'tuk' => 'Polines', 'kuota' => 30, 'terisi' => 25],
            ['id' => 2, 'skema' => 'Junior Web Dev', 'pendaftaran_mulai' => Carbon::create(2025, 10, 1), 'pendaftaran_selesai' => Carbon::create(2025, 10, 15), 'tanggal_asesmen' => Carbon::create(2025, 10, 25), 'tuk' => 'Polines', 'kuota' => 30, 'terisi' => 30],
            ['id' => 3, 'skema' => 'Junior Web Dev', 'pendaftaran_mulai' => Carbon::create(2025, 9, 1), 'pendaftaran_selesai' => Carbon::create(2025, 9, 15), 'tanggal_asesmen' => Carbon::create(2025, 9, 25), 'tuk' => 'Polines', 'kuota' => 30, 'terisi' => 28],
            ['id' => 4, 'skema' => 'Junior Web Dev', 'pendaftaran_mulai' => Carbon::create(2025, 12, 1), 'pendaftaran_selesai' => Carbon::create(2025, 12, 15), 'tanggal_asesmen' => Carbon::create(2025, 12, 25), 'tuk' => 'Polines', 'kuota' => 30, 'terisi' => 10],
        ];

        // Cari jadwal berdasarkan ID
        $jadwal = collect($jadwalList)->firstWhere('id', $id);

        // Jika tidak ditemukan
        if (!$jadwal) {
            abort(404, 'Jadwal tidak ditemukan');
        }

        // Tentukan status
        if ($jadwal['terisi'] >= $jadwal['kuota']) {
            $jadwal['status'] = 'Full';
            $jadwal['statusColor'] = 'text-yellow-700';
            $jadwal['statusBg'] = 'bg-yellow-200';
            $jadwal['dapat_daftar'] = false;
        } elseif ($now->greaterThan($jadwal['pendaftaran_selesai'])) {
            $jadwal['status'] = 'Selesai';
            $jadwal['statusColor'] = 'text-gray-700';
            $jadwal['statusBg'] = 'bg-gray-200';
            $jadwal['dapat_daftar'] = false;
        } elseif ($now->lessThan($jadwal['pendaftaran_mulai'])) {
            $jadwal['status'] = 'Akan datang';
            $jadwal['statusColor'] = 'text-blue-700';
            $jadwal['statusBg'] = 'bg-blue-100';
            $jadwal['dapat_daftar'] = false;
        } else {
            $jadwal['status'] = 'Dibuka';
            $jadwal['statusColor'] = 'text-teal-700';
            $jadwal['statusBg'] = 'bg-teal-100';
            $jadwal['dapat_daftar'] = true;
        }

        return view('detail_jadwal', compact('jadwal'));
    }
}