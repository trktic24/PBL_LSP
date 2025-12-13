<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Asesor;
use App\Models\Skema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use Illuminate\Validation\Rule; 
use Illuminate\Support\Facades\Storage; 

class AsesorController extends Controller
{
    // ==========================================================
    // MENAMPILKAN HALAMAN (GET)
    // ==========================================================

    /**
     * DIROMBAK TOTAL: Menampilkan halaman master asesor
     * Disesuaikan agar sama dengan fungsionalitas master_asesi
     * (Sorting, Per Page, Filter, Search, Join)
     */
    public function index(Request $request)
    {
        // 1. Ambil semua parameter dari URL
        $requestData = $request->all();
        $search = $request->input('search');
        
        // Filter spesifik Asesor
        $skemaId = $request->input('skema_id');
        $jenisKelamin = $request->input('jenis_kelamin');
        $isVerified = $request->input('is_verified');

        // Parameter dari Master Asesi (Sorting & Pagination)
        $perPage = $request->input('per_page', 10); // Default 10
        $sortColumn = $request->input('sort', 'id_asesor'); // Default sort by id
        $sortDirection = $request->input('direction', 'asc'); // Default asc

        // 2. Daftar kolom yang valid untuk di-sort
        $validSortColumns = [
            'id_asesor', 
            'nama_lengkap', 
            'email', // Dari tabel 'users'
            'nomor_regis', 
            'nik', 
            'nomor_hp', 
            'jenis_kelamin', 
            'pekerjaan'
        ];

        // 3. Keamanan: Pastikan kolom sort valid
        if (!in_array($sortColumn, $validSortColumns)) {
            $sortColumn = 'id_asesor';
        }

        // 4. Query dasar dengan JOIN ke tabel users
        // Ini diperlukan agar kita bisa sorting berdasarkan 'email'
        $query = Asesor::with(['skemas']) // Tetap 'with' skemas untuk list di view
            ->join('users', 'asesor.id_user', '=', 'users.id_user')
            ->select('asesor.*', 'users.email', 'users.username'); // Ambil kolom yg diperlukan

        // 5. Logika Pencarian (Diadaptasi untuk JOIN)
        if ($search) {
            $searchTerm = '%' . $search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('asesor.nama_lengkap', 'like', $searchTerm)
                  ->orWhere('asesor.nomor_regis', 'like', $searchTerm)
                  ->orWhere('asesor.nik', 'like', $searchTerm)
                  ->orWhere('asesor.nomor_hp', 'like', $searchTerm)
                  ->orWhere('asesor.pekerjaan', 'like', $searchTerm)
                  ->orWhere('asesor.kabupaten_kota', 'like', $searchTerm)
                  ->orWhere('asesor.provinsi', 'like', $searchTerm)
                  // Mencari di tabel 'users' yang sudah di-JOIN
                  ->orWhere('users.email', 'like', $searchTerm)
                  ->orWhere('users.username', 'like', $searchTerm)
                  // Mencari di relasi 'skemas' (tetap pakai whereHas)
                  ->orWhereHas('skemas', function($skemaQuery) use ($searchTerm) {
                      $skemaQuery->where('nama_skema', 'like', $searchTerm);
                  });
            });
        }
        
        // 6. Logika Filter (Filter lanjutan dari Master Asesor)
        if ($skemaId) {
            $query->whereHas('skemas', function($skemaQuery) use ($skemaId) {
                $skemaQuery->where('skema.id_skema', $skemaId);
            });
        }
        if ($jenisKelamin) {
            $query->where('asesor.jenis_kelamin', $jenisKelamin);
        }
        if ($isVerified !== null && $isVerified !== '') {
            $query->where('asesor.is_verified', $isVerified);
        }

        // 7. Logika Sorting (Baru)
        // Sesuaikan nama kolom jika sortingnya di tabel 'users'
        $sortColumnName = ($sortColumn == 'email') ? 'users.email' : 'asesor.' . $sortColumn;
        $query->orderBy($sortColumnName, $sortDirection);

        // 8. Ambil semua skema untuk dropdown filter
        $skemas = Skema::orderBy('nama_skema')->get();
        
        // 9. Paginate hasil
        $asesors = $query->paginate($perPage)->appends($requestData); 
        
        // 10. Kirim semua data ke view
        return view('master.asesor.master_asesor', compact(
            'asesors', 
            'skemas', 
            'requestData', 
            'perPage', 
            'sortColumn', 
            'sortDirection'
        ));
    }


    public function createStep1(Request $request)
    {
        // Ambil data lama dari session jika ada (untuk tombol 'back')
        $asesor = $request->session()->get('asesor');
        $skemas = Skema::all();
        return view('master.asesor.add_asesor1', compact('asesor', 'skemas'));
    }

    public function createStep2(Request $request)
    {
        $asesor = $request->session()->get('asesor');
        return view('master.asesor.add_asesor2', compact('asesor'));
    }

    public function createStep3(Request $request)
    {
        $asesor = $request->session()->get('asesor');
        return view('master.asesor.add_asesor3', compact('asesor'));
    }

    // ==========================================================
    // MEMPROSES & MENYIMPAN DATA (POST)
    // ==========================================================

    public function postStep1(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:4|confirmed',
            'skema_ids' => 'required|array', 
            'skema_ids.*' => 'exists:skema,id_skema', 
        ]);

        $asesor = $request->session()->get('asesor', new Asesor());
        $asesor->fill($validatedData);
        $asesor->skema_ids = $validatedData['skema_ids'];
        $request->session()->put('asesor', $asesor);

        return redirect()->route('add_asesor2');
    }

    public function postStep2(Request $request)
    {
        $validatedData = $request->validate([
            'nomor_regis' => 'required|string|unique:asesor,nomor_regis',
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:asesor,nik',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|integer',
            'bulan_lahir' => 'required|integer',
            'tahun_lahir' => 'required|integer',
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])], 
            'kebangsaan' => 'required|string',
            'pekerjaan' => 'required|string',
            'alamat_rumah' => 'required|string', 
            'kabupaten_kota' => 'required|string',
            'provinsi' => 'required|string',
            'kode_pos' => 'nullable|string:5',
            'nomor_hp' => 'required|string|max:15', 
            'NPWP' => 'required|string|max:25',
            'nama_bank' => 'required|string',
            'norek' => 'required|string|max:20', 
        ]);

        $tanggal_lahir_full = $validatedData['tahun_lahir'] . '-' . 
                              $validatedData['bulan_lahir'] . '-' . 
                              $validatedData['tanggal_lahir'];

        $asesor = $request->session()->get('asesor');
        $asesor->fill($validatedData);
        $asesor->tanggal_lahir = $tanggal_lahir_full;
        $request->session()->put('asesor', $asesor);

        return redirect()->route('add_asesor3');
    }

     public function store(Request $request)
    {
        $asesorData = $request->session()->get('asesor');
        if (!$asesorData || !isset($asesorData->skema_ids)) {
            return redirect()->route('add_asesor1')->with('error', 'Sesi Anda telah berakhir, silakan mulai lagi.');
        }

        $validatedFiles = $request->validate([
            'ktp' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'pas_foto' => 'required|file|mimes:jpg,png|max:2048',
            'NPWP_foto' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'rekening_foto' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'CV' => 'required|file|mimes:pdf|max:2048',
            'ijazah' => 'required|file|mimes:pdf|max:2048',
            'sertifikat_asesor' => 'required|file|mimes:pdf|max:2048',
            'sertifikasi_kompetensi' => 'required|file|mimes:pdf|max:2048',
            'tanda_tangan' => 'required|file|mimes:png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'username' => $asesorData->username,
                'email' => $asesorData->email,
                'password' => Hash::make($asesorData->password),
                'role_id' => 2, // Asumsi '2' adalah ID untuk role Asesor
            ]);

            $finalAsesorData = [
                'user_id' => $user->id_user, 
                'nomor_regis' => $asesorData->nomor_regis,
                'nama_lengkap' => $asesorData->nama_lengkap,
                'nik' => $asesorData->nik,
                'tanggal_lahir' => $asesorData->tanggal_lahir, 
                'tempat_lahir' => $asesorData->tempat_lahir,
                'jenis_kelamin' => $asesorData->jenis_kelamin, 
                'kebangsaan' => $asesorData->kebangsaan,
                'pekerjaan' => $asesorData->pekerjaan,
                'alamat_rumah' => $asesorData->alamat_rumah,
                'kode_pos' => $asesorData->kode_pos,
                'kabupaten_kota' => $asesorData->kabupaten_kota,
                'provinsi' => $asesorData->provinsi,
                'nomor_hp' => $asesorData->nomor_hp,
                'NPWP' => $asesorData->NPWP,
                'nama_bank' => $asesorData->nama_bank,
                'norek' => $asesorData->norek,
                'is_verified' => false,
            ];

            $uploadPath = 'public/asesor_files/' . $user->id_user;
            foreach ($validatedFiles as $key => $file) {
                $path = $file->store($uploadPath);
                $finalAsesorData[$key] = $path; 
            }

            $asesor = Asesor::create($finalAsesorData);
            
            if (!empty($asesorData->skema_ids)) {
                $asesor->skemas()->attach($asesorData->skema_ids);
            }

            DB::commit();
            $request->session()->forget('asesor');

            return redirect()->route('master_asesor')->with('success', 'Asesor baru berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan asesor: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ==========================================================
    // LOGIKA UNTUK EDIT ASESOR (Tidak Berubah)
    // ==========================================================

    public function editStep1($id_asesor)
    {
        $asesor = Asesor::with(['user', 'skemas'])->findOrFail($id_asesor);
        $skemas = Skema::all(); 
        return view('master.asesor.edit_asesor1', compact('asesor', 'skemas'));
    }

    public function updateStep1(Request $request, $id_asesor)
    {
        $asesor = Asesor::with('user')->findOrFail($id_asesor);
        $user = $asesor->user;

        $validatedUserData = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id_user, 'id_user'),
            ],
            'password' => 'nullable|string|min:4|confirmed',
            'skema_ids' => 'nullable|array', 
            'skema_ids.*' => 'exists:skema,id_skema',
        ]);

        $user->username = $validatedUserData['nama'];
        $user->email = $validatedUserData['email'];
        if (!empty($validatedUserData['password'])) {
            $user->password = Hash::make($validatedUserData['password']);
        }
        $user->save();
        
        $asesor->nama_lengkap = $validatedUserData['nama'];
        $asesor->save();

        $skema_ids = $validatedUserData['skema_ids'] ?? []; 
        $asesor->skemas()->sync($skema_ids);
        
        return redirect()->route('edit_asesor2', $id_asesor)
                         ->with('success-step', 'Data Akun berhasil diperbarui.');
    }

    public function editStep2($id_asesor)
    {
        $asesor = Asesor::findOrFail($id_asesor);
        return view('master.asesor.edit_asesor2', compact('asesor'));
    }

    public function updateStep2(Request $request, $id_asesor)
    {
        $asesor = Asesor::findOrFail($id_asesor);
        
        $validatedAsesorData = $request->validate([
            'nomor_regis' => 'required|string',
            'nama_lengkap' => 'required|string|max:255',
            'nik' => [
                'required', 'string', 'size:16',
                Rule::unique('asesor')->ignore($id_asesor, 'id_asesor'),
            ],
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|integer', 
            'bulan_lahir' => 'required|integer',   
            'tahun_lahir' => 'required|integer',   
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])], 
            'kebangsaan' => 'required|string',
            'pekerjaan' => 'required|string',
            'alamat_rumah' => 'required|string', 
            'kabupaten_kota' => 'required|string',
            'provinsi' => 'required|string',
            'kode_pos' => 'nullable|string:5',
            'nomor_hp' => 'required|string|max:15', 
            'NPWP' => 'required|string|max:25',
            'nama_bank' => 'required|string',
            'norek' => 'required|string|max:20', 
        ]);

        $tanggal_lahir_full = $validatedAsesorData['tahun_lahir'] . '-' . 
                              $validatedAsesorData['bulan_lahir'] . '-' . 
                              $validatedAsesorData['tanggal_lahir'];

        $updateData = $validatedAsesorData;
        $updateData['tanggal_lahir'] = $tanggal_lahir_full; 
        unset($updateData['bulan_lahir']);
        unset($updateData['tahun_lahir']);
        
        $asesor->update($updateData);

        return redirect()->route('edit_asesor3', $id_asesor)
                         ->with('success-step', 'Data Pribadi berhasil diperbarui.');
    }

    public function editStep3($id_asesor)
    {
        $asesor = Asesor::findOrFail($id_asesor);
        return view('master.asesor.edit_asesor3', compact('asesor'));
    }

    public function updateStep3(Request $request, $id_asesor)
    {
        $asesor = Asesor::findOrFail($id_asesor);
        
        $validatedFiles = $request->validate([
            'ktp' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'pas_foto' => 'nullable|file|mimes:jpg,png|max:5120',
            'NPWP_foto' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'rekening_foto' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'CV' => 'nullable|file|mimes:pdf|max:5120',
            'ijazah' => 'nullable|file|mimes:pdf|max:5120',
            'sertifikat_asesor' => 'nullable|file|mimes:pdf|max:5120',
            'sertifikasi_kompetensi' => 'nullable|file|mimes:pdf|max:5120',
            'tanda_tangan' => 'nullable|file|mimes:png|max:5120', 
        ]);

        $uploadPath = 'public/asesor_files/' . $asesor->user_id;
        
        foreach ($validatedFiles as $key => $file) {
            if ($request->hasFile($key)) {
                if ($asesor->$key) {
                    Storage::delete($asesor->$key);
                }
                $path = $file->store($uploadPath);
                $asesor->$key = $path; 
            }
        }
        $asesor->save(); 

        return redirect()->route('master_asesor')
                         ->with('success', 'Data Asesor berhasil diperbarui.');
    }

    // ==========================================================
    // HAPUS & LIHAT PROFIL (Tidak Berubah - Menggunakan Logika perbaikan dari Sesi 1)
    // ==========================================================
    
    public function destroy($id_asesor)
    {
        DB::beginTransaction();
        try {
            $asesor = Asesor::with('user')->findOrFail($id_asesor);
            $user = $asesor->user;
            $userId = $asesor->user_id; 

            $asesor->delete(); 
            
            if ($user) {
                $user->delete(); 
            }

            $uploadPath = 'public/asesor_files/' . $userId;
            Storage::deleteDirectory($uploadPath);

            DB::commit();
            return redirect()->route('master_asesor')
                             ->with('success', 'Asesor, Akun User, dan semua file terkait berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus asesor: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus asesor: ' . $e->getMessage());
        }
    }

    public function showProfile($id_asesor)
    {
        $asesor = Asesor::with('user')->findOrFail($id_asesor);
        return view('profile_asesor.asesor_profile_settings', compact('asesor'));
    }

    public function showBukti($id_asesor)
    {
        $asesor = Asesor::findOrFail($id_asesor);
        $documents = [
            ['key' => 'ktp', 'title' => 'KTP', 'subtitle' => 'Kartu Tanda Penduduk', 'file_path' => $asesor->ktp],
            ['key' => 'pas_foto', 'title' => 'Foto', 'subtitle' => 'Pas Foto 3x4', 'file_path' => $asesor->pas_foto],
            ['key' => 'NPWP_foto', 'title' => 'NPWP', 'subtitle' => 'Kartu NPWP', 'file_path' => $asesor->NPWP_foto],
            ['key' => 'rekening_foto', 'title' => 'Rekening', 'subtitle' => 'Buku Rekening Bank', 'file_path' => $asesor->rekening_foto],
            ['key' => 'CV', 'title' => 'Curiculum Vitae (CV)', 'subtitle' => 'CV terbaru', 'file_path' => $asesor->CV],
            ['key' => 'ijazah', 'title' => 'Ijazah Pendidikan', 'subtitle' => 'Ijazah pendidikan terakhir', 'file_path' => $asesor->ijazah],
            ['key' => 'sertifikat_asesor', 'title' => 'Sertifikat Asesor Kompetensi', 'subtitle' => 'Sertifikat kompetensi sebagai asesor', 'file_path' => $asesor->sertifikat_asesor],
            ['key' => 'sertifikasi_kompetensi', 'title' => 'Sertifikasi Kompetensi', 'subtitle' => 'Sertifikat teknis/pendukung', 'file_path' => $asesor->sertifikasi_kompetensi],
        ];
        
        return view('profile_asesor.asesor_profile_bukti', compact('asesor', 'documents'));
    }
}