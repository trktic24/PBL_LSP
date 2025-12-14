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
        
        // PERBAIKAN: Mengambil input 'status_verifikasi' bukan 'is_verified'
        $statusVerifikasi = $request->input('status_verifikasi');

        $perPage = $request->input('per_page', 10);
        $sortColumn = $request->input('sort', 'id_asesor');
        $sortDirection = $request->input('direction', 'asc');

        $validSortColumns = ['id_asesor', 'nama_lengkap', 'email', 'nomor_regis', 'nik', 'nomor_hp', 'jenis_kelamin', 'pekerjaan'];
        if (!in_array($sortColumn, $validSortColumns)) {
            $sortColumn = 'id_asesor';
        }

        $query = Asesor::with(['skemas'])
            // PERBAIKAN: Menggunakan 'id_user' untuk join
            ->join('users', 'asesor.id_user', '=', 'users.id_user')
            ->select('asesor.*', 'users.email', 'users.username');

        if ($search) {
            $searchTerm = '%' . $search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('asesor.nama_lengkap', 'like', $searchTerm)
                  ->orWhere('asesor.nomor_regis', 'like', $searchTerm)
                  ->orWhere('asesor.nik', 'like', $searchTerm)
                  ->orWhere('asesor.pekerjaan', 'like', $searchTerm)
                  ->orWhere('users.email', 'like', $searchTerm)
                  ->orWhereHas('skemas', function($sq) use ($searchTerm) {
                      $sq->where('nama_skema', 'like', $searchTerm);
                  });
            });
        }
        
        if ($skemaId) {
            $query->whereHas('skemas', function($q) use ($skemaId) {
                $q->where('skema.id_skema', $skemaId);
            });
        }
        if ($jenisKelamin) $query->where('asesor.jenis_kelamin', $jenisKelamin);
        
        // PERBAIKAN: Filter berdasarkan kolom 'status_verifikasi'
        if ($statusVerifikasi) $query->where('asesor.status_verifikasi', $statusVerifikasi);

        $sortColumnName = ($sortColumn == 'email') ? 'users.email' : 'asesor.' . $sortColumn;
        $query->orderBy($sortColumnName, $sortDirection);

        $skemas = Skema::orderBy('nama_skema')->get();
        $asesors = $query->paginate($perPage)->appends($requestData); 
        
        return view('master.asesor.master_asesor', compact('asesors', 'skemas', 'requestData', 'perPage', 'sortColumn', 'sortDirection'));
    }

    // ==========================================================
    // CREATE WIZARD (STEP 1, 2, 3)
    // ==========================================================
    public function createStep1(Request $request)
    {
        $asesor = $request->session()->get('asesor');
        return view('master.asesor.add_asesor1', compact('asesor'));
    }

    public function postStep1(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $asesor = $request->session()->get('asesor', new Asesor());
        $asesor->fill($validatedData);
        $asesor->password = $validatedData['password']; 
        $asesor->username = $validatedData['username'];
        $asesor->email = $validatedData['email'];
        
        $request->session()->put('asesor', $asesor);
        return redirect()->route('add_asesor2');
    }

    public function createStep2(Request $request)
    {
        $asesor = $request->session()->get('asesor');
        $skemas = Skema::all();
        $selectedSkemas = isset($asesor->skema_ids) ? $asesor->skema_ids : [];
        return view('master.asesor.add_asesor2', compact('asesor', 'skemas', 'selectedSkemas'));
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
        $asesor->fill($validatedData);
        $asesor->skema_ids = $validatedData['skema_ids'];
        $asesor->tanggal_lahir = $validatedData['tanggal_lahir'];

        $request->session()->put('asesor', $asesor);
        return redirect()->route('add_asesor3');
    }

    public function createStep3(Request $request)
    {
        $asesor = $request->session()->get('asesor');
        return view('master.asesor.add_asesor3', compact('asesor'));
    }

    public function store(Request $request)
    {
        $asesorData = $request->session()->get('asesor');
        if (!$asesorData) {
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
                'role_id' => 2,
            ]);

            $finalAsesorData = [
                // PERBAIKAN: Key 'id_user'
                'id_user' => $user->id_user, 
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
                // PERBAIKAN: Key 'status_verifikasi' default 'pending'
                'status_verifikasi' => 'pending',
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
    // EDIT WIZARD (STEP 1, 2, 3)
    // ==========================================================
    public function editStep1($id_asesor)
    {
        $asesor = Asesor::with(['user'])->findOrFail($id_asesor);
        return view('master.asesor.edit_asesor1', compact('asesor'));
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

        $user->username = $validatedUserData['nama'];
        $user->email = $validatedUserData['email'];
        if (!empty($validatedUserData['password'])) {
            $user->password = Hash::make($validatedUserData['password']);
        }
        $user->save();
        
        $asesor->nama_lengkap = $validatedUserData['nama'];
        $asesor->save();
        
        return redirect()->route('edit_asesor2', $id_asesor)->with('success-step', 'Data Akun berhasil diperbarui.');
    }

    public function editStep2($id_asesor)
    {
        $asesor = Asesor::with('skemas')->findOrFail($id_asesor);
        $skemas = Skema::all();
        $selectedSkemas = $asesor->skemas->pluck('id_skema')->toArray();
        return view('master.asesor.edit_asesor2', compact('asesor', 'skemas', 'selectedSkemas'));
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

        $asesor->update($validatedAsesorData);
        $skema_ids = $validatedAsesorData['skema_ids'] ?? [];
        $asesor->skemas()->sync($skema_ids);

        return redirect()->route('edit_asesor3', $id_asesor)->with('success-step', 'Data Pribadi & Skema berhasil diperbarui.');
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

        // PERBAIKAN: Mengakses properti 'id_user'
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

        return redirect()->route('master_asesor')->with('success', 'Data Asesor berhasil diperbarui.');
    }

    public function destroy($id_asesor)
    {
        DB::beginTransaction();
        try {
            $asesor = Asesor::with('user')->findOrFail($id_asesor);
            $user = $asesor->user;
            
            // PERBAIKAN: Mengakses properti 'id_user'
            $userId = $asesor->id_user; 

            $asesor->delete(); 
            if ($user) $user->delete(); 

            $uploadPath = 'public/asesor_files/' . $userId;
            Storage::deleteDirectory($uploadPath);

            DB::commit();
            return redirect()->route('master_asesor')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}