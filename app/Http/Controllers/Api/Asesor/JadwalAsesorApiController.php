<?php

namespace App\Http\Controllers\Api\Asesor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\Asesor;
use App\Models\Skema;
use App\Models\MasterTuk;
use App\Models\JenisTuk;
use Illuminate\Support\Facades\Validator;

class JadwalAsesorApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // 1. Cek Data Asesor
        if (!$user->asesor) {
            return response()->json([
                'success' => false,
                'message' => 'Data profil Asesor tidak ditemukan.'
            ], 403);
        }

        // 2. Cek Status Verifikasi
        if ($user->asesor->status_verifikasi !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda belum diverifikasi oleh Admin.'
            ], 403);
        }

        $id_asesor = $user->asesor->id_asesor;

        // 3. Query Jadwal
        $jadwal = Jadwal::where('id_asesor', $id_asesor)
            ->with('skema', 'tuk', 'jenisTuk')
            ->orderBy('tanggal_pelaksanaan', 'asc');

        // A. Filter Pencarian (Search Input)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $jadwal->where(function ($q) use ($searchTerm) {
                $q->where('Status_jadwal', 'like', '%' . $searchTerm . '%')
                    ->orWhere('waktu_mulai', 'like', '%' . $searchTerm . '%')
                    ->orWhere('tanggal_pelaksanaan', 'like', '%' . $searchTerm . '%')
                    ->orWhere('sesi', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('skema', function ($qSkema) use ($searchTerm) {
                        $qSkema->where('nama_skema', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('tuk', function ($qTuk) use ($searchTerm) {
                        $qTuk->where('nama_lokasi', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('jenis_tuk', function ($qJenisTuk) use ($searchTerm) {
                        $qJenisTuk->where('jenis_tuk', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        // B. Filter Nama Skema
        if ($request->has('namaskema') && is_array($request->namaskema)) {
            $jadwal->whereHas('skema', function ($q) use ($request) {
                $q->whereIn('nama_skema', $request->namaskema);
            });
        }

        // Filter Sesi
        if ($request->has('sesi') && is_array($request->sesi)) {
            $jadwal->whereIn('sesi', $request->sesi);
        }

        // C. Filter Tanggal
        if ($request->filled('tanggal')) {
            $jadwal->whereDate('tanggal_pelaksanaan', $request->tanggal);
        }

        // D. Filter Status
        if ($request->has('status') && is_array($request->status)) {
            $jadwal->whereIn('Status_jadwal', $request->status);
        }

        // Waktu
        if ($request->filled('waktu')) {
            $jadwal->whereTime('waktu_mulai', '>=', $request->waktu);
        }

        // Filter TUK
        if ($request->has('tuk') && is_array($request->tuk)) {
            $jadwal->whereIn('tuk', $request->tuk);
        }

        // Filter Jenis TUK
        if ($request->has('jenistuk') && is_array($request->jenistuk)) {
            $jadwal->whereIn('jenistuk', $request->jenistuk);
        }

        // Eksekusi Query
        $jadwals = $jadwal->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Daftar Jadwal Asesor berhasil diambil',
            'data' => $jadwals
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user->asesor) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'id_jenis_tuk' => 'required|exists:jenis_tuk,id_jenis_tuk',
            'id_tuk' => 'required|exists:master_tuk,id_tuk',
            'id_skema' => 'required|exists:skema,id_skema',
            'sesi' => 'required|integer',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'tanggal_pelaksanaan' => 'required|date',
            'Status_jadwal' => 'required|in:Terjadwal,Selesai,Dibatalkan',
            'kuota_maksimal' => 'required|integer',
            'kuota_minimal' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        // Paksa id_asesor sesuai user yang login
        $data['id_asesor'] = $user->asesor->id_asesor;

        $jadwal = Jadwal::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dibuat',
            'data' => $jadwal
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        if (!$user->asesor) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $jadwal = Jadwal::with(['skema', 'tuk', 'jenisTuk', 'dataSertifikasiAsesi.asesi'])->find($id);

        if (!$jadwal) {
            return response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan'], 404);
        }

        // Pastikan jadwal milik asesor ini
        if ($jadwal->id_asesor != $user->asesor->id_asesor) {
            return response()->json(['success' => false, 'message' => 'Anda tidak berhak mengakses jadwal ini'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $jadwal
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        if (!$user->asesor) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $jadwal = Jadwal::find($id);

        if (!$jadwal) {
            return response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan'], 404);
        }

        if ($jadwal->id_asesor != $user->asesor->id_asesor) {
            return response()->json(['success' => false, 'message' => 'Anda tidak berhak mengubah jadwal ini'], 403);
        }

        $validator = Validator::make($request->all(), [
            'id_jenis_tuk' => 'sometimes|exists:jenis_tuk,id_jenis_tuk',
            'id_tuk' => 'sometimes|exists:master_tuk,id_tuk',
            'id_skema' => 'sometimes|exists:skema,id_skema',
            'sesi' => 'sometimes|integer',
            'tanggal_mulai' => 'sometimes|date',
            'tanggal_selesai' => 'sometimes|date',
            'tanggal_pelaksanaan' => 'sometimes|date',
            'Status_jadwal' => 'sometimes|in:Terjadwal,Selesai,Dibatalkan',
            'kuota_maksimal' => 'sometimes|integer',
            'kuota_minimal' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $jadwal->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil diperbarui',
            'data' => $jadwal
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if (!$user->asesor) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $jadwal = Jadwal::find($id);

        if (!$jadwal) {
            return response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan'], 404);
        }

        if ($jadwal->id_asesor != $user->asesor->id_asesor) {
            return response()->json(['success' => false, 'message' => 'Anda tidak berhak menghapus jadwal ini'], 403);
        }

        $jadwal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dihapus'
        ]);
    }
}