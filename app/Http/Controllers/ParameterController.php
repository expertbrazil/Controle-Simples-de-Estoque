<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParameterController extends Controller
{
    public function index()
    {
        $smtpConfig = include(config_path('smtp_config.php'));
        return view('parameters.index', compact('smtpConfig'));
    }

    public function update(Request $request)
    {
        $smtp = $request->smtp;
        
        $config = [
            'scheme' => 'smtp',
            'host' => $smtp['host'],
            'port' => (int)$smtp['port'],
            'encryption' => $smtp['encryption'],
            'username' => $smtp['username'],
            'password' => $smtp['password'],
        ];

        $configContent = "<?php\n\nreturn " . var_export($config, true) . ";";
        file_put_contents(config_path('smtp_config.php'), $configContent);

        return redirect()->route('parameters.index')->with('success', 'Configurações atualizadas com sucesso!');
    }
}
