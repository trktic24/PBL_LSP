<?php

namespace App\Listeners;

use App\Events\AssessmentValidated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUsersOfValidation
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
    public function handle(AssessmentValidated $event): void
    {
        // Notify Asesor
        $event->user->notify(new \App\Notifications\AssessmentValidationNotice($event->data));

        // Notify Admin (Opsional sesuai request)
        $admins = \App\Models\User::whereHas('role', function ($q) {
            $q->where('nama_role', 'admin');
        })->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\AssessmentValidationNotice($event->data));
        }
    }
}
