<?php

namespace App\Http\Controllers;

use App\Models\Tuk;
use App\Models\Skema;
use App\Models\Asesor;
use App\Models\Jadwal;
use App\Models\JenisTuk; 
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function showCalendar()
    {
        $schedules = Jadwal::with(['skema', 'tuk', 'asesor', 'jenisTuk'])
                             ->orderBy('tanggal_pelaksanaan', 'asc')
                             ->get();

        return view('master.schedule.schedule_admin', [
            'schedules' => $schedules
        ]);
    }

    public function index(Request $request)
    {
        // 1. Ambil input sort dan direction
        $sortColumn = $request->input('sort', 'id_jadwal');
        $sortDirection = $request->input('direction', 'asc');

        // 2. Daftar kolom yang BOLEH di-sort
        $allowedColumns = [
            'id_jadwal', 'kuota_maksimal', 'sesi', 'tanggal_mulai', 
            'tanggal_selesai', 'tanggal_pelaksanaan',
            'skema_nama', 'asesor_nama', 'tuk_nama'
        ];
        
        if (!in_array($sortColumn, $allowedColumns)) $sortColumn = 'id_jadwal';
        if (!in_array($sortDirection, ['asc', 'desc'])) $sortDirection = 'asc';

        // 3. Mulai query dengan Eager Loading
        $query = Jadwal::with(['skema', 'tuk', 'asesor', 'jenisTuk']);

        // 4. Terapkan 'search' (Filter)
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            
            $query->where(function($q) use ($searchTerm) {
                $q->where('id_jadwal', 'like', '%' . $searchTerm . '%');

                $q->orWhereHas('skema', function($sq) use ($searchTerm) {
                    $sq->where('nama_skema', 'like', '%' . $searchTerm . '%');
                });
                $q->orWhereHas('asesor', function($aq) use ($searchTerm) {
                    $aq->where('nama_lengkap', 'like', '%' . $searchTerm . '%');
                });
                $q->orWhereHas('tuk', function($tq) use ($searchTerm) {
                    $tq->where('nama_lokasi', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        // 5. Terapkan Filter Tambahan (Status & Jenis TUK)
        $filterStatus = $request->input('filter_status');
        if ($filterStatus && in_array($filterStatus, ['Terjadwal', 'Selesai', 'Dibatalkan'])) {
            $query->where('Status_jadwal', $filterStatus);
        }
        
        $filterJenisTuk = $request->input('filter_jenis_tuk');
        if ($filterJenisTuk && is_numeric($filterJenisTuk)) {
            $query->where('id_jenis_tuk', $filterJenisTuk);
        }

        // 6. Logika Sorting Cerdas dengan JOIN
        $query->select('jadwal.*'); 

        if ($sortColumn == 'skema_nama') {
            $query->join('skema', 'jadwal.id_skema', '=', 'skema.id_skema')
                  ->orderBy('skema.nama_skema', $sortDirection);
        } elseif ($sortColumn == 'asesor_nama') {
            $query->join('asesor', 'jadwal.id_asesor', '=', 'asesor.id_asesor')
                  ->orderBy('asesor.nama_lengkap', $sortDirection);
        } elseif ($sortColumn == 'tuk_nama') {
            $query->join('master_tuk', 'jadwal.id_tuk', '=', 'master_tuk.id_tuk')
                  ->orderBy('master_tuk.nama_lokasi', $sortDirection);
        } else {
            $query->orderBy($sortColumn, $sortDirection); 
        }

        // 7. Ambil 'per_page' (Paginate Dinamis)
        $allowedPerpage = [10, 25, 50, 100]; 
        $perPage = $request->input('per_page', 10);
        if (!in_array($perPage, $allowedPerpage)) {
            $perPage = 10;
        }

        // 8. Ganti ->get() menjadi ->paginate()
        $jadwals = $query->paginate($perPage)->onEachSide(0.5);
        
        // 9. (WAJIB) Sertakan semua parameter di link paginasi
        $jadwals->appends($request->only([
            'sort', 'direction', 'search', 'per_page',
            'filter_status', 'filter_jenis_tuk'
        ]));

        
        // 10. Kirim data lengkap ke view
        return view('master.schedule.master_schedule', [
            'jadwals' => $jadwals,
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
            'filterStatus' => $filterStatus,
            'filterJenisTuk' => $filterJenisTuk
        ]);
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
        // [PERBAIKAN] Memisahkan $rules dan $messages
        $rules = [
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
        ];

        $messages = [
            'required' => 'Kolom :attribute wajib diisi.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'integer' => 'Kolom :attribute harus berupa angka.',
            'min' => 'Kolom :attribute minimal :min.',
            'date' => 'Kolom :attribute harus berupa format tanggal yang valid.',
            'date_format' => 'Kolom :attribute harus berformat Jam:Menit (HH:mm).',
            
            'tanggal_selesai.after_or_equal' => 'Tgl Selesai Pendaftaran harus sama atau setelah Tgl Mulai.',
            'tanggal_pelaksanaan.after_or_equal' => 'Tgl Pelaksanaan harus sama atau setelah Tgl Selesai Pendaftaran.'
        ];
        
        $validatedData = $request->validate($rules, $messages);

        $validatedData['Status_jadwal'] = 'Terjadwal';

        $jadwal = Jadwal::create($validatedData);
        $skemaNama = Skema::find($jadwal->id_skema)->nama_skema;

        return redirect()->route('master_schedule')
                         ->with('success', "Jadwal (ID: {$jadwal->id_jadwal}) untuk skema '{$skemaNama}' berhasil ditambahkan!");
    }

    public function edit($id_jadwal)
    {
        $jadwal = Jadwal::findOrFail($id_jadwal);
        
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
        // [PERBAIKAN] Memisahkan $rules dan $messages
        $rules = [
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
        ];

        $messages = [
            'required' => 'Kolom :attribute wajib diisi.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'integer' => 'Kolom :attribute harus berupa angka.',
            'min' => 'Kolom :attribute minimal :min.',
            'date' => 'Kolom :attribute harus berupa format tanggal yang valid.',
            'date_format' => 'Kolom :attribute harus berformat Jam:Menit (HH:mm).',
            'in' => 'Status yang dipilih tidak valid.',

            'tanggal_selesai.after_or_equal' => 'Tgl Selesai Pendaftaran harus sama atau setelah Tgl Mulai.',
            'tanggal_pelaksanaan.after_or_equal' => 'Tgl Pelaksanaan harus sama atau setelah Tgl Selesai Pendaftaran.'
        ];

        $validatedData = $request->validate($rules, $messages);

        $jadwal = Jadwal::findOrFail($id_jadwal);
        $jadwal->update($validatedData);
        $skemaNama = $jadwal->skema->nama_skema;

        return redirect()->route('master_schedule')
                         ->with('success', "Jadwal (ID: {$jadwal->id_jadwal}) untuk skema '{$skemaNama}' berhasil diperbarui!");
    }

    public function destroy($id_jadwal)
    {
        try {
            $jadwal = Jadwal::with('skema')->findOrFail($id_jadwal);
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