<?php

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

use App\Livewire\{
    FotoPlus,
    Home,
    Ajuda,
    Treinamento,
    Duplicidade,
    Organizar,
    Perfil
};

Route::get('/', FotoPlus::class)->name('fotoplus');
Route::get('/home', Home::class)->middleware('auth')->name('home');
Route::get('/perfil', Perfil::class)->middleware('auth')->name('perfil');
Route::get('/ajuda', Ajuda::class)->middleware('auth')->name('ajuda');
Route::get('/treinamento', Treinamento::class)->middleware('auth')->name('treinamento');
Route::get('/duplicidade', Duplicidade::class)->middleware('auth')->name('duplicidade');
Route::get('/organizar', Organizar::class)->middleware('auth')->name('organizar');
Route::get('/logout', [AuthenticatedSessionController::class, 'logout'])->name('sair');

Route::get('/dashboard', function() {
    return redirect('/home');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('home', Home::class)->middleware('auth')->name('home');
});




