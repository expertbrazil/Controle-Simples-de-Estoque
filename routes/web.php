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

// Rotas de Autenticação
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    
    // Rotas protegidas existentes
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    
    // Rotas de Categoria
    Route::get('/categories/get-categories', [CategoryController::class, 'getCategories'])
        ->name('categories.get-categories');
    Route::resource('categories', CategoryController::class);
    
    Route::resource('products', ProductController::class);
    Route::get('products/search', [ProductController::class, 'search'])->name('products.search');
    Route::post('/products/upload-image', [ProductController::class, 'uploadImage'])->name('products.upload-image');
    Route::get('products/{product}/duplicate', [ProductController::class, 'duplicate'])->name('products.duplicate');
    
    // Rotas para clientes
    Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::resource('customers', CustomerController::class);
    
    // Rotas de Vendas
    Route::resource('sales', SaleController::class);
    Route::post('/sales/{sale}/cancel', [SaleController::class, 'cancel'])->name('sales.cancel');
    Route::post('/sales/{sale}/send-email', [SaleController::class, 'sendEmail'])->name('sales.send-email');
    Route::get('/sales/{sale}/receipt', [SaleController::class, 'receipt'])->name('sales.receipt');
    Route::post('/sales/hold', [SaleController::class, 'hold'])->name('sales.hold');
    Route::get('/sales/held', [SaleController::class, 'getHeldSales'])->name('sales.held');
    Route::post('/sales/{sale}/finalize', [SaleController::class, 'finalizeSale'])->name('sales.finalize');
    Route::get('/sales/search/customers', [SaleController::class, 'searchCustomers'])->name('sales.search.customers');
    Route::get('/sales/search/products', [SaleController::class, 'searchProducts'])->name('sales.search.products');
    Route::get('/sales/list/products', [SaleController::class, 'listProducts'])->name('sales.list.products');
    Route::get('sales/{sale}/duplicate', [SaleController::class, 'duplicate'])->name('sales.duplicate');
    
    // Rotas do PDV
    Route::controller(PdvController::class)->group(function () {
        Route::get('/pdv', 'index')->name('pdv.index');
        Route::get('/pdv/products', 'getProducts')->name('pdv.products');
        Route::get('/pdv/search-customers', 'searchCustomers')->name('pdv.search-customers');
        Route::post('/pdv/finalize-sale', 'finalizeSale')->name('pdv.finalize-sale');
        Route::get('/pdv/sale/{id}', 'show')->name('pdv.show');
        Route::get('/pdv/sale/{id}/print', 'print')->name('pdv.print');
        Route::post('/pdv/sale/{id}/send-email', 'sendEmail')->name('pdv.send-email');
    });
    
    // Rotas para Entrada de Produtos
    Route::resource('product-entries', ProductEntryController::class);
    Route::get('product-entries/search/products', [ProductEntryController::class, 'searchProducts'])
        ->name('product-entries.search-products');
    
    // Rotas de Parâmetros
    Route::get('/parameters', [ParameterController::class, 'index'])->name('parameters.index');
    Route::put('/parameters', [ParameterController::class, 'update'])->name('parameters.update');
    Route::post('/parameters', [ParameterController::class, 'store'])->name('parameters.store');
    
    // Rotas para Marcas
    Route::middleware(['auth'])->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        
        Route::resource('brands', BrandController::class);
    });
    
    // Rotas para Fornecedores
    Route::resource('suppliers', SupplierController::class);
    
    // Rota de teste de email
    Route::post('/test-email', function (Request $request) {
        try {
            $request->validate([
                'test_email' => 'required|email'
            ]);

            $recipientEmail = $request->test_email;
            \Log::info('Tentando enviar email para: ' . $recipientEmail);
            
            $emailService = new \App\Services\EmailService();
            $emailService->sendTest($recipientEmail);
            
            \Log::info('Email enviado com sucesso');
            
            return redirect()->back()
                ->with('success', 'Email de teste enviado com sucesso para ' . $recipientEmail);
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar email: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Erro ao enviar email: ' . $e->getMessage());
        }
    })->name('test-email');
});

// Rota inicial
Route::get('/', function () {
    return redirect()->route('home');
});
