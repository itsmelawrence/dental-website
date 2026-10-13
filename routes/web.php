<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/services', [PageController::class, 'services'])->name('services.index');
Route::get('/services/{slug}', [PageController::class, 'service'])->name('services.show');
Route::get('/dentists', [PageController::class, 'dentists'])->name('dentists');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/book', [BookingController::class, 'store'])->middleware('throttle:5,1')->name('booking.store');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/sitemap.xml', [PageController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [PageController::class, 'robots'])->name('robots');
