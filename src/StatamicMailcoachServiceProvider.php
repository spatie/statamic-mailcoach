<?php

namespace Spatie\StatamicMailcoach;

use Illuminate\Support\Facades\View;
use Spatie\Mailcoach\Http\App\ViewComposers\QueryStringComposer;
use Statamic\Facades\CP\Nav;
use Statamic\Providers\AddonServiceProvider;

class StatamicMailcoachServiceProvider extends AddonServiceProvider
{
    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
    ];

    public function boot()
    {
        parent::boot();

        View::composer('statamic-mailcoach::*', QueryStringComposer::class);

        Nav::extend(function ($nav) {
            $nav->content('Mailcoach')
                ->route('mailcoach.index')
                ->icon('content-writing');
        });

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'statamic-mailcoach');
    }
}
