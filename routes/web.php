<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

Route::get('/login', [App\Filament\Pages\Auth\CustomLogin::class, '__invoke'])->name('login');