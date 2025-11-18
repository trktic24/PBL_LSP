<?php

namespace App\Http\Controllers;

use App\Models\Asesi;
use App\Models\User;
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
        
        // 5. [PERUBAHAN] Terapkan Filter Tambahan (Gender)
        $filterGender = $request->input('filter_gender');
        if ($filterGender && in_array($filterGender, ['Laki-laki', 'Perempuan'])) {
            $query->where('jenis_kelamin', $filterGender);
        }
        // === [AKHIR PERUBAHAN] ===


        // 6. Logika Sorting (Termasuk relasi)
        $query->select('asesi.*'); // WAJIB untuk 'select' saat join

        if ($sortColumn == 'email') {
            $query->join('users', 'asesi.id_user', '=', 'users.id_user')
                  ->orderBy('users.email', $sortDirection);
        } else {
            // Urutkan berdasarkan kolom di tabel 'asesi'
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
        
        // 9. [PERUBAHAN] (WAJIB) Sertakan semua parameter di link paginasi
        $asesis->appends($request->only([
            'sort', 'direction', 'search', 'per_page',
            'filter_gender' // <-- DITAMBAHKAN
        ]));

        
        // 10. [PERUBAHAN] Kirim data lengkap ke view
        return view('master.asesi.master_asesi', [
            'asesis' => $asesis,
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
            'filterGender' => $filterGender // <-- DITAMBAHKAN
        ]);
    }

    /**
     * Menampilkan formulir tambah Asesi.
     */
    public function create()
    {
        return view('master.asesi.add_asesi');
    }

    /**
     * Menyimpan Asesi baru dan Akun User baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // Data User
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            
            // Data Asesi
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
        ], [
            // [SARAN] Tambahkan pesan kustom B. Indonesia
            'email.unique' => 'Email ini sudah terdaftar.',
            'nik.unique' => 'NIK ini sudah terdaftar.',
            'nik.size' => 'NIK harus 16 digit.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        DB::beginTransaction();
        try {
            $ttdPath = null;
            if ($request->hasFile('tanda_tangan')) {
                // Simpan file dan dapatkan path (cth: public/ttd_asesi/namafile.png)
                $ttdPath = $request->file('tanda_tangan')->store('public/ttd_asesi');
            }

            // Buat User baru (role_id 3 = Asesi)
            $user = User::create([
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role_id' => 3, 
                'username' => $validatedData['nik'], // [ASUMSI] Username adalah NIK
            ]);

            // Buat Asesi baru
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

    /**
     * Menampilkan formulir edit Asesi.
     */
    public function edit($id_asesi)
    {
        $asesi = Asesi::with('user')->findOrFail($id_asesi);
        return view('master.asesi.edit_asesi', ['asesi' => $asesi]);
    }

    /**
     * Memperbarui data Asesi dan Akun User (dengan Transaction).
     */
    public function update(Request $request, $id_asesi)
    {
        $asesi = Asesi::with('user')->findOrFail($id_asesi);
        $user = $asesi->user;

        $validatedData = $request->validate([
            // Validasi data User
            'email' => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
            'password' => ['nullable', 'confirmed', Password::min(8)->mixedCase()->numbers()], // Password boleh kosong
            
            // Validasi data Asesi
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
        ],[
            // [SARAN] Tambahkan pesan kustom B. Indonesia
            'email.unique' => 'Email ini sudah terdaftar.',
            'nik.unique' => 'NIK ini sudah terdaftar.',
            'nik.size' => 'NIK harus 16 digit.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        DB::beginTransaction();
        try {
            $ttdPath = $asesi->tanda_tangan;
            if ($request->hasFile('tanda_tangan')) {
                if ($asesi->tanda_tangan) {
                    Storage::delete(str_replace('public/', '', $asesi->tanda_tangan));
                }
                $ttdPath = $request->file('tanda_tangan')->store('public/ttd_asesi');
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

            DB::commit();

            return redirect()->route('master_asesi')->with('success', "Asesi '{$asesi->nama_lengkap}' (ID: {$asesi->id_asesi}) berhasil diperbarui.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui Asesi: '. $e->getMessage())->withInput();
        }
    }

    /**
     * Menghapus Asesi DAN Akun User terkait.
     */
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