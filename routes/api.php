<?php

use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\DetailsController;
use App\Http\Controllers\Api\FooterController;
use App\Http\Controllers\Api\FooterPageController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\LandingPageController;
use App\Http\Controllers\Api\MenuPageController;
use App\Http\Controllers\Api\MetaDataController;
use App\Http\Controllers\Api\OrderController;
// use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PriceRangeController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('product',[ProductController::class, 'index']);//

Route::get('product/{slug}', [DetailsController::class, 'index']);
Route::get('cart/{id}', [CartController::class, 'index']);
Route::post('order', [OrderController::class, 'store']);
Route::get('home', [HomeController::class, 'index']); //
Route::get('footer', [FooterController::class, 'index']);
Route::get('footer-menu-page/{slug}', [FooterPageController::class, 'footerMenuPage']);
Route::get('top-menu', [MenuPageController::class, 'topMenu']);
Route::get('availability/{slug}/{stock}', [ProductController::class, 'stock']);
Route::get('sort-product/{slug}', [MenuPageController::class, 'sortProduct']);

// Route::get('availability/{availability}', [ProductController::class, 'stock']);
Route::get('search-product', [SearchController::class, 'search']);
Route::get('price-range-product', [PriceRangeController::class, 'priceRangeProduct']);
Route::get('blog', [BlogController::class, 'index']);
Route::get('blog-details/{slug}', [BlogController::class, 'blogDetails']);

//meta
Route::get('home-meta', [MetaDataController::class, 'metaHome']);//
Route::get('category-meta/{slug}', [MetaDataController::class, 'categoryMetaDate']);
Route::get('product-meta/{slug}', [MetaDataController::class, 'productMetaDate']);
Route::get('about-meta', [MetaDataController::class, 'aboutMetaDate']);
Route::get('landing-page', [LandingPageController::class, 'landingPage']);
// Route::get('footer-menu-page-meta/{slug}', [MetaDataController::class, 'footerMenuPageMetaData']);


// Route::get('contact-us', [PageController::class, 'contactUs']);
Route::get('order-summary/{id}', [OrderController::class, 'orderSummary']);
Route::get('/{slug}', [MenuPageController::class, 'menuPage']);
