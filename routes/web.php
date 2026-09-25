<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\AppController;
use App\Http\Controllers\Admin\AppScreenshotController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\AppController as PublicAppController;
use App\Http\Controllers\Site\PortfolioController;
use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Admin\WebsiteSettingsController;
use App\Http\Controllers\Admin\HomepageContentController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\ContactLinkController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/apps', [PublicAppController::class, 'index'])->name('apps.index');
Route::get('/apps/{slug}', [PublicAppController::class, 'show'])->name('apps.show');
Route::get('/portfolio/{slug}', [PortfolioController::class, 'show'])->name('portfolio.show');
Route::post('/contact', ContactController::class)->middleware('throttle:5,1')->name('contact.store');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'create'])
            ->name('login');

        Route::post('/login', [LoginController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('login.store');
    });

    Route::middleware(['auth', EnsureUserIsAdmin::class])->group(function () {
        Route::get('/website/settings', [WebsiteSettingsController::class, 'edit'])->name('website.settings.edit');
        Route::put('/website/settings', [WebsiteSettingsController::class, 'update'])->name('website.settings.update');
        Route::get('/website/homepage', [HomepageContentController::class, 'edit'])->name('website.homepage.edit');
        Route::put('/website/homepage', [HomepageContentController::class, 'update'])->name('website.homepage.update');

        Route::post('/services/{service}/toggle', [ServiceController::class, 'toggle'])->name('services.toggle');
        Route::resource('services', ServiceController::class)->except('show');
        Route::post('/portfolio/{project}/toggle', [AdminPortfolioController::class, 'toggle'])->name('portfolio.toggle');
        Route::resource('portfolio', AdminPortfolioController::class)->parameters(['portfolio' => 'project'])->except('show');

        Route::get('/messages', [ContactMessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{message}', [ContactMessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{message}/toggle', [ContactMessageController::class, 'toggle'])->name('messages.toggle');
        Route::delete('/messages/{message}', [ContactMessageController::class, 'destroy'])->name('messages.destroy');

        Route::post('/contact-links/{contactLink}/toggle', [ContactLinkController::class, 'toggle'])->name('contact-links.toggle');
        Route::post('/contact-links/reorder', [ContactLinkController::class, 'reorder'])->name('contact-links.reorder');
        Route::post('/contact-links/{contactLink}/move', [ContactLinkController::class, 'move'])->name('contact-links.move');
        Route::resource('contact-links', ContactLinkController::class)->except('show');

        Route::get('/apps', [AppController::class, 'index'])->name('apps.index');
        Route::get('/apps/create', [AppController::class, 'create'])->name('apps.create');
        Route::get('/apps/{app}/screenshots', [AppScreenshotController::class, 'index'])->name('apps.screenshots.index');
        Route::post('/apps/{app}/screenshots', [AppScreenshotController::class, 'store'])->name('apps.screenshots.store');
        Route::post('/apps/{app}/screenshots/reorder', [AppScreenshotController::class, 'reorder'])->name('apps.screenshots.reorder');
        Route::delete('/apps/{app}/screenshots/{screenshot}', [AppScreenshotController::class, 'destroy'])->name('apps.screenshots.destroy');
        Route::post('/apps', [AppController::class, 'store'])->name('apps.store');
        Route::get('/apps/{app}/edit', [AppController::class, 'edit'])->name('apps.edit');
        Route::put('/apps/{app}', [AppController::class, 'update'])->name('apps.update');
        Route::delete('/apps/{app}', [AppController::class, 'destroy'])->name('apps.destroy');

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::post('/logout', [LoginController::class, 'destroy'])
            ->name('logout');
    });
});
