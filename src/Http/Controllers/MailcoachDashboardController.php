<?php

namespace Spatie\StatamicMailcoach\Http\Controllers;

use Illuminate\Support\Str;
use Spatie\Mailcoach\Http\App\Queries\CampaignsQuery;
use Spatie\Mailcoach\Http\App\Queries\EmailListQuery;
use Spatie\Mailcoach\Http\App\Queries\TemplatesQuery;
use Spatie\Mailcoach\Models\Campaign;
use Spatie\Mailcoach\Models\EmailList;
use Spatie\Mailcoach\Models\Template;
use Spatie\MailcoachSdk\Facades\Mailcoach;

class MailcoachDashboardController
{
    public function __invoke()
    {
        $campaigns = Mailcoach::campaigns();

        $lists = Mailcoach::emailLists();

        return view('statamic-mailcoach::dashboard', [
            'title' => 'Mailcoach',
            'baseUrl' => Str::before(config('statamic.mailcoach.api_url'), '/api'),
            'campaigns' => $campaigns,
            'lists' => $lists,
        ]);
    }
}
