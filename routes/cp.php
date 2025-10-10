<?php

use Spatie\StatamicMailcoach\Http\Controllers\MailcoachDashboardController;
use Spatie\StatamicMailcoach\Http\Controllers\MailcoachSettingsController;

Route::get('/mailcoach/dashboard', '\\'.MailcoachDashboardController::class)->name('statamic-mailcoach.index');
Route::get('/mailcoach/settings', ['\\'.MailcoachSettingsController::class, 'show'])->name('statamic-mailcoach.settings');
Route::patch('/mailcoach/settings', ['\\'.MailcoachSettingsController::class, 'update'])->name('statamic-mailcoach.settings.update');
