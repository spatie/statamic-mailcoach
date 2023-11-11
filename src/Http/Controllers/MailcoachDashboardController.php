<?php

namespace Spatie\StatamicMailcoach\Http\Controllers;

use Illuminate\Support\Str;
use Spatie\MailcoachSdk\Exceptions\Unauthorized;
use Spatie\MailcoachSdk\Facades\Mailcoach;

class MailcoachDashboardController
{
    public function __invoke()
    {
        if (config('statamic.mailcoach.api_token') && config('statamic.mailcoach.api_url')) {
            try {
                $campaigns = Mailcoach::campaigns();
                $lists = Mailcoach::emailLists();
            } catch (Unauthorized) {
                session()->flash('error', __('Your Mailcoach URL or API Token is invalid.'));

                return redirect()->action([MailcoachSettingsController::class, 'show']);
            }
        } else {
            session()->flash('error', __('You need to set a Mailcoach URL and API Token first.'));

            return redirect()->action([MailcoachSettingsController::class, 'show']);
        }

        return view('statamic-mailcoach::dashboard', [
            'title' => 'Mailcoach',
            'baseUrl' => Str::before(config('statamic.mailcoach.api_url'), '/api'),
            'campaigns' => $campaigns,
            'lists' => $lists,
        ]);
    }
}
