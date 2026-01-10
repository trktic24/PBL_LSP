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

    public function store(Request $request, $id_jadwal)
    {
        // 1. Bersihkan Format Tanggal DULU (Hapus 'T' HTML5)
        $input = $request->all();
        if ($request->filled('tanggal_mulai')) {
            $input['tanggal_mulai'] = str_replace('T', ' ', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $input['tanggal_selesai'] = str_replace('T', ' ', $request->tanggal_selesai);
        }
        $request->replace($input);

        // 2. Definisi Rules
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
            
            // [LOGIC 1] Cek TANGGAL saja (Agar bisa hari yang sama)
            'tanggal_pelaksanaan' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    // Ambil 10 karakter pertama (YYYY-MM-DD)
                    $dateSelesai = substr($request->tanggal_selesai, 0, 10);
                    $datePelaksanaan = substr($value, 0, 10);
                    
                    // Validasi: Tgl Pelaksanaan tidak boleh sebelum Tgl Selesai
                    if ($datePelaksanaan < $dateSelesai) {
                        $fail('Tgl Pelaksanaan harus sama atau setelah Tgl Selesai Pendaftaran.');
                    }
                },
            ],

            // [LOGIC 2] Cek WAKTU (Validasi detail Jam)
            'waktu_mulai' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $selesaiRaw = $request->tanggal_selesai;       // Y-m-d H:i:s
                    $tglPelaksanaan = $request->tanggal_pelaksanaan; // Y-m-d
                    
                    if ($selesaiRaw && $tglPelaksanaan) {
                        // Gabungkan Tgl Pelaksanaan + Jam Mulai
                        // Contoh: "2024-01-25 09:00"
                        $startExecution = \Carbon\Carbon::parse($tglPelaksanaan . ' ' . $value);
                        $endRegistration = \Carbon\Carbon::parse($selesaiRaw);

                        // Validasi: Waktu Mulai tidak boleh kurang dari Selesai Pendaftaran
                        if ($startExecution->lt($endRegistration)) {
                            $fail('Waktu Mulai tidak boleh lebih awal dari Waktu Selesai Pendaftaran.');
                        }
                    }
                },
            ],

            'waktu_selesai' => 'required|after:waktu_mulai',
        ];

        // 3. Pesan Error
        $messages = [
            'required' => 'Kolom :attribute wajib diisi.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'integer' => 'Kolom :attribute harus berupa angka.',
            'min' => 'Kolom :attribute minimal :min.',
            'date' => 'Format tanggal salah.',
            'after_or_equal' => 'Tanggal tidak logis.',
            'waktu_selesai.after' => 'Waktu Selesai harus lebih akhir dari Waktu Mulai.',
        ];
        
        $validatedData = $request->validate($rules, $messages);
        $validatedData['Status_jadwal'] = 'Terjadwal';

        try {
            $jadwal = Jadwal::create($validatedData);
            $skemaNama = \App\Models\Skema::find($jadwal->id_skema)->nama_skema ?? 'Skema';

            return redirect()->route('admin.master_schedule')
                             ->with('success', "Jadwal (ID: {$id_jadwal}) untuk '{$skemaNama}' berhasil ditambahkan!");

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['msg' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id_jadwal)
    {
        // 1. Bersihkan Format Tanggal
        $input = $request->all();
        if ($request->filled('tanggal_mulai')) {
            $input['tanggal_mulai'] = str_replace('T', ' ', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $input['tanggal_selesai'] = str_replace('T', ' ', $request->tanggal_selesai);
        }
        $request->replace($input);

        // 2. Definisi Rules (Sama dengan Store)
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
            
            'tanggal_pelaksanaan' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    $dateSelesai = substr($request->tanggal_selesai, 0, 10);
                    $datePelaksanaan = substr($value, 0, 10);
                    
                    if ($datePelaksanaan < $dateSelesai) {
                        $fail('Tgl Pelaksanaan harus sama atau setelah Tgl Selesai Pendaftaran.');
                    }
                },
            ],

            'waktu_mulai' => [
                'required',
                // 'date_format:H:i', // Opsional
                function ($attribute, $value, $fail) use ($request) {
                    $selesaiRaw = $request->tanggal_selesai;
                    $tglPelaksanaan = $request->tanggal_pelaksanaan;
                    
                    if ($selesaiRaw && $tglPelaksanaan) {
                        $startExecution = \Carbon\Carbon::parse($tglPelaksanaan . ' ' . $value);
                        $endRegistration = \Carbon\Carbon::parse($selesaiRaw);

                        if ($startExecution->lt($endRegistration)) {
                            $fail('Waktu Mulai tidak boleh lebih awal dari Waktu Selesai Pendaftaran.');
                        }
                    }
                },
            ],

            'waktu_selesai' => 'required|after:waktu_mulai',
            'Status_jadwal' => 'required|in:Terjadwal,Selesai,Dibatalkan',
        ];

        $messages = [
            'required' => 'Kolom :attribute wajib diisi.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'integer' => 'Kolom :attribute harus berupa angka.',
            'min' => 'Kolom :attribute minimal :min.',
            'date' => 'Format tanggal salah.',
            'in' => 'Status tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tgl Selesai Pendaftaran harus setelah Tgl Mulai.',
            'waktu_selesai.after' => 'Waktu Selesai harus lebih akhir dari Waktu Mulai.',
        ];

        $validatedData = $request->validate($rules, $messages);

        $jadwal = Jadwal::findOrFail($id_jadwal);
        $jadwal->update($validatedData);
        $skemaNama = $jadwal->skema->nama_skema;

        return redirect()->route('admin.master_schedule')
                         ->with('success', "Jadwal (ID: {$id_jadwal}) untuk '{$skemaNama}' berhasil diperbarui!");
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