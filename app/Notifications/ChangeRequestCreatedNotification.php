<?php

namespace App\Notifications;

use App\Models\ChangeControl;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChangeRequestCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected ChangeControl $changeControl) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('admin.change-controls.edit', $this->changeControl->id);

        return (new MailMessage)
            ->subject('Nueva solicitud de cambio pendiente: ' . $this->changeControl->title)
            ->line('Se ha creado una nueva solicitud de cambio que requiere tu revisión.')
            ->line('Título: ' . $this->changeControl->title)
            ->line('Solicitante: ' . ($this->changeControl->requester->name ?? 'Sistema'))
            ->action('Revisar Solicitud', $url)
            ->line('Gracias por tu colaboración en el control de calidad.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'change_control_id' => $this->changeControl->id,
            'title' => $this->changeControl->title,
            'requester' => $this->changeControl->requester->name ?? 'Sistema',
            'type' => $this->changeControl->type,
        ];
    }
}
