<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct()
    {
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Teste de Email - Sistema de Estoque')
                    ->view('emails.test')
                    ->with([
                        'timestamp' => now()->format('d/m/Y H:i:s'),
                        'config' => [
                            'host' => config('mail.mailers.smtp.host'),
                            'port' => config('mail.mailers.smtp.port'),
                            'encryption' => config('mail.mailers.smtp.encryption'),
                            'username' => config('mail.mailers.smtp.username'),
                            'from' => [
                                'address' => config('mail.from.address'),
                                'name' => config('mail.from.name')
                            ]
                        ]
                    ]);
    }
}
