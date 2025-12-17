<?php

namespace App\Listeners;

use App\Events\AssessmentReviewed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyValidatorOfReview
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AssessmentReviewed $event): void
    {
        // Cari user yang berperan sebagai validator, misal berdasarkan role 'validator'
        // Atau ambil dari relation jika ada. Untuk sekarang kita anggap semua user dengan role 'admin' atau 'validator'
        // Karena struktur role belum pasti, saya gunakan User::all() sebagai placeholder atau query spesifik jika tahu.
        // Asumsi: Ada model App\Models\User dan scope atau kolom role.

        $validators = \App\Models\User::whereHas('role', function ($q) {
            $q->where('nama_role', 'superadmin');
        })->get();
        foreach ($validators as $validator) {
            $validator->notify(new \App\Notifications\AssessmentReviewRequest($event->data));
        }
    }
}
