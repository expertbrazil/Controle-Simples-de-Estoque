<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;

class EmailService
{
    public function sendTest($recipientEmail)
    {
        $smtpConfig = include(config_path('smtp_config.php'));
        
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.scheme' => 'smtp',
            'mail.mailers.smtp.host' => $smtpConfig['host'],
            'mail.mailers.smtp.port' => $smtpConfig['port'],
            'mail.mailers.smtp.encryption' => $smtpConfig['encryption'],
            'mail.mailers.smtp.username' => $smtpConfig['username'],
            'mail.mailers.smtp.password' => $smtpConfig['password'],
            'mail.from.address' => $smtpConfig['username'],
            'mail.from.name' => 'Sistema de Estoque'
        ]);

        try {
            Mail::to($recipientEmail)->send(new TestEmail());
            return true;
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar email: ' . $e->getMessage());
            throw $e;
        }
    }
}
