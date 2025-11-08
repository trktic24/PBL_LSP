<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Asesor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Pastikan Log di-import
use Illuminate\Validation\Rule; 
use Illuminate\Support\Facades\Storage; 

class AsesorController extends Controller
{
    // ==========================================================
    // MENAMPILKAN HALAMAN (GET)
    // ==========================================================

    public function createStep1(Request $request)
    {
        // Ambil data lama dari session jika ada (untuk tombol 'back')
        $asesor = $request->session()->get('asesor');
        return view('master.asesor.add_asesor1', compact('asesor'));
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
        // PERBAIKAN: Disesuaikan dengan form add_asesor1
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:4|confirmed',
            'id_skema' => 'required|exists:skema,id_skema',
        ]);

        // Inisialisasi session jika belum ada
        $asesor = $request->session()->get('asesor', new Asesor());
        $asesor->fill($validatedData);
        $request->session()->put('asesor', $asesor);

        return redirect()->route('add_asesor2');
    }

    /**
     * Simpan data dari Step 2 ke Session
     */
    public function postStep2(Request $request)
    {
        // PERBAIKAN: Validasi disesuaikan dengan database dan form
        $validatedData = $request->validate([
            'nomor_regis' => 'required|string|unique:asesor,nomor_regis',
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:asesor,nik',
            'tempat_lahir' => 'required|string',
            
            // Validasi 3 field tanggal
            'tanggal_lahir' => 'required|integer',
            'bulan_lahir' => 'required|integer',
            'tahun_lahir' => 'required|integer',

            // Validasi 1 atau 0
            'jenis_kelamin' => 'required|boolean', 
            
            'kebangsaan' => 'required|string',
            'pekerjaan' => 'required|string', // Field ini wajib ada
            'alamat' => 'required|string', 
            'kab_kota' => 'required|string',
            'provinsi' => 'required|string',
            'kode_pos' => 'nullable|string:5',
            'no_hp' => 'required|string|max:15', 
            'npwp' => 'required|string|max:25',
            'nama_bank' => 'required|string',
            'no_rekening' => 'required|string|max:20', 
        ]);

        // Gabungkan tanggal lahir
        $tanggal_lahir_full = $validatedData['tahun_lahir'] . '-' . 
                              $validatedData['bulan_lahir'] . '-' . 
                              $validatedData['tanggal_lahir'];

        $asesor = $request->session()->get('asesor');
        $asesor->fill($validatedData);
        
        // Timpa/Set data dengan nama kolom DB yang benar
        $asesor->tanggal_lahir = $tanggal_lahir_full;
        $asesor->alamat_rumah = $validatedData['alamat'];
        $asesor->kabupaten_kota = $validatedData['kab_kota'];
        $asesor->nomor_hp = $validatedData['no_hp'];
        $asesor->norek = $validatedData['no_rekening'];
        $asesor->NPWP = $validatedData['npwp'];
        // 'pekerjaan' dan 'nama_lengkap' sudah terisi otomatis oleh fill()

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
        if (!$asesorData) {
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

            // 4. PERBAIKAN: Buat array data yang BERSIH untuk tabel 'asesor'
            $finalAsesorData = [
                'id_user' => $user->id_user,
                'id_skema' => $asesorData->id_skema,
                'nomor_regis' => $asesorData->nomor_regis,
                'nama_lengkap' => $asesorData->nama_lengkap,
                'nik' => $asesorData->nik,
                'tanggal_lahir' => $asesorData->tanggal_lahir, // Sudah Y-m-d dari Step 2
                'tempat_lahir' => $asesorData->tempat_lahir,
                'jenis_kelamin' => $asesorData->jenis_kelamin, // Sudah 1/0 dari Step 2
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
                $finalAsesorData[$key] = $path; // $key sudah benar (ktp, pas_foto, dll)
            }

            // 6. PERBAIKAN: Simpan Asesor ke database menggunakan Asesor::create
            Asesor::create($finalAsesorData);

            // 7. Jika sukses, hapus session dan commit
            DB::commit();
            $request->session()->forget('asesor');

            return redirect()->route('master_asesor')->with('success', 'Asesor baru berhasil ditambahkan!');

        } catch (\Exception $e) {
            // 8. Jika gagal, batalkan semua dan kembali
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
        // Ambil data Asesor DAN data User-nya
        $asesor = Asesor::with('user')->findOrFail($id_asesor);
        return view('master.asesor.edit_asesor1', compact('asesor'));
    }

    /**
     * Menyimpan data dari Edit Step 1
     */
    public function updateStep1(Request $request, $id_asesor)
    {
        $asesor = Asesor::with('user')->findOrFail($id_asesor);
        $user = $asesor->user;

        // Validasi data
        $validatedUserData = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                // Pastikan email unik, KECUALI untuk user ini
                Rule::unique('users')->ignore($user->id_user, 'id_user'),
            ],
            'password' => 'nullable|string|min:4|confirmed',
        ]);

        // Update data User
        $user->username = $validatedUserData['nama'];
        $user->email = $validatedUserData['email'];
        
        // Hanya update password jika diisi
        if (!empty($validatedUserData['password'])) {
            $user->password = Hash::make($validatedUserData['password']);
        }
        $user->save();
        
        // Update juga nama_lengkap di tabel asesor agar konsisten
        $asesor->nama_lengkap = $validatedUserData['nama'];
        $asesor->save();

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
            
            // Validasi Tanggal (3 field integer)
            'tanggal_lahir' => 'required|integer', 
            'bulan_lahir' => 'required|integer',   
            'tahun_lahir' => 'required|integer',   
            
            // PERBAIKAN: Validasi Jenis Kelamin (boolean untuk 1/0)
            'jenis_kelamin' => 'required|boolean', 
            
            'kebangsaan' => 'required|string',
            
            // PERBAIKAN: Tambahkan validasi 'pekerjaan'
            'pekerjaan' => 'required|string',
            
            // Validasi disesuaikan dengan nama field di blade
            'alamat' => 'required|string', 
            'kab_kota' => 'required|string',
            'provinsi' => 'required|string',
            'kode_pos' => 'nullable|string:5',
            'no_hp' => 'required|string|max:15', 
            'npwp' => 'required|string|max:25',
            'nama_bank' => 'required|string',
            'no_rekening' => 'required|string|max:20', 
        ]);

        // Gabungkan tanggal lahir
        $tanggal_lahir_full = $validatedAsesorData['tahun_lahir'] . '-' . 
                              $validatedAsesorData['bulan_lahir'] . '-' . 
                              $validatedAsesorData['tanggal_lahir'];

        // Update data Asesor (Pemetaan disamakan dengan database)
        $asesor->update([
            'nomor_regis' => $validatedAsesorData['nomor_regis'],
            'nama_lengkap' => $validatedAsesorData['nama_lengkap'],
            'nik' => $validatedAsesorData['nik'],
            'tempat_lahir' => $validatedAsesorData['tempat_lahir'],
            'tanggal_lahir' => $tanggal_lahir_full,
            'jenis_kelamin' => $validatedAsesorData['jenis_kelamin'],
            'kebangsaan' => $validatedAsesorData['kebangsaan'],
            
            // PERBAIKAN: Tambahkan 'pekerjaan'
            'pekerjaan' => $validatedAsesorData['pekerjaan'],
            
            // Pemetaan nama kolom database (kiri) dari nama field blade (kanan)
            'alamat_rumah' => $validatedAsesorData['alamat'],
            'kabupaten_kota' => $validatedAsesorData['kab_kota'],
            'provinsi' => $validatedAsesorData['provinsi'],
            'kode_pos' => $validatedAsesorData['kode_pos'],
            'nomor_hp' => $validatedAsesorData['no_hp'],
            'NPWP' => $validatedAsesorData['npwp'],
            'nama_bank' => $validatedAsesorData['nama_bank'],
            'norek' => $validatedAsesorData['no_rekening'],
        ]);

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
        
        // PERBAIKAN: Validasi file disesuaikan dengan nama kolom DB
        $validatedFiles = $request->validate([
            'ktp' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'pas_foto' => 'nullable|file|mimes:jpg,png|max:5120',
            'NPWP_foto' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'rekening_foto' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'CV' => 'nullable|file|mimes:pdf|max:5120',
            'ijazah' => 'nullable|file|mimes:pdf|max:5120',
            'sertifikat_asesor' => 'nullable|file|mimes:pdf|max:5120',
            'sertifikasi_kompetensi' => 'nullable|file|mimes:pdf|max:5120',
            // 'tanda_tangan' opsional, bisa ditambahkan jika perlu diedit
            // 'tanda_tangan' => 'nullable|file|mimes:png|max:5120', 
        ]);

        $uploadPath = 'public/asesor_files/' . $asesor->id_user;
        
        // Loop menggunakan $validatedFiles yang sudah benar
        foreach ($validatedFiles as $key => $file) {
            if ($request->hasFile($key)) {
                
                // Hapus file lama jika ada
                if ($asesor->$key) {
                    Storage::delete($asesor->$key);
                }
                
                // Simpan file baru
                $path = $file->store($uploadPath);
                $asesor->$key = $path; // Update path di model
            }
        }
        
        $asesor->save(); // Simpan perubahan path file

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
            
            // Panggil delete(). Model Event di Asesor.php akan
            // menangani penghapusan User dan file secara otomatis.
            $asesor->delete();

            return redirect()->route('master_asesor')
                             ->with('success', 'Asesor berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Gagal menghapus asesor: ' . $e->getMessage());
            // PERBAIKAN: Pesan error agar lebih jelas
            return back()->with('error', 'Terjadi kesalahan saat menghapus asesor: ' . $e->getMessage());
        }
    }
}