<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\CampaignController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\DeviceController;
use App\Http\Controllers\Web\MessageController;
use App\Http\Controllers\Web\SettingsController;
use App\Http\Controllers\Web\TemplateController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/guide', function () { return view('guide'); })->name('guide');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');

    // Contacts
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');
    Route::put('/contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');
    Route::post('/contacts/import', [ContactController::class, 'import'])->name('contacts.import');

    // Templates
    Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
    Route::post('/templates', [TemplateController::class, 'store'])->name('templates.store');
    Route::put('/templates/{template}', [TemplateController::class, 'update'])->name('templates.update');
    Route::delete('/templates/{template}', [TemplateController::class, 'destroy'])->name('templates.destroy');

    // Campaigns
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::post('/campaigns/{campaign}/start', [CampaignController::class, 'start'])->name('campaigns.start');
    Route::post('/campaigns/{campaign}/pause', [CampaignController::class, 'pause'])->name('campaigns.pause');
    Route::post('/campaigns/{campaign}/cancel', [CampaignController::class, 'cancel'])->name('campaigns.cancel');

    // Devices
    Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
    Route::post('/devices/{device}/toggle', [DeviceController::class, 'toggleActive'])->name('devices.toggle');
    Route::delete('/devices/{device}', [DeviceController::class, 'destroy'])->name('devices.destroy');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/regenerate-api-key', [SettingsController::class, 'regenerateApiKey'])->name('settings.regenerateApiKey');
    Route::post('/settings/webhook', [SettingsController::class, 'updateWebhook'])->name('settings.updateWebhook');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
