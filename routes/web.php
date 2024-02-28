<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ListingController;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(["verify" => true]);

Route::get('/', [ListingController::class, 'index']);
Route::get('listing/{listing:slug}', [ListingController::class, 'show'])->name('listing-listing:slug');

Route::group(['controller' => ListingController::class, "middleware" => "auth", "prefix" => 'listings', "as" => "listings."], function () {
    Route::get('create', 'create')->name("create");
    Route::post('create', 'store')->name("store");
    Route::get('{listing:slug}/edit', 'edit')->name("edit");
    Route::patch('{listing:slug}', 'update')->name("update");
    Route::delete('{listing:slug}/delete', 'destroy')->name("delete");
    Route::get('user', 'show_user_listings')->name("user-listings");
});

Route::group(["middleware" => "can:admin", "as" => "admin."], function () {
    Route::get('listings/{listing:slug}/edit', [ListingController::class, 'edit'])->name("listing.edit");
    Route::get('admin/categories/create', [CategoryController::class, 'create'])->name("categories.create");
    Route::post('admin/categories/store', [CategoryController::class, 'store'])->name("categories.store");
});

Route::get('/logout', function () {
    Auth::logout();
    return Redirect::to(RouteServiceProvider::HOME);
});
