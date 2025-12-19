<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\MasterTUK;
use App\Models\Skema;
use App\Models\Asesor;
use App\Models\Jadwal;
use App\Models\JenisTUK; 
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function showCalendar()
    {
        $schedules = Jadwal::with(['skema', 'masterTuk', 'asesor', 'jenisTuk'])
                             ->orderBy('tanggal_pelaksanaan', 'asc')
                             ->get();

        return view('Admin.master.schedule.schedule_admin', [
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
            'waktu_mulai', 'waktu_selesai', // [REVISI] Tambah Waktu
            'skema_nama', 'asesor_nama', 'tuk_nama'
        ];
        
        if (!in_array($sortColumn, $allowedColumns)) $sortColumn = 'id_jadwal';
        if (!in_array($sortDirection, ['asc', 'desc'])) $sortDirection = 'asc';

        // 3. Mulai query dengan Eager Loading
        $query = Jadwal::with(['skema', 'masterTuk', 'asesor', 'jenisTuk']);

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
                $q->orWhereHas('masterTuk', function($tq) use ($searchTerm) {
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
            $query->join('masterTuk', 'jadwal.id_tuk', '=', 'masterTuk.id_tuk')
                  ->orderBy('masterTuk.nama_lokasi', $sortDirection);
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
        return view('Admin.master.schedule.master_schedule', [
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
        $tuks = MasterTUK::all();
        $asesors = Asesor::all();
        $jenisTuks = JenisTUK::all(); 

        return view('Admin.master.schedule.add_schedule', [
            'skemas' => $skemas,
            'tuks' => $tuks,
            'asesors' => $asesors,
            'jenisTuks' => $jenisTuks,
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validasi
        $rules = [
            'id_jenis_tuk' => 'required|exists:jenis_tuk,id_jenis_tuk',
            'id_tuk' => 'required|exists:master_tuk,id_tuk', // Pastikan nama tabel benar (master_tuk vs masterTuk)
            'id_skema' => 'required|exists:skema,id_skema',
            'id_asesor' => 'required|exists:asesor,id_asesor',
            'kuota_maksimal' => 'required|integer|min:1',
            'kuota_minimal' => 'nullable|integer|min:1',
            'sesi' => 'required|integer|min:1',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tanggal_pelaksanaan' => 'required|date|after_or_equal:tanggal_selesai',
            'waktu_mulai' => 'required', // Hapus date_format dulu agar lebih fleksibel
            'waktu_selesai' => 'required|after:waktu_mulai',
        ];

        // Custom Error Messages
        $messages = [
            'required' => 'Kolom :attribute wajib diisi.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'integer' => 'Kolom :attribute harus berupa angka.',
            'min' => 'Kolom :attribute minimal :min.',
            'date' => 'Kolom :attribute format tanggal salah.',
            'after_or_equal' => 'Tanggal tidak logis (Harus setelah tanggal sebelumnya).',
            'waktu_selesai.after' => 'Waktu Selesai harus lebih akhir dari Waktu Mulai.',
        ];
        
        // Jalankan Validasi
        $validatedData = $request->validate($rules, $messages);

        // 2. [PENTING] Membersihkan Format Tanggal (Hapus huruf 'T')
        // Input HTML: "2024-12-10T09:00" -> MySQL: "2024-12-10 09:00"
        if ($request->has('tanggal_mulai')) {
            $validatedData['tanggal_mulai'] = str_replace('T', ' ', $request->tanggal_mulai);
        }
        if ($request->has('tanggal_selesai')) {
            $validatedData['tanggal_selesai'] = str_replace('T', ' ', $request->tanggal_selesai);
        }

        // 3. Set Status Default
        $validatedData['Status_jadwal'] = 'Terjadwal';

        // 4. Simpan ke Database dengan Try-Catch
        try {
            $jadwal = Jadwal::create($validatedData);
            
            // Ambil nama skema untuk pesan sukses (Optional)
            $skemaNama = \App\Models\Skema::find($jadwal->id_skema)->nama_skema ?? 'Skema';

            return redirect()->route('admin.master_schedule')
                             ->with('success', "Jadwal untuk '{$skemaNama}' berhasil ditambahkan!");

        } catch (\Exception $e) {
            // Jika gagal, kembalikan ke form dengan pesan error asli
            // Ini akan memberitahu kita KENAPA gagal (misal: kolom kurang, tipe data salah)
            return back()->withInput()->withErrors(['msg' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    public function edit($id_jadwal)
    {
        $jadwal = Jadwal::findOrFail($id_jadwal);
        
        $skemas = Skema::all();
        $tuks = MasterTUK::all();
        $asesors = Asesor::all();
        $jenisTuks = JenisTUK::all();

        return view('Admin.master.schedule.edit_schedule', [
            'jadwal' => $jadwal,
            'skemas' => $skemas,
            'tuks' => $tuks,
            'asesors' => $asesors,
            'jenisTuks' => $jenisTuks,
        ]);
    }

    public function update(Request $request, $id_jadwal)
    {
        // [REVISI] Menambahkan validasi waktu_selesai
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
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai', // [BARU]
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
            'tanggal_pelaksanaan.after_or_equal' => 'Tgl Pelaksanaan harus sama atau setelah Tgl Selesai Pendaftaran.',
            'waktu_selesai.after' => 'Waktu Selesai harus lebih akhir dari Waktu Mulai.', // [BARU]
        ];

        $validatedData = $request->validate($rules, $messages);

        $jadwal = Jadwal::findOrFail($id_jadwal);
        $jadwal->update($validatedData);
        $skemaNama = $jadwal->skema->nama_skema;

        return redirect()->route('admin.master_schedule')
                         ->with('success', "Jadwal (ID: {$jadwal->id_jadwal}) untuk skema '{$skemaNama}' berhasil diperbarui!");
    }

    public function destroy($id_jadwal)
    {
        try {
            $jadwal = Jadwal::with('skema')->findOrFail($id_jadwal);
            $id = $jadwal->id_jadwal;
            $skemaNama = $jadwal->skema ? $jadwal->skema->nama_skema : 'N/A';

            $jadwal->delete();
            return redirect()->route('admin.master_schedule')
                             ->with('success', "Jadwal (ID: {$id}) untuk skema '{$skemaNama}' berhasil dihapus.");
        
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus: Jadwal ini mungkin terhubung ke data lain.');
        }
    }
}