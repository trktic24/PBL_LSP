<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User; // Pastikan Model User diimport jika ingin lebih dinamis

class NotifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua user yang terdaftar sebagai Asesor
        // Asumsi: Ada relasi 'user' di model Asesor atau kita ambil id_user dari tabel Asesor
        $asesorUsers = \App\Models\Asesor::pluck('id_user')->toArray();

        // Jika tidak ada data Asesor, fallback ke User ID 1 (untuk testing manual)
        if (empty($asesorUsers)) {
            $asesorUsers = [1];
        }

        foreach ($asesorUsers as $userId) {
            // Hapus notifikasi lama agar tidak menumpuk
            DB::table('notifications')->where('notifiable_id', $userId)->delete();

            $notifications = [];
            $now = Carbon::now();

            for ($i = 0; $i < 15; $i++) {
                $notifications[] = [
                    'id' => \Illuminate\Support\Str::uuid()->toString(),
                    'type' => 'App\Notifications\GeneralNotification',
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => $userId,
                    'data' => json_encode([
                        'title' => 'Notifikasi Asesor ' . $userId . ' - Ke-' . ($i + 1),
                        'body' => 'Ini adalah pesan dummy untuk Asesor ID ' . $userId,
                        'icon' => ($i % 2 == 0) ? 'info' : 'warning',
                        'link' => '#'
                    ]),
                    'read_at' => ($i > 5) ? $now->copy()->subHours($i) : null,
                    'created_at' => $now->copy()->subHours($i),
                    'updated_at' => $now->copy()->subHours($i),
                ];
            }
            DB::table('notifications')->insert($notifications);
        }
    }
}
