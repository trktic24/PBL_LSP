<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\User;
use App\Models\DataPekerjaanAsesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File; // [PENTING] Gunakan File Facade
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AsesiController extends Controller
{
    /**
     * Menampilkan daftar Asesi dengan Paginasi, Sort, dan Filter.
     */
    public function index(Request $request)
    {
        // 1. Ambil input sort dan direction
        $sortColumn = $request->input('sort', 'id_asesi');
        $sortDirection = $request->input('direction', 'asc');

        // 2. Daftar kolom yang BOLEH di-sort
        $allowedColumns = [
            'id_asesi', 'nama_lengkap', 'nik', 'tempat_lahir', 
            'tanggal_lahir', 'jenis_kelamin', 'pendidikan', 'pekerjaan',
            'kabupaten_kota', 'provinsi', 'email', 'nomor_hp'
        ];
        
        if (!in_array($sortColumn, $allowedColumns)) $sortColumn = 'id_asesi';
        if (!in_array($sortDirection, ['asc', 'desc'])) $sortDirection = 'asc';

        // 3. Mulai query dengan Eager Loading
        $query = Asesi::with('user');

        // 4. Terapkan 'search' (Filter)
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('id_asesi', 'like', '%' . $searchTerm . '%')
                ->orWhere('nama_lengkap', 'like', '%' . $searchTerm . '%')
                ->orWhere('nik', 'like', '%' . $searchTerm . '%')
                ->orWhere('nomor_hp', 'like', '%' . $searchTerm . '%')
                ->orWhere('jenis_kelamin', 'like', '%' . $searchTerm . '%')
                ->orWhere('pendidikan', 'like', '%' . $searchTerm . '%')
                ->orWhere('pekerjaan', 'like', '%' . $searchTerm . '%')
                ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                    $userQuery->where('email', 'like', '%' . $searchTerm . '%');
                });
            });
        }
        
        // 5. Terapkan Filter Tambahan (Gender)
        $filterGender = $request->input('filter_gender');
        if ($filterGender && in_array($filterGender, ['Laki-laki', 'Perempuan'])) {
            $query->where('jenis_kelamin', $filterGender);
        }

        // 6. Logika Sorting (Termasuk relasi)
        $query->select('asesi.*'); 

        if ($sortColumn == 'email') {
            $query->join('users', 'asesi.id_user', '=', 'users.id_user')
                  ->orderBy('users.email', $sortDirection);
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
        $asesis = $query->paginate($perPage)->onEachSide(0.5);
        
        // 9. Sertakan semua parameter di link paginasi
        $asesis->appends($request->only([
            'sort', 'direction', 'search', 'per_page',
            'filter_gender'
        ]));
        
        // 10. Kirim data lengkap ke view
        return view('Admin.master.asesi.master_asesi', [
            'asesis' => $asesis,
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
            'filterGender' => $filterGender
        ]);
    }

    public function create()
    {
        return view('Admin.master.asesi.add_asesi');
    }

    /**
     * Menyimpan Asesi baru, Akun User, DAN Data Pekerjaan.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // 1. Data User
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            
            // 2. Data Asesi
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:asesi,nik',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kebangsaan' => 'nullable|string|max:100',
            'pendidikan' => 'required|string|max:255',
            'pekerjaan' => 'required|string|max:255', 
            'alamat_rumah' => 'required|string',
            'kode_pos' => 'nullable|string|max:10',
            'kabupaten_kota' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:16',
            'tanda_tangan' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',

            // 3. Data Pekerjaan Asesi
            'nama_institusi_pekerjaan' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'no_telepon_institusi' => 'required|string|max:16',
            'kode_pos_institusi' => 'nullable|string|max:10',
            'alamat_institusi' => 'required|string',
        ], [
            'email.unique' => 'Email ini sudah terdaftar.',
            'nik.unique' => 'NIK ini sudah terdaftar.',
            'nik.size' => 'NIK harus 16 digit.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        DB::beginTransaction();
        try {
            $ttdPath = null;
            
            // [PERBAIKAN] Upload ke private_docs/ttd_asesi
            if ($request->hasFile('tanda_tangan')) {
                $file = $request->file('tanda_tangan');
                $filename = time() . '_' . $file->getClientOriginalName();
                
                // Gunakan disk private_docs + Simpan FULL PATH (ttd_asesi/filename.jpg)
                $ttdPath = Storage::disk('private_docs')->putFileAs('ttd_asesi', $file, $filename);
            }

            // A. Buat User
            $user = User::create([
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role_id' => 3, 
                'username' => $validatedData['nik'],
            ]);

            // B. Buat Asesi
            $asesi = Asesi::create([
                'id_user' => $user->id_user, 
                'nama_lengkap' => $validatedData['nama_lengkap'],
                'nik' => $validatedData['nik'],
                'tempat_lahir' => $validatedData['tempat_lahir'],
                'tanggal_lahir' => $validatedData['tanggal_lahir'],
                'jenis_kelamin' => $validatedData['jenis_kelamin'],
                'kebangsaan' => $validatedData['kebangsaan'],
                'pendidikan' => $validatedData['pendidikan'],
                'pekerjaan' => $validatedData['pekerjaan'],
                'alamat_rumah' => $validatedData['alamat_rumah'],
                'kode_pos' => $validatedData['kode_pos'],
                'kabupaten_kota' => $validatedData['kabupaten_kota'],
                'provinsi' => $validatedData['provinsi'],
                'nomor_hp' => $validatedData['nomor_hp'],
                'tanda_tangan' => $ttdPath,
            ]);

            // C. Buat Data Pekerjaan
            DataPekerjaanAsesi::create([
                'id_asesi' => $asesi->id_asesi,
                'nama_institusi_pekerjaan' => $validatedData['nama_institusi_pekerjaan'],
                'jabatan' => $validatedData['jabatan'],
                'no_telepon_institusi' => $validatedData['no_telepon_institusi'],
                'kode_pos_institusi' => $validatedData['kode_pos_institusi'],
                'alamat_institusi' => $validatedData['alamat_institusi'],
            ]);

            DB::commit();

            return redirect()->route('admin.master_asesi')->with('success', "Asesi '{$asesi->nama_lengkap}' (ID: {$asesi->id_asesi}) berhasil ditambahkan.");

        } catch (\Exception $e) {
            DB::rollBack();
            // Hapus file jika DB gagal dan file sudah terupload
            if ($ttdPath && File::exists(public_path($ttdPath))) {
                File::delete(public_path($ttdPath));
            }
            return back()->with('error', 'Gagal menyimpan Asesi: '. $e->getMessage())->withInput();
        }
    }

    public function edit($id_asesi)
    {
        $asesi = Asesi::with('user')->findOrFail($id_asesi);
        $dataPekerjaanSingle = $asesi->dataPekerjaan()->latest()->first();
        return view('Admin.master.asesi.edit_asesi', [
        'asesi' => $asesi,
        'dataPekerjaan' => $dataPekerjaanSingle // Kirim HANYA model tunggal
        ]);
    }

    public function update(Request $request, $id_asesi)
    {
        $asesi = Asesi::with(['user', 'dataPekerjaan'])->findOrFail($id_asesi);
        $user = $asesi->user;

        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
            'password' => ['nullable', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:asesi,nik,' . $asesi->id_asesi . ',id_asesi',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kebangsaan' => 'nullable|string|max:100',
            'pendidikan' => 'required|string|max:255',
            'pekerjaan' => 'required|string|max:255',
            'alamat_rumah' => 'required|string',
            'kode_pos' => 'nullable|string|max:10',
            'kabupaten_kota' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:16',
            'tanda_tangan' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
            'nama_institusi_pekerjaan' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'no_telepon_institusi' => 'required|string|max:16',
            'kode_pos_institusi' => 'nullable|string|max:10',
            'alamat_institusi' => 'required|string',
        ],[
            'email.unique' => 'Email ini sudah terdaftar.',
            'nik.unique' => 'NIK ini sudah terdaftar.',
            'nik.size' => 'NIK harus 16 digit.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        DB::beginTransaction();
        try {
            $ttdPath = $asesi->tanda_tangan;

            // [PERBAIKAN] Update file ke private_docs/ttd_asesi
            if ($request->hasFile('tanda_tangan')) {
                // Hapus file lama jika ada
                if ($asesi->tanda_tangan && Storage::disk('private_docs')->exists($asesi->tanda_tangan)) {
                    Storage::disk('private_docs')->delete($asesi->tanda_tangan);
                }
                
                $file = $request->file('tanda_tangan');
                $filename = time() . '_' . $file->getClientOriginalName();
                // Simpan FULL PATH
                $ttdPath = Storage::disk('private_docs')->putFileAs('ttd_asesi', $file, $filename);
            }

            // Update User
            $user->email = $validatedData['email'];
            if ($request->filled('password')) {
                $user->password = Hash::make($validatedData['password']);
            }
            $user->save();

            // Update Asesi
            $asesi->update([
                'nama_lengkap' => $validatedData['nama_lengkap'],
                'nik' => $validatedData['nik'],
                'tempat_lahir' => $validatedData['tempat_lahir'],
                'tanggal_lahir' => $validatedData['tanggal_lahir'],
                'jenis_kelamin' => $validatedData['jenis_kelamin'],
                'kebangsaan' => $validatedData['kebangsaan'],
                'pendidikan' => $validatedData['pendidikan'],
                'pekerjaan' => $validatedData['pekerjaan'],
                'alamat_rumah' => $validatedData['alamat_rumah'],
                'kode_pos' => $validatedData['kode_pos'],
                'kabupaten_kota' => $validatedData['kabupaten_kota'],
                'provinsi' => $validatedData['provinsi'],
                'nomor_hp' => $validatedData['nomor_hp'],
                'tanda_tangan' => $ttdPath,
            ]);

            // Update Data Pekerjaan
            $asesi->dataPekerjaan()->updateOrCreate(
                ['id_asesi' => $asesi->id_asesi],
                [
                    'nama_institusi_pekerjaan' => $validatedData['nama_institusi_pekerjaan'],
                    'jabatan' => $validatedData['jabatan'],
                    'no_telepon_institusi' => $validatedData['no_telepon_institusi'],
                    'kode_pos_institusi' => $validatedData['kode_pos_institusi'],
                    'alamat_institusi' => $validatedData['alamat_institusi'],
                ]
            );

            DB::commit();

            return redirect()->route('admin.master_asesi')->with('success', "Asesi '{$asesi->nama_lengkap}' (ID: {$asesi->id_asesi}) berhasil diperbarui.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui Asesi: '. $e->getMessage())->withInput();
        }
    }

    public function destroy($id_asesi)
    {
        DB::beginTransaction();
        try {
            $asesi = Asesi::with('user')->findOrFail($id_asesi);
            $nama = $asesi->nama_lengkap;
            $id = $asesi->id_asesi;
            $user = $asesi->user; 

            // [PERBAIKAN] Hapus file fisik dari private_docs
            if ($asesi->tanda_tangan && Storage::disk('private_docs')->exists($asesi->tanda_tangan)) {
                Storage::disk('private_docs')->delete($asesi->tanda_tangan);
            }
            
            if ($user) {
                $user->delete();
            } else {
                $asesi->delete();
            }
            
            DB::commit();

            return redirect()->route('admin.master_asesi')->with('success', "Asesi '{$nama}' (ID: {$id}) dan akun user terkait berhasil dihapus.");
        
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus Asesi: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan Template Master View untuk Laporan Asesi (Admin)
     */
    public function adminShow($id_skema)
    {
        $skema = \App\Models\Skema::findOrFail($id_skema);

        $query = \App\Models\DataSertifikasiAsesi::with([
            'asesi.dataPekerjaan',
            'jadwal.masterTuk',
            'jadwal.asesor',
            'responApl02Ia01',
            'responBuktiAk01',
            'lembarJawabIa05',
            'komentarAk05'
        ])->whereHas('jadwal', function($q) use ($id_skema) {
            $q->where('id_skema', $id_skema);
        });

        if (request('search')) {
            $search = request('search');
            $query->whereHas('asesi', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%");
            });
        }

        $pendaftar = $query->paginate(request('per_page', 10))->withQueryString();

        $user = auth()->user();
        $asesor = new \App\Models\Asesor();
        $asesor->id_asesor = 0;
        $asesor->nama_lengkap = $user ? $user->name : 'Administrator';
        $asesor->pas_foto = $user ? $user->profile_photo_path : null;
        $asesor->status_verifikasi = 'approved';
        $asesor->setRelation('skemas', collect());
        $asesor->setRelation('jadwals', collect());
        $asesor->setRelation('skema', null);

        $jadwal = new \App\Models\Jadwal([
            'tanggal_pelaksanaan' => now(),
            'waktu_mulai' => '08:00',
        ]);
        $jadwal->setRelation('skema', $skema);
        $jadwal->setRelation('masterTuk', new \App\Models\MasterTUK(['nama_lokasi' => 'Semua TUK (Filter Skema)']));

        return view('Admin.master.skema.daftar_asesi', [
            'pendaftar' => $pendaftar,
            'asesor' => $asesor,
            'jadwal' => $jadwal,
            'isMasterView' => true,
            'sortColumn' => request('sort', 'nama_lengkap'),
            'sortDirection' => request('direction', 'asc'),
            'perPage' => request('per_page', 10),
            'targetRoute' => 'admin.laporan.asesi.view',
            'buttonLabel' => 'Laporan',
        ]);
    }
}