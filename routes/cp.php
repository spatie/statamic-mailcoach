<?php

use Spatie\StatamicMailcoach\Http\Controllers\MailcoachDashboardController;

Route::get('/mailcoach', '\\'.MailcoachDashboardController::class)->name('mailcoach.index');
