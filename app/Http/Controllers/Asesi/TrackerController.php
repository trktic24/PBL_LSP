<?php

namespace App\Http\Controllers\Asesi;

use Carbon\Carbon;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Support\Facades\Auth;

class TrackerController extends Controller
{
    /**
     * Menampilkan halaman tracker untuk user yang sedang login.
     * Mendukung parameter opsional $jadwal_id untuk redirect spesifik.
     */
    public function index($jadwal_id = null)
    {
        $user = Auth::user();
        $asesi = $user->asesi;
        $sertifikasi = null;

        // Default value buat flag tampilan (biar gak error kalau $sertifikasi null)
        $showPortofolio = false;
        $showObservasi = false;
        $showTanyaJawab = false;
        $showReviuProduk = false;
        $showKegiatan = false;
        $showTertulis = false;
        $showLisan = false;
        $showWawancara = false;
        $showLainnya = false;

        // Data Status default flags
        $unlockAPL02 = false;
        $unlockAK01 = false;
        $unlockAsesmen = false;
        $unlockAK03 = false;
        $isSudahHadir = false;
        $unlockHasil = false;

        // Data Status default text
        $statusAPL01 = 'menunggu';
        $statusAPL02 = 'menunggu';
        $statusAK01 = 'menunggu';
        $statusAsesmen = 'menunggu';
        $statusAK03 = 'menunggu';
        $pesanHasil = '';

        if ($asesi) {
            // [UPDATE 1] Tambahkan 'responBuktiAk01' di sini biar datanya keambil sekalian
            $query = $asesi->dataSertifikasi()->with(['jadwal.skema', 'jadwal.asesor', 'responBuktiAk01', 'daftarHadir']);

            if ($jadwal_id) {
                $sertifikasi = $query->where('id_jadwal', $jadwal_id)->first();
            } else {
                $sertifikasi = $query->latest()->first();
            }

            // [BARU] LOGIC CEK BUKTI AK.01
            if ($sertifikasi) {
                // APL 01 Status
                $apl01 = $sertifikasi->rekomendasi_apl01;
                $statusAPL01 = $apl01;

                $isSudahHadir = $sertifikasi->is_sudah_hadir;

                // APL 02 Status
                $apl02 = $sertifikasi->rekomendasi_apl02;
                $statusAPL02 = $apl02;

                // Logic unlock APL-02
                if ($apl01 == 'diterima') {
                    $unlockAPL02 = true;
                }

                // Logic unlock Asesmen
                if ($apl02 == 'diterima') {
                    $unlockAK01 = true;
                }

                $isAsesorVerified = $sertifikasi->rekomendasi_apl02 == 'diterima';

                // 1. Ambil semua ID bukti yang tersimpan buat asesi ini
                $buktiIds = $sertifikasi->responBuktiAk01->pluck('id_bukti_ak01')->toArray();

                // 2. Definisi ID
                $ID_PORTOFOLIO = 1;
                $ID_OBSERVASI = 2;
                $ID_TANYA_JAWAB = 3;
                $ID_REVIU_PRODUK = 4;
                $ID_KEGIATAN = 5;
                $ID_TERTULIS = 6;
                $ID_LISAN = 7;
                $ID_WAWANCARA = 8;
                $ID_LAINNYA = 9;

                // 3. Cek apakah ID tersebut ada di array milik asesi
                $showPortofolio = in_array($ID_PORTOFOLIO, $buktiIds);
                $showObservasi = in_array($ID_OBSERVASI, $buktiIds);
                $showTanyaJawab = in_array($ID_TANYA_JAWAB, $buktiIds);
                $showReviuProduk = in_array($ID_REVIU_PRODUK, $buktiIds);
                $showKegiatan = in_array($ID_KEGIATAN, $buktiIds);
                $showTertulis = in_array($ID_TERTULIS, $buktiIds);
                $showLisan = in_array($ID_LISAN, $buktiIds);
                $showWawancara = in_array($ID_WAWANCARA, $buktiIds);
                $showLainnya = in_array($ID_LAINNYA, $buktiIds);

                $jadwalDB = $sertifikasi->jadwal;
                if ($jadwalDB) {
                    // buat waktu mulai dari tanggal pelaksanaan + waktu mulai
                    $tanggalSaja = Carbon::parse($jadwalDB->tanggal_pelaksanaan)->format('Y-m-d');
                    $waktuMulai = Carbon::parse($tanggalSaja . ' ' . $jadwalDB->waktu_mulai);

                    // ambil waktu sekarang
                    $waktuSekarang = Carbon::now();

                    // cek apakah waktu sekarang sudah melewati atau sama dengan waktu mulai
                    $isWaktuSudahMulai = $waktuSekarang->greaterThanOrEqualTo($waktuMulai);

                    $tglPelaksanaan = Carbon::parse($jadwalDB->tanggal_pelaksanaan);
                    $tglBukaHasil = $tglPelaksanaan->copy()->addDay()->startOfDay();

                    // Asesmen Status
                    if ($isWaktuSudahMulai) {
                        if (!$isAsesorVerified) {
                            $pesanStatus = 'Menunggu Verifikasi Asesor.';
                        } elseif (!$isSudahHadir) {
                            // TERKUNCI KARENA BELUM ABSEN
                            $pesanStatus = 'Anda belum mengisi Daftar Hadir.';
                        } else {
                            $unlockAsesmen = true;
                        }
                    } else {
                        $pesanStatus = 'Asesmen belum dimulai.';
                    }

                    if (Carbon::now()->greaterThanOrEqualTo($tglBukaHasil)) {
                        $unlockHasil = true;
                        $unlockAK03 = true;
                    }
                    
                } else {
                    $pesanWaktu = 'Jadwal asesmen tidak ditemukan.';
                }
            }
        }

        // [UPDATE 2] Kirim variabel flags ke View
        return view('asesi.tracker', [
            'sertifikasi' => $sertifikasi,
            'showPortofolio' => $showPortofolio,
            'showObservasi' => $showObservasi,
            'showTanyaJawab' => $showTanyaJawab,
            'showReviuProduk' => $showReviuProduk,
            'showKegiatan' => $showKegiatan,
            'showTertulis' => $showTertulis,
            'showLisan' => $showLisan,
            'showWawancara' => $showWawancara,
            'showLainnya' => $showLainnya,
            'unlockAPL02' => $unlockAPL02,
            'unlockAK01' => $unlockAK01,
            'unlockAsesmen' => $unlockAsesmen,
            'unlockAK03' => $unlockAK03,
            'statusAPL01' => $statusAPL01,
            'statusAPL02' => $statusAPL02,
            'statusAK01' => $statusAK01,
            'statusAsesmen' => $statusAsesmen,
            'statusAK03' => $statusAK03,
            'pesanWaktu' => $pesanWaktu ?? null,
            'isSudahHadir' => $isSudahHadir,
            'unlockHasil' => $unlockHasil,
            'pesanHasil' => $pesanHasil,
            'pesanStatus' => $pesanStatus ?? null,
        ]);
    }

