<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssessmentReviewRequest extends Notification
{
    use Queueable;
    public $data;


    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->data['title'] ?? 'Penilaian Siap Divalidasi',
            'message' => $this->data['message'] ?? 'Asesor telah menyelesaikan penilaian asesmen.',
            'action_url' => $this->data['action_url'] ?? '#',
            'actor' => $this->data['actor'] ?? 'Asesor',
            'entity_id' => $this->data['entity_id'] ?? null,
            'icon' => 'clipboard-check', // Keeping icon for UI consistency
        ];
    }
}
