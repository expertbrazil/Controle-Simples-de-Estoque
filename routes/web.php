<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\PdvController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProductEntryController;
use Illuminate\Support\Facades\Mail;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\SupplierController;
use App\Mail\TestEmail;

// Rota inicial - redireciona para login ou dashboard
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Rotas de Autenticação
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Rotas protegidas por autenticação
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Autenticação
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    
    // Categorias
    Route::get('/categories/get-categories', [CategoryController::class, 'getCategories'])
        ->name('categories.get-categories');
    Route::resource('categories', CategoryController::class);
    
    // Produtos
    Route::resource('products', ProductController::class);
    Route::get('products/search', [ProductController::class, 'search'])->name('products.search');
    Route::post('/products/upload-image', [ProductController::class, 'uploadImage'])->name('products.upload-image');
    Route::get('products/{product}/duplicate', [ProductController::class, 'duplicate'])->name('products.duplicate');
    
    // PDV
    Route::prefix('pdv')->group(function () {
        Route::get('/', [PdvController::class, 'index'])->name('pdv.index');
        Route::post('/', [PdvController::class, 'store'])->name('pdv.store');
        Route::get('/product/{id}', [PdvController::class, 'getProduct'])->name('pdv.product');
        Route::get('/customer/{id}', [PdvController::class, 'getCustomer'])->name('pdv.customer');
    });
    
    // Vendas
    Route::resource('sales', SaleController::class);
    Route::get('/sales/{sale}/print', [SaleController::class, 'print'])->name('sales.print');
    
    // Entradas de Produtos
    Route::resource('product-entries', ProductEntryController::class);
    
    // Marcas
    Route::resource('brands', BrandController::class);
    
    // Fornecedores
    Route::resource('suppliers', SupplierController::class);
    Route::patch('suppliers/{supplier}/toggle-status', [SupplierController::class, 'toggleStatus'])->name('suppliers.toggle-status');
    
    // Parâmetros
    Route::get('/parameters', [ParameterController::class, 'index'])->name('parameters.index');
    Route::post('/parameters', [ParameterController::class, 'store'])->name('parameters.store');
    Route::put('/parameters/smtp', [ParameterController::class, 'updateSmtp'])->name('parameters.update-smtp');
    
    // Teste de E-mail
    Route::post('/test-email', function (Request $request) {
        try {
            $request->validate([
                'test_email' => 'required|email'
            ]);

            \Log::info('Iniciando teste de email para: ' . $request->test_email);

            // Carregar configurações SMTP atualizadas
            if (!file_exists(config_path('smtp_config.php'))) {
                throw new \Exception('Configurações SMTP não encontradas. Por favor, configure o SMTP primeiro.');
            }

            $smtpConfig = include(config_path('smtp_config.php'));
            \Log::info('Configurações SMTP carregadas:', $smtpConfig);
            
            // Verificar se todas as configurações necessárias existem
            $requiredKeys = ['host', 'port', 'encryption', 'username', 'password', 'scheme'];
            foreach ($requiredKeys as $key) {
                if (!isset($smtpConfig[$key])) {
                    throw new \Exception("Configuração SMTP incompleta. Falta o parâmetro: {$key}");
                }
            }

            // Configurar o SMTP em tempo real
            $mailConfig = [
                'mail.default' => 'smtp',
                'mail.mailers.smtp.transport' => 'smtp',
                'mail.mailers.smtp.host' => $smtpConfig['host'],
                'mail.mailers.smtp.port' => $smtpConfig['port'],
                'mail.mailers.smtp.encryption' => $smtpConfig['encryption'],
                'mail.mailers.smtp.username' => $smtpConfig['username'],
                'mail.mailers.smtp.password' => $smtpConfig['password'],
                'mail.mailers.smtp.scheme' => $smtpConfig['scheme'],
                'mail.from.address' => $smtpConfig['username'],
                'mail.from.name' => config('app.name', 'Sistema de Estoque')
            ];

            \Log::info('Aplicando configurações de email:', $mailConfig);
            config($mailConfig);

            // Reconfigurar o mailer com as novas configurações
            \Illuminate\Support\Facades\Mail::purge('smtp');
            
            // Tentar estabelecer conexão SMTP antes de enviar
            try {
                $transport = new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport(
                    $smtpConfig['host'],
                    $smtpConfig['port'],
                    $smtpConfig['encryption'] === 'ssl'
                );
                $transport->setUsername($smtpConfig['username']);
                $transport->setPassword($smtpConfig['password']);
                
                \Log::info('Testando conexão SMTP...');
                $transport->start();
                \Log::info('Conexão SMTP estabelecida com sucesso');
                $transport->stop();
            } catch (\Exception $e) {
                \Log::error('Erro na conexão SMTP: ' . $e->getMessage());
                throw new \Exception('Não foi possível estabelecer conexão com o servidor SMTP: ' . $e->getMessage());
            }
            
            // Enviar o email
            \Log::info('Enviando email de teste...');
            Mail::to($request->test_email)->send(new TestEmail());
            \Log::info('Email enviado com sucesso');
            
            return response()->json(['message' => 'E-mail de teste enviado com sucesso!']);
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar e-mail de teste: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Erro ao enviar e-mail: ' . $e->getMessage(),
                'details' => [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    })->name('test.email')->middleware('auth');
});
