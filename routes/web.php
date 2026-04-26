<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('product.show');

// Cart Routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::post('/coupon/apply', [CartController::class, 'applyCoupon'])->name('coupon.apply');
});

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/place', [CheckoutController::class, 'placeOrder'])->name('checkout.place');
Route::get('/order/success/{number}', [CheckoutController::class, 'success'])->name('order.success');

// Order Tracking
Route::get('/track', [\App\Http\Controllers\OrderTrackingController::class, 'index'])->name('track.index');
Route::get('/track/status', [\App\Http\Controllers\OrderTrackingController::class, 'track'])->name('track.status');

// Legal & Info Pages
Route::get('/shipping-policy', function() { return view('pages.shipping'); })->name('pages.shipping');
Route::get('/privacy-policy', function() { return view('pages.privacy'); })->name('pages.privacy');

// SEO Dynamic Sitemap
Route::get('/sitemap.xml', function () {
    $products = \App\Models\Product::where('is_active', true)->get();
    $categories = \App\Models\Category::where('is_active', true)->get();
    
    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
    
    $urls = [
        ['loc' => route('home'), 'priority' => '1.0', 'changefreq' => 'daily'],
        ['loc' => route('shop'), 'priority' => '0.9', 'changefreq' => 'daily'],
        ['loc' => route('pages.shipping'), 'priority' => '0.5', 'changefreq' => 'monthly'],
        ['loc' => route('pages.privacy'), 'priority' => '0.5', 'changefreq' => 'monthly'],
        ['loc' => route('track.index'), 'priority' => '0.6', 'changefreq' => 'monthly'],
    ];

    foreach ($urls as $url) {
        $xml .= '<url>';
        $xml .= '<loc>' . $url['loc'] . '</loc>';
        $xml .= '<lastmod>' . now()->tz('UTC')->toAtomString() . '</lastmod>';
        $xml .= '<changefreq>' . $url['changefreq'] . '</changefreq>';
        $xml .= '<priority>' . $url['priority'] . '</priority>';
        $xml .= '</url>';
    }

    foreach ($categories as $cat) {
        $xml .= '<url>';
        $xml .= '<loc>' . htmlspecialchars(route('shop') . '?category=' . $cat->slug, ENT_XML1, 'UTF-8') . '</loc>';
        $xml .= '<lastmod>' . $cat->updated_at->tz('UTC')->toAtomString() . '</lastmod>';
        $xml .= '<changefreq>weekly</changefreq>';
        $xml .= '<priority>0.8</priority>';
        $xml .= '</url>';
    }

    foreach ($products as $product) {
        $xml .= '<url>';
        $xml .= '<loc>' . route('product.show', $product->slug) . '</loc>';
        $xml .= '<lastmod>' . $product->updated_at->tz('UTC')->toAtomString() . '</lastmod>';
        $xml .= '<changefreq>weekly</changefreq>';
        $xml .= '<priority>0.9</priority>';
        if ($product->thumbnail) {
            $xml .= '<image:image><image:loc>' . htmlspecialchars(\Illuminate\Support\Facades\Storage::url($product->thumbnail), ENT_XML1, 'UTF-8') . '</image:loc></image:image>';
        }
        $xml .= '</url>';
    }

    $xml .= '</urlset>';

    return response($xml, 200)->header('Content-Type', 'text/xml');
});

// Storage file proxy (for hosts that disable symlinks)
Route::get('storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);

    if (!file_exists($fullPath)) {
        abort(404);
    }

    $mime = mime_content_type($fullPath);
    $headers = [
        'Content-Type' => $mime,
        'Cache-Control' => 'public, max-age=604800',
    ];

    return response()->file($fullPath, $headers);
})->where('path', '.*')->name('storage.proxy');
