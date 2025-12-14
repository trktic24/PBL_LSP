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
    // MASTER DATA: INDEX (LISTING)
    // ==========================================================
    public function index(Request $request)
    {
        $requestData = $request->all();
        $search = $request->input('search');
        
        $skemaId = $request->input('skema_id');
        $jenisKelamin = $request->input('jenis_kelamin');
        $statusVerifikasi = $request->input('status_verifikasi');

        $perPage = $request->input('per_page', 10);
        $sortColumn = $request->input('sort', 'id_asesor');
        $sortDirection = $request->input('direction', 'asc');

        $validSortColumns = ['id_asesor', 'nama_lengkap', 'email', 'nomor_regis', 'nik', 'nomor_hp', 'jenis_kelamin', 'pekerjaan'];
        if (!in_array($sortColumn, $validSortColumns)) {
            $sortColumn = 'id_asesor';
        }

        // LOAD SEMUA SUMBER DATA SKEMA:
        // 1. skema (Profil Utama - One-to-Many)
        // 2. skemas (Lisensi Pivot - Many-to-Many)
        // 3. jadwals.skema (Riwayat Jadwal - via Tabel Jadwal)
        $query = Asesor::with(['skema', 'skemas', 'jadwals.skema']) 
            ->join('users', 'asesor.id_user', '=', 'users.id_user')
            ->select('asesor.*', 'users.email');

        // LOGIKA PENCARIAN (SEARCH)
        if ($search) {
            $searchTerm = '%' . $search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('asesor.nama_lengkap', 'like', $searchTerm)
                  ->orWhere('asesor.nomor_regis', 'like', $searchTerm)
                  ->orWhere('asesor.nik', 'like', $searchTerm)
                  ->orWhere('asesor.pekerjaan', 'like', $searchTerm)
                  ->orWhere('users.email', 'like', $searchTerm)
                  // Cari di Skema Profil Utama
                  ->orWhereHas('skema', function($sq) use ($searchTerm) {
                      $sq->where('nama_skema', 'like', $searchTerm);
                  })
                  // Cari di Skema Lisensi (Pivot)
                  ->orWhereHas('skemas', function($sq) use ($searchTerm) {
                      $sq->where('nama_skema', 'like', $searchTerm);
                  })
                  // Cari di Riwayat Jadwal
                  ->orWhereHas('jadwals.skema', function($jsq) use ($searchTerm) {
                      $jsq->where('nama_skema', 'like', $searchTerm);
                  });
            });
        }
        
        // LOGIKA FILTER SKEMA (dropdown)
        if ($skemaId) {
            $query->where(function($q) use ($skemaId) {
                // Tampilkan jika skema tersebut ada di profil UTAMA
                $q->where('asesor.id_skema', $skemaId)
                // ATAU tampilkan jika skema tersebut ada di daftar LISENSI (Pivot)
                  ->orWhereHas('skemas', function($sq) use ($skemaId) {
                      $sq->where('skema.id_skema', $skemaId);
                  })
                // ATAU tampilkan jika skema tersebut ada di riwayat JADWAL
                  ->orWhereHas('jadwals', function($jq) use ($skemaId) {
                      $jq->where('id_skema', $skemaId);
                  });
            });
        }

        if ($jenisKelamin) $query->where('asesor.jenis_kelamin', $jenisKelamin);
        if ($statusVerifikasi) $query->where('asesor.status_verifikasi', $statusVerifikasi);

        $sortColumnName = ($sortColumn == 'email') ? 'users.email' : 'asesor.' . $sortColumn;
        $query->orderBy($sortColumnName, $sortDirection);

        $skemas = Skema::orderBy('nama_skema')->get();
        $asesors = $query->paginate($perPage)->appends($requestData); 
        
        return view('admin.master.asesor.master_asesor', compact('asesors', 'skemas', 'requestData', 'perPage', 'sortColumn', 'sortDirection'));
    }

    // ==========================================================
    // CREATE WIZARD (STEP 1, 2, 3)
    // ==========================================================
    public function createStep1(Request $request)
    {
        $asesor = $request->session()->get('asesor', new Asesor());
        return view('admin.master.asesor.add_asesor1', compact('asesor'));
    }

    public function postStep1(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $asesor = $request->session()->get('asesor', new Asesor());
        $asesor->fill($validatedData);
        
        $asesor->password = $validatedData['password']; 
        $asesor->email = $validatedData['email'];
        
        // Opsional: Jika ada input nama_lengkap di step 1 view
        if($request->has('nama_lengkap')) { 
            $asesor->nama_lengkap = $request->input('nama_lengkap'); 
        }
        
        $request->session()->put('asesor', $asesor);
        return redirect()->route('admin.add_asesor2');
    }

    public function createStep2(Request $request)
    {
        $asesor = $request->session()->get('asesor');
        if (!$asesor) return redirect()->route('admin.add_asesor1');

        $skemas = Skema::all();
        $selectedSkemas = isset($asesor->skema_ids) ? $asesor->skema_ids : [];
        return view('admin.master.asesor.add_asesor2', compact('asesor', 'skemas', 'selectedSkemas'));
    }

    public function postStep2(Request $request)
    {
        $validatedData = $request->validate([
            'nomor_regis' => 'required|string|unique:asesor,nomor_regis',
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:asesor,nik',
            'skema_ids' => 'required|array', 
            'skema_ids.*' => 'exists:skema,id_skema',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date', 
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])], 
            'kebangsaan' => 'required|string',
            'pekerjaan' => 'required|string',
            'alamat_rumah' => 'required|string', 
            'kabupaten_kota' => 'required|string',
            'provinsi' => 'required|string',
            'kode_pos' => 'nullable|string',
            'nomor_hp' => 'required|string|max:15', 
            'NPWP' => 'required|string|max:25',
            'nama_bank' => 'required|string',
            'norek' => 'required|string|max:20', 
        ]);

        $asesor = $request->session()->get('asesor');
        if (!$asesor) return redirect()->route('admin.add_asesor1');

        $asesor->fill($validatedData);
        // Simpan array ID skema sementara di session untuk pivot nanti
        $asesor->skema_ids = $validatedData['skema_ids'];
        
        // Simpan id_skema (single) untuk kolom tabel asesor (ambil yg pertama sebagai default)
        if (!empty($validatedData['skema_ids'])) {
            $asesor->id_skema = $validatedData['skema_ids'][0];
        }

        $asesor->tanggal_lahir = $validatedData['tanggal_lahir'];

        $request->session()->put('asesor', $asesor);
        return redirect()->route('admin.add_asesor3');
    }

    public function createStep3(Request $request)
    {
        $asesor = $request->session()->get('asesor');
        if (!$asesor) return redirect()->route('admin.add_asesor1');
        return view('admin.master.asesor.add_asesor3', compact('asesor'));
    }

    public function store(Request $request)
    {
        $asesorData = $request->session()->get('asesor');
        if (!$asesorData) {
            return redirect()->route('admin.add_asesor1')->with('error', 'Sesi Anda telah berakhir, silakan mulai lagi.');
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
                'email' => $asesorData->email,
                'password' => Hash::make($asesorData->password),
                'role_id' => 2,
            ]);

            $finalAsesorData = [
                'id_user' => $user->id_user, 
                'id_skema' => $asesorData->id_skema ?? null, 
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
                'status_verifikasi' => 'pending',
            ];

            $uploadPath = 'public/asesor_files/' . $user->id_user;
            foreach ($validatedFiles as $key => $file) {
                $path = $file->store($uploadPath);
                $finalAsesorData[$key] = $path; 
            }

            $asesor = Asesor::create($finalAsesorData);
            
            // Simpan relasi Many-to-Many ke tabel pivot Transaksi_asesor_skema
            if (!empty($asesorData->skema_ids)) {
                $asesor->skemas()->attach($asesorData->skema_ids);
            }

            DB::commit();
            $request->session()->forget('asesor');
            return redirect()->route('admin.master_asesor')->with('success', 'Asesor baru berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan asesor: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ==========================================================
    // EDIT WIZARD (STEP 1, 2, 3)
    // ==========================================================
    public function editStep1($id_asesor)
    {
        $asesor = Asesor::with(['user'])->findOrFail($id_asesor);
        return view('admin.master.asesor.edit_asesor1', compact('asesor'));
    }

    public function updateStep1(Request $request, $id_asesor)
    {
        $asesor = Asesor::with('user')->findOrFail($id_asesor);
        $user = $asesor->user;

        $validatedUserData = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id_user, 'id_user')],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->email = $validatedUserData['email'];
        if (!empty($validatedUserData['password'])) {
            $user->password = Hash::make($validatedUserData['password']);
        }
        $user->save();
        
        $asesor->nama_lengkap = $validatedUserData['nama'];
        $asesor->save();
        
        return redirect()->route('admin.edit_asesor2', $id_asesor)->with('success-step', 'Data Akun berhasil diperbarui.');
    }

    public function editStep2($id_asesor)
    {
        // Load data skema dari tabel pivot
        $asesor = Asesor::with('skemas')->findOrFail($id_asesor);
        $skemas = Skema::all();
        // Ambil array ID skema yang sudah dimiliki
        $selectedSkemas = $asesor->skemas->pluck('id_skema')->toArray();
        return view('admin.master.asesor.edit_asesor2', compact('asesor', 'skemas', 'selectedSkemas'));
    }

    public function updateStep2(Request $request, $id_asesor)
    {
        $asesor = Asesor::findOrFail($id_asesor);
        
        $validatedAsesorData = $request->validate([
            'nomor_regis' => 'required|string',
            'nama_lengkap' => 'required|string|max:255',
            'nik' => ['required', 'string', 'size:16', Rule::unique('asesor')->ignore($id_asesor, 'id_asesor')],
            'skema_ids' => 'nullable|array', 
            'skema_ids.*' => 'exists:skema,id_skema',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date', 
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])], 
            'kebangsaan' => 'required|string',
            'pekerjaan' => 'required|string',
            'alamat_rumah' => 'required|string', 
            'kabupaten_kota' => 'required|string',
            'provinsi' => 'required|string',
            'kode_pos' => 'nullable|string',
            'nomor_hp' => 'required|string|max:15', 
            'NPWP' => 'required|string|max:25',
            'nama_bank' => 'required|string',
            'norek' => 'required|string|max:20', 
        ]);

        $asesor->fill($validatedAsesorData);
        
        // Update kolom id_skema di tabel asesor (ambil yang pertama dari pilihan sebagai default)
        if (!empty($validatedAsesorData['skema_ids'])) {
            $asesor->id_skema = $validatedAsesorData['skema_ids'][0];
        }
        $asesor->save();

        // Update tabel pivot Transaksi_asesor_skema
        $skema_ids = $validatedAsesorData['skema_ids'] ?? [];
        $asesor->skemas()->sync($skema_ids);

        return redirect()->route('admin.edit_asesor3', $id_asesor)->with('success-step', 'Data Pribadi & Skema berhasil diperbarui.');
    }

    public function editStep3($id_asesor)
    {
        $asesor = Asesor::findOrFail($id_asesor);
        return view('admin.master.asesor.edit_asesor3', compact('asesor'));
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

        $uploadPath = 'public/asesor_files/' . $asesor->id_user;
        
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

        return redirect()->route('admin.master_asesor')->with('success', 'Data Asesor berhasil diperbarui.');
    }

    public function destroy($id_asesor)
    {
        DB::beginTransaction();
        try {
            $asesor = Asesor::with('user')->findOrFail($id_asesor);
            
            // Cek dependensi Jadwal sebelum hapus
            if ($asesor->jadwals()->exists()) {
                return redirect()->route('admin.master_asesor')->with('error', 'Gagal menghapus: Asesor ini terdaftar dalam Jadwal. Silakan hapus atau ubah jadwal terkait terlebih dahulu.');
            }

            $user = $asesor->user;
            
            $userId = $asesor->id_user; 

            $asesor->delete(); 
            if ($user) $user->delete(); 

            $uploadPath = 'public/asesor_files/' . $userId;
            Storage::deleteDirectory($uploadPath);

            DB::commit();
            return redirect()->route('admin.master_asesor')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}