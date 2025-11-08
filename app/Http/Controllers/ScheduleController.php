<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Skema;
use App\Models\Tuk;
use App\Models\Asesor;
use App\Models\Asesi; // <-- WAJIB ADA
use App\Models\JenisTuk; 

class ScheduleController extends Controller
{
    /**
     * Menampilkan halaman dashboard kalender.
     */
    public function showCalendar()
    {
        return view('master.schedule.schedule_admin');
    }

    /**
     * Menampilkan daftar semua jadwal (master_schedule).
     * Disesuaikan dengan migrasi terbaru.
     */
    public function index(Request $request)
    {
        // 1. Ambil input sort dan direction, beri nilai default
        $sortColumn = $request->input('sort', 'id_jadwal');
        $sortDirection = $request->input('direction', 'asc');

        // 2. Daftar kolom yang BOLEH di-sort (sesuai migrasi baru)
        $allowedColumns = ['id_jadwal', 'tanggal_pelaksanaan', 'Status_jadwal'];
        
        if (!in_array($sortColumn, $allowedColumns)) $sortColumn = 'id_jadwal';
        if (!in_array($sortDirection, ['asc', 'desc'])) $sortDirection = 'asc';

        // 3. Mulai query (ditambahkan 'asesi')
        $query = Schedule::with(['skema', 'tuk', 'asesor', 'jenisTuk', 'asesi']);

        // 4. Terapkan 'search' (Filter)
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            
            $query->whereHas('skema', function($q) use ($searchTerm) {
                $q->where('nama_skema', 'like', '%' . $searchTerm . '%');
            })->orWhereHas('asesor', function($q) use ($searchTerm) {
                $q->where('nama_lengkap', 'like', '%' . $searchTerm . '%');
            })->orWhereHas('tuk', function($q) use ($searchTerm) {
                $q->where('nama_lokasi', 'like', '%' . $searchTerm . '%');
            })->orWhereHas('asesi', function($q) use ($searchTerm) { // <-- Ditambahkan search by asesi
                $q->where('nama_lengkap', 'like', '%' . $searchTerm . '%');
            });
        }

        // 5. Terapkan 'orderBy' (Sorting)
        $query->orderBy($sortColumn, $sortDirection);

        // 6. Eksekusi query
        $jadwals = $query->get();
        
        return view('master.schedule.master_schedule', [
            'jadwals' => $jadwals
        ]);
    }

    /**
     * Menampilkan formulir tambah jadwal (add_schedule).
     */
    public function create()
    {
        $skemas = Skema::all();
        $tuks = Tuk::all();
        $asesors = Asesor::all();
        $jenisTuks = JenisTuk::all(); 
        $asesis = Asesi::all(); // <-- WAJIB ADA (untuk dropdown asesi)

        return view('master.schedule.add_schedule', [
            'skemas' => $skemas,
            'tuks' => $tuks,
            'asesors' => $asesors,
            'jenisTuks' => $jenisTuks,
            'asesis' => $asesis // <-- WAJIB ADA
        ]);
    }

    /**
     * Menyimpan jadwal baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi disesuaikan dengan migrasi TERBARU
        $validatedData = $request->validate([
            'id_jenis_tuk' => 'required|exists:jenis_tuk,id_jenis_tuk',
            'id_tuk' => 'required|exists:master_tuk,id_tuk',
            'id_skema' => 'required|exists:skema,id_skema',
            'id_asesor' => 'required|exists:asesor,id_asesor',
            'id_asesi' => 'required|exists:asesi,id_asesi', // <-- WAJIB ADA
            'tanggal_pelaksanaan' => 'required|date', // <-- Sesuai migrasi
            'Status_jadwal' => 'required|string', 
            // Kolom 'sesi', 'tanggal_mulai', 'tanggal_selesai' dihapus
        ]);

        $jadwal = Schedule::create($validatedData);
        $skemaNama = Skema::find($jadwal->id_skema)->nama_skema;

        return redirect()->route('master_schedule')
                         ->with('success', "Jadwal (ID: {$jadwal->id_jadwal}) untuk skema '{$skemaNama}' berhasil ditambahkan!");
    }

    /**
     * Menampilkan formulir edit jadwal (edit_schedule).
     */
    public function edit($id_jadwal)
    {
        $jadwal = Schedule::findOrFail($id_jadwal);
        
        $skemas = Skema::all();
        $tuks = Tuk::all();
        $asesors = Asesor::all();
        $jenisTuks = JenisTuk::all();
        $asesis = Asesi::all(); // <-- WAJIB ADA

        return view('master.schedule.edit_schedule', [
            'jadwal' => $jadwal,
            'skemas' => $skemas,
            'tuks' => $tuks,
            'asesors' => $asesors,
            'jenisTuks' => $jenisTuks,
            'asesis' => $asesis // <-- WAJIB ADA
        ]);
    }

    /**
     * Memperbarui jadwal di database.
     */
    public function update(Request $request, $id_jadwal)
    {
        // Validasi disesuaikan dengan migrasi TERBARU
        $validatedData = $request->validate([
            'id_jenis_tuk' => 'required|exists:jenis_tuk,id_jenis_tuk',
            'id_tuk' => 'required|exists:master_tuk,id_tuk',
            'id_skema' => 'required|exists:skema,id_skema',
            'id_asesor' => 'required|exists:asesor,id_asesor',
            'id_asesi' => 'required|exists:asesi,id_asesi', // <-- WAJIB ADA
            'tanggal_pelaksanaan' => 'required|date', // <-- Sesuai migrasi
            'Status_jadwal' => 'required|string',
        ]);

        $jadwal = Schedule::findOrFail($id_jadwal);
        $jadwal->update($validatedData);
        $skemaNama = $jadwal->skema->nama_skema;

        return redirect()->route('master_schedule')
                         ->with('success', "Jadwal (ID: {$jadwal->id_jadwal}) untuk skema '{$skemaNama}' berhasil diperbarui!");
    }

    /**
     * Menghapus jadwal dari database.
     */
    public function destroy($id_jadwal)
    {
        try {
            $jadwal = Schedule::with('skema')->findOrFail($id_jadwal);
            
            $id = $jadwal->id_jadwal;
            $skemaNama = $jadwal->skema ? $jadwal->skema->nama_skema : 'N/A';

            $jadwal->delete();
            return redirect()->route('master_schedule')
                             ->with('success', "Jadwal (ID: {$id}) untuk skema '{$skemaNama}' berhasil dihapus.");
        
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus: Jadwal ini mungkin terhubung ke data lain.');
        }
    }
}