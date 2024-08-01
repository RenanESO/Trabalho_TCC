<?php

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use App\Livewire\{
    FotoPlus,
    Home,
    Ajuda,
    AuthGoogleCallback,
    PastaDownloadServidor,
    Treinamento,
    Duplicidade,
    Organizar,
    Perfil
};

// Rota principal
Route::get('/', FotoPlus::class)->name('fotoplus');

// Rota de callback do Google OAuth
Route::get('/oauth-google-callback', AuthGoogleCallback::class)->name('oauth-google-callback');

// Rota de download da pasta
Route::get('/pasta-download-servidor', PastaDownloadServidor::class)->middleware('auth')->name('pasta-download-servidor');

// Agrupamento de rotas protegidas por autenticação
Route::get('/home', Home::class)->middleware('auth')->name('home');
Route::get('/perfil', Perfil::class)->middleware('auth')->name('perfil');
Route::get('/ajuda', Ajuda::class)->middleware('auth')->name('ajuda');
Route::get('/treinamento', Treinamento::class)->middleware('auth')->name('treinamento');
Route::get('/duplicidade', Duplicidade::class)->middleware('auth')->name('duplicidade');
Route::get('/organizar', Organizar::class)->middleware('auth')->name('organizar');
Route::get('/logout', [AuthenticatedSessionController::class, 'logout'])->name('sair');

// Redirecionamento do dashboard para home
Route::get('/dashboard', function() {
    return redirect('/home');
});

// Agrupamento de rotas verificadas pelo Jetstream
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('home', Home::class)->middleware('auth')->name('home');
});




