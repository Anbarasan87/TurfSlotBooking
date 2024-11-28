<?php

use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Auth\TurfController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/test', function () {
    return "Server is working fine";
});

Route::get('/register', [RegisteredUserController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'register']);
Route::get('/login', [RegisteredUserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [RegisteredUserController::class, 'login']);

Route::resource('turfs', TurfController::class);

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/user', [DashboardController::class, 'user'])->name('dashboard.user');
    Route::patch('/bookings/{id}/cancel', [DashboardController::class, 'cancelBooking'])->name('booking.cancel');
    Route::post('/dashboard/user/book/{turf_id}', [DashboardController::class, 'storeBooking'])->name('dashboard.user.book');
    Route::get('/payment/gateway/{booking_id}', [DashboardController::class, 'showPaymentGateway'])->name('user.paymentgateway');
    Route::post('/payment/process/{booking_id}', [DashboardController::class, 'processPayment'])->name('payment.process');
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/dashboard/owner', [DashboardController::class, 'owner'])->name('dashboard.owner');
    Route::get('/owner/download', [DashboardController::class, 'downloadData'])->name('owner.download');
    Route::get('/dashboard/owner/turfs', [DashboardController::class, 'showTurfs'])->name('owner.turfs');
    Route::get('/dashboard/owner/bookings', [DashboardController::class, 'showBookings'])->name('owner.bookings');
    Route::get('/dashboard/owner/bookings/{id}', [DashboardController::class, 'updateBooking'])->name('owner.bookings.update');
    Route::put('dashboard/owner/turfs/update/{id}', [DashboardController::class, 'updateTurf'])->name('owner.turfs.update');
    Route::get('/admin/download', [DashboardController::class, 'downloadAData'])->name('admin.download');
     Route::post('/check-availability', function (Request $request) {
        $exists = DB::table('bookings')
            ->where('turf_id', $request->turf_id)
            ->where('booking_date', $request->booking_date)
            ->whereRaw("time_slot BETWEEN '{$request->time_slot}'")
            ->exists();
    
        return response()->json(['available' => !$exists]);
    });
    
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/manage', [AdminController::class, 'manage'])->name('manage');
        Route::get('/users', [AdminController::class, 'manageUsers'])->name('users');
        Route::get('/turfs', [AdminController::class, 'manageTurfs'])->name('turfs');
        Route::get('/bookings', [AdminController::class, 'manageBookings'])->name('bookings');
        Route::post('/manage', [AdminController::class, 'manage']);
        Route::get('/create/{type}', [AdminController::class, 'create'])->name('create');
        Route::post('/store/{type}', [AdminController::class, 'store'])->name('store');
        Route::get('/show/{type}/{id}', [AdminController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [AdminController::class, 'edit'])->name('edit');
        Route::put('/update/{type}/{id}', [AdminController::class, 'update'])->name('update');
        Route::delete('/destroy/{type}/{id}', [AdminController::class, 'destroy'])->name('destroy');
        Route::get('/users/filter', [AdminController::class, 'filterUsers'])->name('filterUsers');
        Route::get('/users/search', [AdminController::class, 'searchUsers'])->name('searchUsers');
    });

    Route::post('/logout', function (Request $request) {
        auth()->logout();
        return redirect()->route('welcome');
    })->name('logout');

    Route::put('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
});

Route::post('/newsletter/subscribe', [UserController::class, 'subscribe'])->name('newsletter.subscribe');

Route::middleware('auth')->group(function () {
    Route::get('/user/bookings', [UserController::class, 'bookings'])->name('user.bookings');
    Route::patch('/booking/{id}/cancel', [UserController::class, 'cancel'])->name('booking.cancel');
});
