<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Asesor;
use App\Models\Skema; // Ini SANGAT PENTING
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
     * PERBAIKAN: Menampilkan halaman master asesor (daftar asesor)
     */
    public function index(Request $request)
    {
        // PERBAIKAN: Ambil semua parameter filter
        $requestData = $request->only(['search', 'skema_id', 'jenis_kelamin', 'is_verified']);

        // Query dasar dengan relasi yang dibutuhkan
        $query = Asesor::with(['user', 'skemas']);

        // Logika pencarian
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_lengkap', 'like', $searchTerm)
                  ->orWhere('nomor_regis', 'like', $searchTerm)
                  ->orWhere('nik', 'like', $searchTerm)
                  ->orWhere('nomor_hp', 'like', $searchTerm)
                  ->orWhere('pekerjaan', 'like', $searchTerm)
                  ->orWhere('kabupaten_kota', 'like', $searchTerm)
                  ->orWhere('provinsi', 'like', $searchTerm)
                  // Mencari di relasi user
                  ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                      $userQuery->where('email', 'like', $searchTerm);
                  })
                  // Mencari di relasi 'skemas'
                  ->orWhereHas('skemas', function($skemaQuery) use ($searchTerm) {
                      $skemaQuery->where('nama_skema', 'like', $searchTerm);
                  });
            });
        }
        
        // Logika filter skema (sudah ada)
        if ($request->filled('skema_id')) {
            $query->whereHas('skemas', function($skemaQuery) use ($request) {
                $skemaQuery->where('skema.id_skema', $request->skema_id);
            });
        }

        // PERBAIKAN BARU: Logika filter Jenis Kelamin
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        // PERBAIKAN BARU: Logika filter Status Verifikasi
        if ($request->filled('is_verified')) { 
            $query->where('is_verified', $request->is_verified);
        }

        // Ambil semua skema untuk dropdown filter
        $skemas = Skema::orderBy('nama_skema')->get();
        
        // Paginate hasil DAN tambahkan parameter filter ke link pagination
        $asesors = $query->paginate(10)->appends($requestData); 
        
        // Kirim data ke view
        return view('master.asesor.master_asesor', compact('asesors', 'skemas', 'requestData'));
    }


    public function createStep1(Request $request)
