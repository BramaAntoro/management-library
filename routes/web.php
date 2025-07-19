<?php

use App\Livewire\BookComponent;
use App\Livewire\BorrowComponent;
use App\Livewire\CategoryComponent;
use App\Livewire\HomeComponent;
use App\Livewire\LoginComponent;
use App\Livewire\MemberComponent;
use App\Livewire\UserComponent;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeComponent::class)->middleware('auth')->name('home');

Route::get('/login', LoginComponent::class)->name('login');
Route::get('/logout', [LoginComponent::class, 'logout'])->name('logout');

Route::get('/user', UserComponent::class)->name('user')->middleware('auth');

Route::get('/member', MemberComponent::class)->name('member')->middleware('auth');

Route::get('/categories', CategoryComponent::class)->name('category')->middleware('auth');

Route::get('/books', BookComponent::class)->name('book')->middleware('auth');

Route::get('/borrower', BorrowComponent::class)->name('borrower')->middleware('auth');