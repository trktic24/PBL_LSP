<?php

namespace App\Listeners;

use App\Events\AssessmentCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyAsesorOfCompletion
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
    public function handle(AssessmentCompleted $event): void
    {
        $event->user->notify(new \App\Notifications\AssessmentCompletionNotice($event->data));
    }
}
