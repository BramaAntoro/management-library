<?php

use App\Livewire\HomeComponent;
use App\Livewire\LoginComponent;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeComponent::class)->middleware('auth')->name('home');

Route::get('/login', LoginComponent::class)->name('login');
Route::get('/logout', [LoginComponent::class, 'logout'])->name('logout');