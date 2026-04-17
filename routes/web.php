<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Users\{UserIndex, UserCreate, UserEdit, UserShow};
use App\Livewire\Products\{ProductIndex, ProductCreate, ProductEdit, ProductShow};
use App\Livewire\Roles\{RolesIndex, RolesCreate, RolesEdit, RolesShow};
use App\Livewire\Category\{CategoryIndex, CategoryCreate, CategoryEdit};
use App\Http\Controllers\{SwitchUserController,ProductController};
use App\Http\Controllers\EditProfileController;
use App\Livewire\Staff\{StaffCreate, StaffEdit};
use App\Livewire\Staff\StaffIndex;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\BuyNowController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\ShopController;

  
// Route::get('/', function (){
//     return redirect()->route('login');
// })->name('home');
// Route::get('/', function () {
//     return view('welcome');
// })

Route::get('/', [ProductController::class, 'frontend'])->name('home');
Route::get('/dashboard',[SwitchUserController::class,'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('users', UserIndex::class)->name('users.index')->middleware("permission:User Show|User Add|User Edit|User Delete");
Route::get('users/create', UserCreate::class)->name('users.create')->middleware("permission:User Add");
Route::get('users/{id}/edit', UserEdit::class)->name('users.edit')->middleware("permission:User Edit");

Route::get('products', ProductIndex::class)->name('products.index')->middleware("permission:Product Show|Product Add|Product Edit|Product Delete");
Route::get('products/create', ProductCreate::class)->name('products.create')->middleware("permission:Product Add");
Route::get('products/{id}/edit', ProductEdit::class)->name('products.edit')->middleware("permission:Product Edit");
Route::get('products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('roles', RolesIndex::class)->name('roles.index')->middleware("permission:Role Show|Role Add|Role Edit|Role Delete");
Route::get('roles/create', RolesCreate::class)->name('roles.create')->middleware("permission:Role Add");
Route::get('roles/{id}/edit', RolesEdit::class)->name('roles.edit')->middleware("permission:Role Edit");

Route::get('category',  CategoryIndex::class)->name('category.index')->middleware("permission:Category Show|Category Add|Category Edit|Category Delete");
Route::get('category/create', CategoryCreate::class)->name('category.create')->middleware("permission:Category Add");
Route::get('category/{id}/edit', CategoryEdit::class)->name('category.edit')->middleware("permission:Category Edit");

Route::get('staff', StaffIndex::class)->name('staff.index');
Route::get('staff/create', StaffCreate::class)->name('staff.create');
Route::get('staff/{id}/edit', StaffEdit::class)->name('staff.edit');

Route::middleware(['auth'])->group(function () {
Route::post('/admin/switch-user/{user}', [SwitchUserController::class, 'switchTo'])->name('admin.switchUser');
Route::get('/admin/switch-back', [SwitchUserController::class, 'switchBack'])->name('admin.switch.back');
});

Route::middleware('auth')->group(function () {
Route::get('/profile/edit', [EditProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/update', [EditProfileController::class, 'update'])->name('profile.update');
});
Route::middleware('auth')->group(function() {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
});
Route::middleware('auth')->group(function () {
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('wishlist/add/{id}', [WishlistController::class, 'add'])->name('wishlist.add');
Route::post('wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
Route::post('/wishlist/add-to-cart-and-remove/{productId}', [WishlistController::class, 'addToCartAndRemove'])->name('wishlist.addToCartAndRemove');
});
Route::middleware('auth')->group(function () {
Route::post('/buy-now', [BuyNowController::class, 'buyNow'])->name('buy.now');
Route::get('/buy-now/checkout', [BuyNowController::class, 'checkout'])->name('buy.now.checkout');
Route::post('/buy-now/place-order', [BuyNowController::class, 'placeOrder'])->name('buy.now.placeOrder');
});

Route::get('/paypal/payment', [PayPalController::class, 'payWithPayPal'])->name('paypal.payment');
Route::get('/paypal/success', [PayPalController::class, 'paymentSuccess'])->name('paypal.success');
Route::get('/paypal/cancel', [PayPalController::class, 'paymentCancel'])->name('paypal.cancel');

Route::get('/shop/page',[ShopController::class,'index'])->name('shop.shop');

require __DIR__.'/auth.php';
