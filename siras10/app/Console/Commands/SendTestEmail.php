<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:send-email {to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $to = $this->argument('to');

        $this->info("Enviando email de prueba a: {$to}");

        try {
            Mail::raw('Este es un email de prueba desde SIRAS', function ($message) use ($to) {
                $message->to($to)
                    ->subject('Email de Prueba - SIRAS');
            });

            $this->info('✓ Email enviado exitosamente!');

        } catch (\Exception $e) {
            $this->error('✗ Error al enviar: '.$e->getMessage());
            $this->error('Archivo: '.$e->getFile().':'.$e->getLine());
        }
    }
}