// ... (sisa kode controller Anda tetap sama) ...
// ... (pastikan Anda menyalin bagian 'index' yang baru di atas) ...
// ... (sisa file tidak berubah) ...
    {
        // Ambil data lama dari session jika ada (untuk tombol 'back')
        $asesor = $request->session()->get('asesor');
        // PERBAIKAN: Ambil semua skema untuk dropdown
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

    /**
     * Simpan data dari Step 1 ke Session
     */
    public function postStep1(Request $request)
    {
        // PERBAIKAN: Validasi skema dikembalikan sebagai array
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:4|confirmed',
            'skema_ids' => 'required|array', // Harus array
            'skema_ids.*' => 'exists:skema,id_skema', // Setiap item harus ada di tabel skema
        ]);

        // Inisialisasi session jika belum ada
        $asesor = $request->session()->get('asesor', new Asesor());
        $asesor->fill($validatedData);
        
        // PERBAIKAN: Simpan skema_ids ke session
        $asesor->skema_ids = $validatedData['skema_ids'];
        
        $request->session()->put('asesor', $asesor);

        return redirect()->route('add_asesor2');
    }

    /**
     * Simpan data dari Step 2 ke Session
     */
    public function postStep2(Request $request)
    {
        // Validasi ini sudah benar dan sesuai dengan migrasi asesor
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

        // Gabungkan tanggal lahir
        $tanggal_lahir_full = $validatedData['tahun_lahir'] . '-' . 
                              $validatedData['bulan_lahir'] . '-' . 
                              $validatedData['tanggal_lahir'];

        $asesor = $request->session()->get('asesor');
        $asesor->fill($validatedData);
        
        $asesor->tanggal_lahir = $tanggal_lahir_full;
        
        $request->session()->put('asesor', $asesor);

        return redirect()->route('add_asesor3');
    }

    /**
     * Simpan data dari Step 3 dan masukkan ke Database
     */
     public function store(Request $request)
    {
        // 1. Ambil data Asesor (sebagai OBJEK) dari session
        $asesorData = $request->session()->get('asesor');

        // Pastikan data session ada
        if (!$asesorData || !isset($asesorData->skema_ids)) {
            return redirect()->route('add_asesor1')->with('error', 'Sesi Anda telah berakhir, silakan mulai lagi.');
        }

        // 2. Validasi file (nama field HARUS SAMA dengan kolom DB)
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

        // Gunakan Transaksi Database untuk keamanan
        DB::beginTransaction();
        try {
            // 3. Buat User baru
            $user = User::create([
                'username' => $asesorData->username,
                'email' => $asesorData->email,
                'password' => Hash::make($asesorData->password),
                'role_id' => 2, // Asumsi '2' adalah ID untuk role Asesor
            ]);

            // 4. Buat array data yang BERSIH untuk tabel 'asesor'
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

            // 5. Upload file dan tambahkan path ke array
            $uploadPath = 'public/asesor_files/' . $user->id_user;
            foreach ($validatedFiles as $key => $file) {
                $path = $file->store($uploadPath);
                $finalAsesorData[$key] = $path; 
            }

            // 6. Simpan Asesor ke database
            $asesor = Asesor::create($finalAsesorData);
            
            // 7. PERBAIKAN: Simpan relasi skema ke tabel pivot
            if (!empty($asesorData->skema_ids)) {
                $asesor->skemas()->attach($asesorData->skema_ids);
            }

            // 8. Jika sukses, hapus session dan commit
            DB::commit();
            $request->session()->forget('asesor');

            return redirect()->route('master_asesor')->with('success', 'Asesor baru berhasil ditambahkan!');

        } catch (\Exception $e) {
            // 9. Jika gagal, batalkan semua dan kembali
            DB::rollBack();
            Log::error('Gagal menyimpan asesor: ' . $e->getMessage());
            // Kembalikan error agar bisa dilihat di blade
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ==========================================================
    // LOGIKA UNTUK EDIT ASESOR
    // ==========================================================

    /**
     * Menampilkan halaman Edit Step 1 (Informasi Akun)
     */
    public function editStep1($id_asesor)
    {
        // Ambil data Asesor DAN relasi 'user' dan 'skemas'
        $asesor = Asesor::with(['user', 'skemas'])->findOrFail($id_asesor);
        
        // PERBAIKAN: Ambil semua skema untuk V
        $skemas = Skema::all(); 
        
        return view('master.asesor.edit_asesor1', compact('asesor', 'skemas'));
    }

    /**
     * Menyimpan data dari Edit Step 1
     */
    public function updateStep1(Request $request, $id_asesor)
    {
        $asesor = Asesor::with('user')->findOrFail($id_asesor);
        $user = $asesor->user;

        // Validasi data (skema dikembalikan)
        $validatedUserData = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id_user, 'id_user'),
            ],
            'password' => 'nullable|string|min:4|confirmed',
            'skema_ids' => 'nullable|array', // Boleh null/kosong, tapi harus array
            'skema_ids.*' => 'exists:skema,id_skema',
        ]);

        // Update data User
        $user->username = $validatedUserData['nama'];
        $user->email = $validatedUserData['email'];
        
        if (!empty($validatedUserData['password'])) {
            $user->password = Hash::make($validatedUserData['password']);
        }
        $user->save();
        
        // Update juga nama_lengkap di tabel asesor agar konsisten
        $asesor->nama_lengkap = $validatedUserData['nama'];
        $asesor->save();

        // PERBAIKAN: Gunakan sync() untuk update relasi di tabel pivot
        // 'sync' akan menghapus relasi lama dan menambah relasi baru
        $skema_ids = $validatedUserData['skema_ids'] ?? []; // Default array kosong jika tidak ada
        $asesor->skemas()->sync($skema_ids);
        
        // Redirect ke step 2
        return redirect()->route('edit_asesor2', $id_asesor)
                         ->with('success-step', 'Data Akun berhasil diperbarui.');
    }

    /**
     * Menampilkan halaman Edit Step 2 (Data Pribadi)
     */
    public function editStep2($id_asesor)
    {
        $asesor = Asesor::findOrFail($id_asesor);
        return view('master.asesor.edit_asesor2', compact('asesor'));
    }

    /**
     * Menyimpan data dari Edit Step 2
     */
    public function updateStep2(Request $request, $id_asesor)
    {
        $asesor = Asesor::findOrFail($id_asesor);
        
        // Validasi data pribadi (disesuaikan dengan database)
        $validatedAsesorData = $request->validate([
            'nomor_regis' => 'required|string',
            'nama_lengkap' => 'required|string|max:255',
            'nik' => [
                'required',
                'string',
                'size:16',
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

        // Gabungkan tanggal lahir
        $tanggal_lahir_full = $validatedAsesorData['tahun_lahir'] . '-' . 
                              $validatedAsesorData['bulan_lahir'] . '-' . 
                              $validatedAsesorData['tanggal_lahir'];

        $updateData = $validatedAsesorData;
        $updateData['tanggal_lahir'] = $tanggal_lahir_full; // Timpa dengan tanggal yang sudah digabung
        
        // Hapus field yang tidak ada di tabel asesor
        unset($updateData['bulan_lahir']);
        unset($updateData['tahun_lahir']);
        
        $asesor->update($updateData);

        return redirect()->route('edit_asesor3', $id_asesor)
                         ->with('success-step', 'Data Pribadi berhasil diperbarui.');
    }

    /**
     * Menampilkan halaman Edit Step 3 (Kelengkapan Dokumen)
     */
    public function editStep3($id_asesor)
    {
        $asesor = Asesor::findOrFail($id_asesor);
        return view('master.asesor.edit_asesor3', compact('asesor'));
    }

    /**
     * Menyimpan data dari Edit Step 3 (File)
     */
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
    // <!-- FUNGSI BARU UNTUK HAPUS ASESOR -->
    // ==========================================================
    /**
     * Hapus data Asesor dari database.
     */
    public function destroy($id_asesor)
    {
        try {
            $asesor = Asesor::findOrFail($id_asesor);
            
            $asesor->delete();

            return redirect()->route('master_asesor')
                             ->with('success', 'Asesor berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Gagal menghapus asesor: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus asesor: ' . $e->getMessage());
        }
    }

    // ==========================================================
    // <!-- FUNGSI BARU UNTUK MELIHAT PROFIL ASESOR -->
    // ==========================================================
    /**
     * Menampilkan halaman profil publik seorang Asesor.
     */
    public function showProfile($id_asesor)
    {
        // Ambil data Asesor DAN data User-nya
        $asesor = Asesor::with('user')->findOrFail($id_asesor);
        
        // Kirim data ke view
        return view('profile_asesor.asesor_profile_settings', compact('asesor'));
    }

    // ==========================================================
    // <!-- FUNGSI BARU UNTUK MELIHAT BUKTI KELENGKAPAN -->
    // ==========================================================
    /**
     * Menampilkan halaman bukti kelengkapan seorang Asesor.
     */
    public function showBukti($id_asesor)
    {
        // Ambil data Asesor
        $asesor = Asesor::findOrFail($id_asesor);

        // BARU: Pindahkan logika array $documents dari view ke controller
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
        
        // Kirim data ke view
        // DIRUBAH: Tambahkan 'documents' ke compact
        return view('profile_asesor.asesor_profile_bukti', compact('asesor', 'documents'));
    }
}