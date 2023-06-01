<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\MyCourseController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\ShowcaseController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function(){
    return view('auth.login');
});

// admin route
Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function(){
    // admin dashboard route
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    // admin category route
    Route::resource('/category', CategoryController::class);
    // admin course route
    Route::resource('/course', CourseController::class);
    Route::get('/my-course', MyCourseController::class)->name('mycourse');
    // admin user route
    Route::controller(UserController::class)->as('user.')->group(function(){
        Route::get('/user/profile', 'profile')->name('profile');
        Route::put('/user/profile/{user}', 'profileUpdate')->name('profile.update');
        Route::put('/user/profile/password/{user}', 'profile')->name('profile.password');
    });
    Route::resource('/user', UserController::class)->only('index', 'update', 'destroy');
    // admin video route
    Route::controller(VideoController::class)->as('video.')->group(function(){
        Route::get('/{course:slug}/video', 'index')->name('index');
        Route::get('/{course:slug}/create', 'create')->name('create');
        Route::post('/{course:slug}/store', 'store')->name('store');
        Route::get('/edit/{course:slug}/{video}', 'edit')->name('edit');
        Route::put('/update/{course:slug}/{video}', 'update')->name('update');
        Route::delete('/delete/{video}', 'destroy')->name('destroy');
    });
    // admin showcase route
    Route::get('/showcase', ShowcaseController::class)->name('showcase.index');
    // admin review route
    Route::controller(ReviewController::class)->group(function(){
        Route::get('/review', 'index')->name('review.index');
        Route::post('/review/{course}', 'store')->name('review');
    });
    // admin transaction route
    Route::resource('/transaction', TransactionController::class)->only('index', 'show');
});