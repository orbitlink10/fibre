<?php

use App\Http\Controllers\Admin\ProviderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\FiberToolController as AdminFiberToolController;
use App\Http\Controllers\Admin\HomepageContentController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CategoryController as PublicCategoryController;
use App\Http\Controllers\FiberToolController;
use App\Http\Controllers\ProductController as PublicProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TechnicianController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', function () {
    $relevantCategoryTerms = [
        'fiber',
        'fibre',
        'optic',
        'cable',
        'starlink',
        'network',
        'wi-fi',
        'wifi',
        'router',
        'switch',
        'cctv',
        'security',
        'isp',
        'billing',
        'accessories',
        'internet',
        'satellite',
    ];
    $urls = collect([
        [
            'loc' => route('home'),
            'priority' => '1.0',
            'changefreq' => 'daily',
        ],
        [
            'loc' => route('technicians.public'),
            'priority' => '0.8',
            'changefreq' => 'weekly',
        ],
    ]);

    $urls = $urls
        ->merge(\App\Models\Category::query()
            ->where('is_active', true)
            ->where(function ($query) use ($relevantCategoryTerms) {
                foreach ($relevantCategoryTerms as $term) {
                    $query->orWhere('name', 'like', '%'.$term.'%')
                        ->orWhere('slug', 'like', '%'.$term.'%');
                }
            })
            ->get()
            ->map(fn ($category) => [
                'loc' => route('categories.show', ['category' => $category->slug]),
                'priority' => '0.8',
                'changefreq' => 'weekly',
            ]))
        ->merge(\App\Models\Product::query()
            ->latest('updated_at')
            ->get()
            ->map(fn ($product) => [
                'loc' => route('products.show', ['product' => $product->slug]),
                'priority' => '0.7',
                'changefreq' => 'weekly',
            ]))
        ->merge(collect(FiberToolController::slugs())
            ->map(fn ($slug) => [
                'loc' => url($slug),
                'priority' => '0.7',
                'changefreq' => 'monthly',
            ]));

    if (Storage::disk('local')->exists('pages.json')) {
        $urls = $urls->merge(collect(json_decode(Storage::disk('local')->get('pages.json'), true) ?: [])
            ->filter(fn ($page) => ! empty($page['slug']))
            ->map(fn ($page) => [
                'loc' => route('pages.preview', $page['slug']),
                'priority' => ($page['type'] ?? 'Post') === 'Page' ? '0.8' : '0.6',
                'changefreq' => 'monthly',
            ]));
    }

    $xml = view('sitemap', [
        'urls' => $urls->unique('loc')->values(),
    ])->render();

    return response($xml, 200)->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::get('/', HomeController::class)->name('home');
Route::get('/shop/{product:slug}', [PublicProductController::class, 'show'])->name('products.show');
Route::get('/category/{category:slug}', [PublicCategoryController::class, 'show'])->name('categories.show');
Route::get('/categories/{category:slug}', fn (App\Models\Category $category) => redirect()->route('categories.show', ['category' => $category->slug], 301));
Route::get('/fiber-technicians', [TechnicianController::class, 'publicIndex'])->name('technicians.public');
foreach (FiberToolController::slugs() as $fiberToolSlug) {
    Route::get('/'.$fiberToolSlug, [FiberToolController::class, 'show'])
        ->defaults('slug', $fiberToolSlug)
        ->name('tools.'.$fiberToolSlug);
}
Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::post('/cart/items/{product}', [CartController::class, 'store'])->name('cart.items.store');
Route::patch('/cart/items/{product}', [CartController::class, 'update'])->name('cart.items.update');
Route::delete('/cart/items/{product}', [CartController::class, 'destroy'])->name('cart.items.destroy');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [PasswordResetController::class, 'request'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'email'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'reset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
});

Route::post('/mpesa/callback', [PaymentController::class, 'callback'])->name('mpesa.callback');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/technicians', [TechnicianController::class, 'index'])->name('technicians.index');
    Route::post('/technicians', [TechnicianController::class, 'store'])->name('technicians.store');
    Route::put('/technicians', [TechnicianController::class, 'update'])->name('technicians.update');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    Route::resource('bookings', BookingController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
    Route::post('/bookings/{booking}/payments/mpesa', [PaymentController::class, 'initiate'])->name('payments.mpesa');
    Route::post('/bookings/{booking}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    Route::middleware('role:provider')->group(function () {
        Route::patch('/provider/jobs/{booking}', [AssignmentController::class, 'providerAction'])->name('provider.jobs.update');
    });

    Route::middleware('role:admin,dispatcher')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('services', ServiceController::class)->except(['show']);
        Route::resource('providers', ProviderController::class)->except(['show']);
        Route::get('/menu', [MenuController::class, 'edit'])->name('menu.edit');
        Route::put('/menu', [MenuController::class, 'update'])->name('menu.update');
        Route::get('/homepage-content', [HomepageContentController::class, 'edit'])->name('homepage.edit');
        Route::put('/homepage-content', [HomepageContentController::class, 'update'])->name('homepage.update');
        Route::get('/bookings/{booking}/assign', [AssignmentController::class, 'edit'])->name('assignments.edit');
        Route::patch('/bookings/{booking}/assign', [AssignmentController::class, 'update'])->name('assignments.update');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/fiber-tools', [AdminFiberToolController::class, 'index'])->name('fiber-tools.index');
    });

    Route::middleware('role:admin,dispatcher')->name('admin.')->group(function () {
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('products', ProductController::class)->except(['show']);
        Route::resource('testimonials', TestimonialController::class)->except(['show']);
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/pages', [PageController::class, 'index'])->name('pages');
        Route::get('/new-post', [PageController::class, 'create'])->name('pages.create');
        Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
        Route::get('/pages/{id}/edit', [PageController::class, 'edit'])->whereNumber('id')->name('pages.edit');
        Route::put('/pages/{id}', [PageController::class, 'update'])->whereNumber('id')->name('pages.update');
        Route::delete('/pages/{id}', [PageController::class, 'destroy'])->whereNumber('id')->name('pages.destroy');
    });
});

Route::get('/page/{slug}', fn (string $slug) => redirect('/'.$slug, 301));
Route::get('/page-images/{path}', [PageController::class, 'image'])
    ->where('path', '.*')
    ->name('pages.image');
Route::get('/uploaded-images/{path}', function (string $path) {
    $path = ltrim(rawurldecode($path), '/');

    abort_if($path === '' || str_contains($path, '..') || ! Storage::disk('public')->exists($path), 404);

    return response(Storage::disk('public')->get($path), 200)
        ->header('Content-Type', Storage::disk('public')->mimeType($path) ?: 'application/octet-stream')
        ->header('Cache-Control', 'public, max-age=604800');
})->where('path', '.*')->name('media.image');

Route::redirect('/satellite-internet-providers-in-kenya', '/fiber-optic-internet-kenya', 301);
Route::redirect('/amazon-leo-internet-latency-in-kenya', '/fiber-troubleshooting-wizard', 301);
Route::redirect('/amazon-leo-internet-packages-in-kenya', '/fiber-optic-internet-kenya', 301);
Route::redirect('/amazon-leo-internet-prices-kenya', '/category/fiber-optic-cable-prices-in-kenya', 301);
Route::redirect('/amazon-leo-internet-speeds-in-kenya', '/fiber-bandwidth-calculator', 301);

Route::get('/{slug}', [PageController::class, 'preview'])
    ->where('slug', '^(?!admin|bookings|category|categories|customers|dashboard|forgot-password|login|logout|new-post|orders|page|pages|password|products|profile|register|storage|testimonials).+')
    ->name('pages.preview');
