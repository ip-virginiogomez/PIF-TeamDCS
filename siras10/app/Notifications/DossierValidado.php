<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DossierValidado extends Notification
{
    use Queueable;

    public $grupo;

    /**
     * Create a new notification instance.
     */
    public function __construct($grupo)
    {
        $this->grupo = $grupo;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $centroSalud = $this->grupo->cupoDistribucion->cupoOferta->unidadClinica->centroSalud->nombreCentro ?? 'N/A';
        $tipoPractica = $this->grupo->cupoDistribucion->cupoOferta->tipoPractica->nombrePractica ?? 'N/A';
        $asignatura = $this->grupo->asignatura->nombreAsignatura ?? 'N/A';
        $fechaInicio = $this->grupo->fechaInicio ? \Carbon\Carbon::parse($this->grupo->fechaInicio)->format('d/m/Y') : 'N/A';
        $fechaFin = $this->grupo->fechaFin ? \Carbon\Carbon::parse($this->grupo->fechaFin)->format('d/m/Y') : 'N/A';

        return [
            'mensaje' => "El dossier del grupo \"{$this->grupo->nombreGrupo}\" ha sido validado.\n" .
                        "Centro de Salud: {$centroSalud}\n" .
                        "Tipo de Práctica: {$tipoPractica}\n" .
                        "Asignatura: {$asignatura}\n" .
                        "Periodo: {$fechaInicio} - {$fechaFin}",
            'idGrupo' => $this->grupo->idGrupo,
            'fecha' => now(),
        ];
    }
}
