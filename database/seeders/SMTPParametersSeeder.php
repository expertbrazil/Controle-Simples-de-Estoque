<?php

namespace Database\Seeders;

use App\Models\Parameter;
use Illuminate\Database\Seeder;

class SMTPParametersSeeder extends Seeder
{
    public function run(): void
    {
        $smtpParameters = [
            [
                'key' => 'smtp_host',
                'name' => 'SMTP Host',
                'value' => 'smtp.gmail.com',
                'group' => 'email',
                'type' => 'text',
                'is_private' => false,
                'description' => 'SMTP server hostname'
            ],
            [
                'key' => 'smtp_port',
                'name' => 'SMTP Port',
                'value' => '587',
                'group' => 'email',
                'type' => 'number',
                'is_private' => false,
                'description' => 'SMTP server port'
            ],
            [
                'key' => 'smtp_username',
                'name' => 'SMTP Username',
                'value' => '',
                'group' => 'email',
                'type' => 'text',
                'is_private' => false,
                'description' => 'SMTP username'
            ],
            [
                'key' => 'smtp_password',
                'name' => 'SMTP Password',
                'value' => '',
                'group' => 'email',
                'type' => 'password',
                'is_private' => true,
                'description' => 'SMTP password'
            ],
            [
                'key' => 'smtp_encryption',
                'name' => 'SMTP Encryption',
                'value' => 'tls',
                'group' => 'email',
                'type' => 'select',
                'is_private' => false,
                'description' => 'SMTP encryption method'
            ],
            [
                'key' => 'smtp_sender_name',
                'name' => 'Sender Name',
                'value' => 'Controle de Estoque',
                'group' => 'email',
                'type' => 'text',
                'is_private' => false,
                'description' => 'Name displayed as sender'
            ],
            [
                'key' => 'smtp_sender_email',
                'name' => 'Sender Email',
                'value' => '',
                'group' => 'email',
                'type' => 'text',
                'is_private' => false,
                'description' => 'Email address used as sender'
            ]
        ];

        foreach ($smtpParameters as $parameter) {
            Parameter::updateOrCreate(
                ['key' => $parameter['key']],
                $parameter
            );
        }
    }
}
