<!DOCTYPE html>
<html>
<head>
    <title>Teste de Email</title>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; border-radius: 5px; padding: 20px; margin-bottom: 20px;">
        <h2 style="color: #0d6efd; margin-top: 0;">Teste de Email - Sistema de Estoque</h2>
        
        <p>Este é um email de teste enviado pelo Sistema de Controle de Estoque da Distribuidora Nova Rosa.</p>
        
        <p><strong>Informações do envio:</strong></p>
        <ul style="list-style-type: none; padding-left: 0;">
            <li>Data e hora: {{ $timestamp }}</li>
            <li>Servidor: {{ $config['host'] }}</li>
            <li>Porta: {{ $config['port'] }}</li>
            <li>Criptografia: {{ $config['encryption'] }}</li>
            <li>Usuário: {{ $config['username'] }}</li>
        </ul>
        
        <p style="background-color: #d1e7dd; color: #0f5132; padding: 10px; border-radius: 4px;">
            ✅ Se você está recebendo este email, significa que a configuração SMTP está funcionando corretamente!
        </p>
        
        <hr style="border: none; border-top: 1px solid #dee2e6; margin: 20px 0;">
        
        <p style="color: #6c757d; font-size: 0.875rem;">
            Este é um email automático, por favor não responda.<br>
            Enviado por {{ $config['from']['name'] }} &lt;{{ $config['from']['address'] }}&gt;
        </p>
    </div>
</body>
</html>
