<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssessmentCompletionNotice extends Notification
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
            'title' => 'Asesmen Selesai',
            'body' => $this->data['message'] ?? 'Asesi telah menyelesaikan ujian.',
            'icon' => 'check-circle',
            'link' => $this->data['link'] ?? '#',
        ];
    }
}
