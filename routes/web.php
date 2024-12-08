<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;

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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rotas de Categoria
    Route::get('/categories/get-categories', [CategoryController::class, 'getCategories'])
        ->name('categories.get-categories');
    Route::resource('categories', CategoryController::class);
    
    Route::resource('products', ProductController::class);
    Route::get('products/search', [ProductController::class, 'search'])->name('products.search');
    Route::post('/products/upload-image', [ProductController::class, 'uploadImage'])->name('products.upload-image');
    Route::resource('customers', CustomerController::class);
    Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');
    Route::resource('sales', SaleController::class);
    
    // Rotas do PDV
    Route::get('/pdv', [SaleController::class, 'create'])->name('pdv.create');
    Route::post('/pdv', [SaleController::class, 'store'])->name('pdv.store');
    Route::get('/pdv/search-products', [SaleController::class, 'searchProducts'])->name('pdv.search-products');
    Route::get('/pdv/search-customers', [SaleController::class, 'searchCustomers'])->name('pdv.search-customers');
    
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

// Rota inicial
Route::get('/', function () {
    return redirect()->route('home');
});
