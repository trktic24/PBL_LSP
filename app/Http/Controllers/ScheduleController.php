<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Skema;
use App\Models\Tuk;
use App\Models\Asesor;
// use App\Models\Asesi; // Dihapus
use App\Models\JenisTuk; 

class ScheduleController extends Controller
{
    public function showCalendar()
    {
        return view('master.schedule.schedule_admin');
    }

    public function index(Request $request)
    {
        $sortColumn = $request->input('sort', 'id_jadwal');
        $sortDirection = $request->input('direction', 'asc');

        $allowedColumns = [
            'id_jadwal', 'kuota_maksimal', 'sesi', 'tanggal_mulai', 
            'tanggal_selesai', 'tanggal_pelaksanaan', 'Status_jadwal'
        ];
        
        if (!in_array($sortColumn, $allowedColumns)) $sortColumn = 'id_jadwal';
        if (!in_array($sortDirection, ['asc', 'desc'])) $sortDirection = 'asc';

        // Relasi 'asesi' dihapus dari 'with'
        $query = Schedule::with(['skema', 'tuk', 'asesor', 'jenisTuk']);

        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            
            // Pencarian 'orWhereHas' untuk 'asesi' dihapus
            $query->whereHas('skema', function($q) use ($searchTerm) {
                $q->where('nama_skema', 'like', '%' . $searchTerm . '%');
            })->orWhereHas('asesor', function($q) use ($searchTerm) {
                $q->where('nama_lengkap', 'like', '%' . $searchTerm . '%');
            })->orWhereHas('tuk', function($q) use ($searchTerm) {
                $q->where('nama_lokasi', 'like', '%' . $searchTerm . '%');
            });
        }

        $query->orderBy($sortColumn, $sortDirection);
        $jadwals = $query->get();
        
        return view('master.schedule.master_schedule', ['jadwals' => $jadwals]);
    }

    public function create()
    {
        $skemas = Skema::all();
        $tuks = Tuk::all();
        $asesors = Asesor::all();
        $jenisTuks = JenisTuk::all(); 

        return view('master.schedule.add_schedule', [
            'skemas' => $skemas,
            'tuks' => $tuks,
            'asesors' => $asesors,
            'jenisTuks' => $jenisTuks,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_jenis_tuk' => 'required|exists:jenis_tuk,id_jenis_tuk',
            'id_tuk' => 'required|exists:master_tuk,id_tuk',
            'id_skema' => 'required|exists:skema,id_skema',
            'id_asesor' => 'required|exists:asesor,id_asesor',
            'kuota_maksimal' => 'required|integer|min:1',
            'kuota_minimal' => 'nullable|integer|min:1',
            'sesi' => 'required|integer|min:1',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tanggal_pelaksanaan' => 'required|date|after_or_equal:tanggal_selesai',
            'waktu_mulai' => 'required|date_format:H:i',
            'Status_jadwal' => 'required|in:Terjadwal,Selesai,Dibatalkan',
        ]);

        $jadwal = Schedule::create($validatedData);
        $skemaNama = Skema::find($jadwal->id_skema)->nama_skema;

        return redirect()->route('master_schedule')
                         ->with('success', "Jadwal (ID: {$jadwal->id_jadwal}) untuk skema '{$skemaNama}' berhasil ditambahkan!");
    }

    public function edit($id_jadwal)
    {
        $jadwal = Schedule::findOrFail($id_jadwal);
        
        $skemas = Skema::all();
        $tuks = Tuk::all();
        $asesors = Asesor::all();
        $jenisTuks = JenisTuk::all();

        return view('master.schedule.edit_schedule', [
            'jadwal' => $jadwal,
            'skemas' => $skemas,
            'tuks' => $tuks,
            'asesors' => $asesors,
            'jenisTuks' => $jenisTuks,
        ]);
    }

    public function update(Request $request, $id_jadwal)
    {
        $validatedData = $request->validate([
            'id_jenis_tuk' => 'required|exists:jenis_tuk,id_jenis_tuk',
            'id_tuk' => 'required|exists:master_tuk,id_tuk',
            'id_skema' => 'required|exists:skema,id_skema',
            'id_asesor' => 'required|exists:asesor,id_asesor',
            'kuota_maksimal' => 'required|integer|min:1',
            'kuota_minimal' => 'nullable|integer|min:1',
            'sesi' => 'required|integer|min:1',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tanggal_pelaksanaan' => 'required|date|after_or_equal:tanggal_selesai',
            'waktu_mulai' => 'required|date_format:H:i',
            'Status_jadwal' => 'required|in:Terjadwal,Selesai,Dibatalkan',
        ]);

        $jadwal = Schedule::findOrFail($id_jadwal);
        $jadwal->update($validatedData);
        $skemaNama = $jadwal->skema->nama_skema;

        return redirect()->route('master_schedule')
                         ->with('success', "Jadwal (ID: {$jadwal->id_jadwal}) untuk skema '{$skemaNama}' berhasil diperbarui!");
    }

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