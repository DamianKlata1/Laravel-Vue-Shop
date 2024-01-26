<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\User\ProductController as UserProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\UserHomeController;
use App\Http\Controllers\User\WishlistController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminAuthController;

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------

*/

Route::middleware('trackVisitor')->group(function () {
    Route::get('/',[UserHomeController::class, 'index'])->name('user.home');



    Route::middleware(['auth','verified'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('user.dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::prefix('checkout')->controller(CheckoutController::class)->group(function () {
            Route::post('/order', 'store')->name('checkout.store');
            Route::get('/success', 'success')->name('checkout.success');
            Route::post('/cancel', 'cancel')->name('checkout.cancel');
        });
        Route::prefix('wishlist')->controller(WishlistController::class)->group(function () {
            Route::get('/view', 'view')->name('wishlist.view');
            Route::post('/store/{product}', 'store')->name('wishlist.store');
            Route::delete('/delete/{wishlistItem}', 'delete')->name('wishlist.delete');
        });
        Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('review.store');
        Route::post('/reviews/{review}/helpful-toggle', [ReviewController::class, 'toggleMarkAsHelpful'])->name('review.helpful');
        Route::delete('/reviews/{review}/delete', [ReviewController::class, 'destroy'])->name('review.delete');
    });

    Route::prefix('cart')->controller(CartController::class)->group(function () {
        Route::get('/view', 'view')->name('cart.view');
        Route::post('/store/{product}', 'store')->name('cart.store');
        Route::patch('/update/{product}', 'update')->name('cart.update');
        Route::delete('/delete/{product}', 'delete')->name('cart.delete');
    });

    Route::prefix('products')->controller(UserProductController::class)->group(function () {
        Route::get('/{product}', 'showDetails')->name('products.showDetails');
        Route::get('', 'list')->name('products.list');
    });
});



/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------

*/
Route::group(['prefix' => 'admin', 'middleware' => 'redirectAdmin'], function() {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    //products routes
    Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products.index');
    Route::post('/products/store', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::put('/products/update/{id}', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::patch('/products/publish/{id}', [AdminProductController::class, 'publish'])->name('admin.products.publish');
    Route::delete('/products/delete/{id}', [AdminProductController::class, 'delete'])->name('admin.products.delete');
    Route::delete('/products/image/delete/{id}', [AdminProductController::class, 'imageDelete'])->name('admin.products.image.delete');

    //categories routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/categories/update/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/delete/{id}', [CategoryController::class, 'delete'])->name('admin.categories.delete');

    //brands routes
    Route::get('/brands', [BrandController::class, 'index'])->name('admin.brands.index');
    Route::post('/brands/store', [BrandController::class, 'store'])->name('admin.brands.store');
    Route::put('/brands/update/{id}', [BrandController::class, 'update'])->name('admin.brands.update');
    Route::delete('/brands/delete/{id}', [BrandController::class, 'delete'])->name('admin.brands.delete');

    //users routes
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::post('/users/store', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::put('/users/update/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::patch('/users/update-password/{id}', [AdminUserController::class, 'updatePassword'])->name('admin.users.update_password');
    Route::delete('/users/delete/{id}', [AdminUserController::class, 'delete'])->name('admin.users.delete');

    //reviews routes
});



require __DIR__.'/auth.php';
