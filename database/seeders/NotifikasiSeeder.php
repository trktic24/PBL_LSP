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

            $titles = ['Jadwal Asesmen Baru', 'Pengingat Validasi'];
            $messages = ['Anda memiliki jadwal asesmen baru pada tanggal 2024-12-20.', 'Mohon segera lakukan validasi untuk asesi baru.'];

            for ($i = 0; $i < 2; $i++) {
                $notifications[] = [
                    'id' => \Illuminate\Support\Str::uuid()->toString(),
                    'type' => 'App\Notifications\GeneralNotification',
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => $userId,
                    'data' => json_encode([
                        'title' => $titles[$i],
                        'body' => $messages[$i],
                        'icon' => ($i == 0) ? 'calendar' : 'check-circle',
                        'link' => '#' // Ganti dengan route yang sesuai jika ada
                    ]),
                    'read_at' => null, // Set unread for visibility
                    'created_at' => $now->copy()->subMinutes($i * 5),
                    'updated_at' => $now->copy()->subMinutes($i * 5),
                ];
            }
            DB::table('notifications')->insert($notifications);
        }
    }
}
