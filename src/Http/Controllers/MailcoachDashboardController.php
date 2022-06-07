<?php

namespace Spatie\StatamicMailcoach\Http\Controllers;

use Spatie\Mailcoach\Http\App\Queries\CampaignsQuery;
use Spatie\Mailcoach\Http\App\Queries\EmailListQuery;
use Spatie\Mailcoach\Http\App\Queries\TemplatesQuery;
use Spatie\Mailcoach\Domain\Campaign\Models\Campaign;
use Spatie\Mailcoach\Domain\Audience\Models\EmailList;
use Spatie\Mailcoach\Domain\Campaign\Models\Template;

class MailcoachDashboardController
{
    public function __invoke(CampaignsQuery $campaignsQuery, EmailListQuery $listQuery, TemplatesQuery $templatesQuery)
    {
        return view('statamic-mailcoach::dashboard', [
            'title' => 'Mailcoach',
            'campaigns' => $campaignsQuery->get(),
            'lists' => $listQuery->get(),
            'templates' => $templatesQuery->get(),
            'totalCampaignsCount' => Campaign::count(),
            'totalListsCount' => EmailList::count(),
            'totalTemplatesCount' => Template::count(),
        ]);
    }
}
