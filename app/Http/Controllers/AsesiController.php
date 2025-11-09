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
     * Menampilkan halaman daftar Asesi.
     */
    public function index(Request $request)
    {
        $query = Asesi::with('user'); 
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_lengkap', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nik', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                      $userQuery->where('email', 'like', '%' . $searchTerm . '%');
                  });
            });
        }
        $asesis = $query->get();
        return view('master.asesi.master_asesi', ['asesis' => $asesis]);
    }

    /**
     * Menampilkan formulir tambah Asesi (satu halaman).
     */
    public function create()
    {
        return view('master.asesi.add_asesi');
    }

    /**
     * Menyimpan Asesi baru dan Akun User baru dari satu form.
     */
    public function store(Request $request)
    {
        // Validasi semua data sekaligus
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
        ]);

        DB::beginTransaction();
        try {
            $ttdPath = null;
            if ($request->hasFile('tanda_tangan')) {
                $ttdPath = $request->file('tanda_tangan')->store('public/ttd_asesi');
            }

            // Buat User baru (role_id 3 = Asesi)
            $user = User::create([
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role_id' => 3, 
            ]);

            // Buat Asesi baru
            Asesi::create([
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

            return redirect()->route('master_asesi')->with('success', "Asesi '{$validatedData['nama_lengkap']}' berhasil ditambahkan.");

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($ttdPath)) {
                Storage::delete($ttdPath);
            }
            return back()->with('error', 'Gagal menyimpan Asesi: ' . $e->getMessage());
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
     * Memperbarui data Asesi dan Akun User.
     */
    public function update(Request $request, $id_asesi)
    {
        $asesi = Asesi::with('user')->findOrFail($id_asesi);
        $user = $asesi->user;

        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
            'password' => ['nullable', 'confirmed', Password::min(8)->mixedCase()->numbers()], // Password boleh kosong
            
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

            $user->email = $validatedData['email'];
            if ($request->filled('password')) {
                $user->password = Hash::make($validatedData['password']);
            }
            $user->save();

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

            return redirect()->route('master_asesi')->with('success', "Asesi '{$validatedData['nama_lengkap']}' berhasil diperbarui.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui Asesi: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus Asesi DAN Akun User terkait.
     */
    public function destroy($id_asesi)
    {
        try {
            $asesi = Asesi::with('user')->findOrFail($id_asesi);
            $nama = $asesi->nama_lengkap;
            if ($asesi->tanda_tangan) {
                Storage::delete(str_replace('public/', '', $asesi->tanda_tangan));
            }
            $asesi->user->delete(); 
            return redirect()->route('master_asesi')->with('success', "Asesi '{$nama}' dan akun user terkait berhasil dihapus.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus Asesi: ' . $e->getMessage());
        }
    }
}