<?php

namespace App\Http\Controllers;

use App\Models\Asesi;
use App\Models\User;
use App\Models\DataPekerjaanAsesi; // [PENTING] Import Model Baru
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        return view('master.asesi.master_asesi', [
            'asesis' => $asesis,
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
            'filterGender' => $filterGender
        ]);
    }

    public function create()
    {
        return view('master.asesi.add_asesi');
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
            'pekerjaan' => 'required|string|max:255', // Pekerjaan umum
            'alamat_rumah' => 'required|string',
            'kode_pos' => 'nullable|string|max:10',
            'kabupaten_kota' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:16',
            'tanda_tangan' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',

            // 3. Data Pekerjaan Asesi (Detail) - [BARU]
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
            if ($request->hasFile('tanda_tangan')) {
                $ttdPath = $request->file('tanda_tangan')->store('public/ttd_asesi');
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

            // C. Buat Data Pekerjaan [BARU]
            DataPekerjaanAsesi::create([
                'id_asesi' => $asesi->id_asesi,
                'nama_institusi_pekerjaan' => $validatedData['nama_institusi_pekerjaan'],
                'jabatan' => $validatedData['jabatan'],
                'no_telepon_institusi' => $validatedData['no_telepon_institusi'],
                'kode_pos_institusi' => $validatedData['kode_pos_institusi'],
                'alamat_institusi' => $validatedData['alamat_institusi'],
            ]);

            DB::commit();

            return redirect()->route('master_asesi')->with('success', "Asesi '{$asesi->nama_lengkap}' (ID: {$asesi->id_asesi}) berhasil ditambahkan.");

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($ttdPath)) {
                Storage::delete($ttdPath);
            }
            return back()->with('error', 'Gagal menyimpan Asesi: '. $e->getMessage())->withInput();
        }
    }

    public function edit($id_asesi)
    {
        // Load relasi user DAN dataPekerjaan
        $asesi = Asesi::with(['user', 'dataPekerjaan'])->findOrFail($id_asesi);
        return view('master.asesi.edit_asesi', ['asesi' => $asesi]);
    }

    /**
     * Memperbarui data Asesi, User, dan Data Pekerjaan.
     */
    public function update(Request $request, $id_asesi)
    {
        $asesi = Asesi::with(['user', 'dataPekerjaan'])->findOrFail($id_asesi);
        $user = $asesi->user;

        $validatedData = $request->validate([
            // 1. Validasi User
            'email' => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
            'password' => ['nullable', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            
            // 2. Validasi Asesi
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

            // 3. Validasi Data Pekerjaan [BARU]
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
            // A. Update File TTD
            $ttdPath = $asesi->tanda_tangan;
            if ($request->hasFile('tanda_tangan')) {
                if ($asesi->tanda_tangan) {
                    Storage::delete(str_replace('public/', '', $asesi->tanda_tangan));
                }
                $ttdPath = $request->file('tanda_tangan')->store('public/ttd_asesi');
            }

            // B. Update User
            $user->email = $validatedData['email'];
            if ($request->filled('password')) {
                $user->password = Hash::make($validatedData['password']);
            }
            $user->save();

            // C. Update Asesi
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

            // D. Update Data Pekerjaan [BARU]
            // Gunakan updateOrCreate untuk menangani jika data lama belum ada
            $asesi->dataPekerjaan()->updateOrCreate(
                ['id_asesi' => $asesi->id_asesi], // Kondisi pencarian
                [
                    'nama_institusi_pekerjaan' => $validatedData['nama_institusi_pekerjaan'],
                    'jabatan' => $validatedData['jabatan'],
                    'no_telepon_institusi' => $validatedData['no_telepon_institusi'],
                    'kode_pos_institusi' => $validatedData['kode_pos_institusi'],
                    'alamat_institusi' => $validatedData['alamat_institusi'],
                ]
            );

            DB::commit();

            return redirect()->route('master_asesi')->with('success', "Asesi '{$asesi->nama_lengkap}' (ID: {$asesi->id_asesi}) berhasil diperbarui.");

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

            if ($asesi->tanda_tangan) {
                Storage::delete(str_replace('public/', '', $asesi->tanda_tangan));
            }
            
            // Hapus User akan meng-cascade delete Asesi dan DataPekerjaanAsesi
            if ($user) {
                $user->delete();
            } else {
                $asesi->delete();
            }
            
            DB::commit();

            return redirect()->route('master_asesi')->with('success', "Asesi '{$nama}' (ID: {$id}) dan akun user terkait berhasil dihapus.");
        
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus Asesi: ' . $e->getMessage());
        }
    }
}