    /**
     * ========================================================
     * FUNGSI API: Untuk mendaftarkan user ke jadwal
     * ========================================================
     */
    public function daftarJadwal(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi input
        $request->validate([
            'id_jadwal' => 'required|integer|exists:jadwal,id_jadwal',
        ]);

        $id_jadwal = $request->id_jadwal;
        $asesi = $user->asesi;

        // 2. Pastikan asesi ada
        if (!$asesi) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Data asesi Anda tidak ditemukan. Harap lengkapi profil Anda.',
                ],
                404,
            );
        }

        // 3. Cek apakah user sudah mendaftar di jadwal ini?
        $existing = DataSertifikasiAsesi::where('id_asesi', $asesi->id_asesi)->where('id_jadwal', $id_jadwal)->first();

        if ($existing) {
            return response()->json(
                [
                    'success' => false,
                    // Arahkan ke tracker jadwal tersebut
                    'redirect_url' => route('tracker', ['jadwal_id' => $existing->id_jadwal]),
                    'message' => 'Anda sudah memiliki pendaftaran aktif. Mengarahkan ke data Anda...',
                ],
                409,
            );
        }

        try {
            // 4. Buat pendaftaran baru
            // Status default biasanya diatur di database ('sedang_mendaftar')
            $newSertifikasi = DataSertifikasiAsesi::create([
                'id_asesi' => $asesi->id_asesi,
                'id_jadwal' => $id_jadwal,
                'tujuan_asesmen' => 'Sertifikasi', // Default, nanti diubah user di formulir
                'tanggal_daftar' => Carbon::now(),
            ]);

            // 5. Kirim respons sukses
            return response()->json([
                'success' => true,
                // Arahkan ke tracker dengan ID JADWAL YANG BARU DIBUAT
                'redirect_url' => route('tracker', ['jadwal_id' => $newSertifikasi->id_jadwal]),
                'message' => 'Berhasil mendaftar! Mengarahkan Anda ke Tracker...',
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Terjadi kesalahan server saat mendaftar: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }
}
