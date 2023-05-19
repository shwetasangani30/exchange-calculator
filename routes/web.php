<?php

use App\Http\Controllers\BuySellController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellBuyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login')->with(Auth::logout());
});

/* Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard'); */

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['verified'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('buysell', BuySellController::class);
    Route::post('/buysell/sell', [BuySellController::class, 'sell'])->name('buysell.sell');
    Route::post('/buysell/sellClose', [BuySellController::class, 'sellClose'])->name('buysell.sellClose');

    Route::resource('sellbuy', SellBuyController::class);
    Route::post('/sellbuy/buy', [SellBuyController::class, 'buy'])->name('sellbuy.buy');
    Route::post('/sellbuy/buyClose', [SellBuyController::class, 'buyClose'])->name('sellbuy.buyClose');

    Route::get('/buy/export', [BuySellController::class, 'exportToExcel'])->name('buyExport');

    Route::get('/sell/export', [SellBuyController::class, 'exportToExcel'])->name('sellExport');
});

require __DIR__ . '/auth.php';
