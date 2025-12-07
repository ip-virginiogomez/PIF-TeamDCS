<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Password;

class TestPasswordReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:password-reset {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test password reset email sending';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $this->info("Intentando enviar email de recuperación a: {$email}");

        try {
            $status = Password::sendResetLink(['email' => $email]);

            $this->info("Status: {$status}");

            if ($status === Password::RESET_LINK_SENT) {
                $this->info('✓ Email enviado exitosamente!');
            } elseif ($status === Password::INVALID_USER) {
                $this->error('✗ Usuario no encontrado');
            } elseif ($status === Password::RESET_THROTTLED) {
                $this->error('✗ Demasiados intentos, espera un momento');
            } else {
                $this->error("✗ Error: {$status}");
            }

        } catch (\Exception $e) {
            $this->error('Excepción: '.$e->getMessage());
            $this->error('Archivo: '.$e->getFile().':'.$e->getLine());
        }
    }
}
