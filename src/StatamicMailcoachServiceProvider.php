<?php

namespace Spatie\StatamicMailcoach;

use Illuminate\Support\Facades\Auth;
use Spatie\StatamicMailcoach\Actions\SubscribeFromRegistrationAction;
use Spatie\StatamicMailcoach\Actions\SubscribeFromSubmissionAction;
use Spatie\StatamicMailcoach\Fieldtypes\MailcoachList;
use Spatie\StatamicMailcoach\Fieldtypes\UserFields;
use Statamic\Events\SubmissionCreated;
use Statamic\Events\UserRegistered;
use Statamic\Facades\CP\Nav;
use Statamic\Facades\Permission;
use Statamic\Providers\AddonServiceProvider;

class StatamicMailcoachServiceProvider extends AddonServiceProvider
{
    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
    ];

    protected $fieldtypes = [
        MailcoachList::class,
        UserFields::class,
    ];

    protected $listen = [
        UserRegistered::class => [SubscribeFromRegistrationAction::class],
        SubmissionCreated::class => [SubscribeFromSubmissionAction::class],
    ];

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/mailcoach.php', 'statamic.mailcoach');

        $this->publishes([
            __DIR__.'/../config/mailcoach.php' => config_path('statamic/mailcoach.php'),
        ], 'statamic-mailcoach-config');
    }

    public function boot()
    {
        parent::boot();

        $this->bootMailcoachAPI();
        $this->bootNav();
        $this->bootPermissions();

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'statamic-mailcoach');
    }

    public function bootMailcoachAPI(): void
    {
        if (config('statamic.mailcoach.api_token') && config('statamic.mailcoach.api_url')) {
            config()->set('mailcoach-sdk.api_token', config('statamic.mailcoach.api_token'));
            config()->set('mailcoach-sdk.endpoint', config('statamic.mailcoach.api_url'));
        }
    }

    public function bootNav(): void
    {
        Nav::extend(function (\Statamic\CP\Navigation\Nav $nav) {
            $nav->content('Mailcoach')
                ->route('statamic-mailcoach.index')
                ->icon('<svg class="h-full mr-2" style="width: 45px" fill="none" viewBox="0 0 570 305" xmlns="http://www.w3.org/2000/svg">
                        <path d="m306.6 98.3-6.1-56.5c23.2-3.6 62-8.9 90.2-8.9s67.5 5.3 91 8.9l-10.4 79.6c-2.8 0.8-5.6 1.6-8.4 2.5 4.3 6 8 12.4 11.1 19.1 5.5-1.6 11.1-3 16.8-4l15-115.1s-70-12.5-115.1-12.5c-45 0-113.8 12.5-113.8 12.5l10.4 95.4c5.9-7.8 12.3-14.9 19.3-21z" fill="url(#c)"/>
                        <path d="m462.9 126c-13.5 4.5-26.5 10.5-38.9 18s-23.9 16.4-34.6 26.5c-10.2-9.6-21.4-18.1-33.4-25.5-14.7-9-30.4-16.1-46.8-21.1l-2.6-24.3c16.2-14.5 35.8-23.4 61.6-23.4 39.1-0.1 73.9 20 94.7 49.8zm17.8 55.2c-19.6 0-35.3 16.2-35.3 36.4s15.7 36.4 35.3 36.4 35.3-16.2 35.3-36.4-15.7-36.4-35.3-36.4zm3.3-40.8s-8.5 2.2-10 2.6c-33 10-62 25-84.5 51.6-25-29.7-60-51.7-100-60.7l-2.1-20.3c-1.3 1.8-2.7 3.7-4 5.6-6-2.6-22.4-5.2-29.4-5.2-26.7 0-48.3 21.3-48.3 47.6-36.4 0.7-65.7 30-65.7 66 0 36.5 30 66.1 67 66.1h274 1.5c43 0 77.8-34.3 77.8-76.7s-33.3-76.6-76.3-76.6zm52.9 77.2c0-32.3-25-58.1-56.3-58.1s-56.3 25.8-56.3 58.1 25 58.1 56.3 58.1 56.3-25.8 56.3-58.1zm-461.7-38.7c-8.4 0-15.2 7-15.2 15.7s6.8 15.7 15.2 15.7 15.2-7 15.2-15.7c0.1-8.7-6.7-15.7-15.2-15.7zm-46.1 35.8c-6.2 0-11.2 5.2-11.2 11.5s5 11.5 11.2 11.5 11.2-5.2 11.2-11.5-5-11.5-11.2-11.5zm65.6 16.2c-12.7 0-23 10.7-23 23.8s10.3 23.8 23 23.8 23-10.7 23-23.8c0.1-13.1-10.3-23.8-23-23.8z" fill="url(#b)" opacity=".25"/>
                        <path d="m289.2 139-2.1-19.6c6-7.9 12.3-14.9 19.4-21l2.6 23.5c16.4 4.9 32.1 11.7 46.8 20.5 12 7.1 23.2 15.4 33.4 24.7 10.7-9.8 22.3-18.4 34.6-25.6 12.4-7.3 25.4-13.1 38.9-17.5 4.3 6 8 12.4 11.1 19.1-33 9.7-62 29-84.5 54.7-25.2-28.8-60.2-50.1-100.2-58.8zm63.8 81c0 31.3-25 56.3-56.3 56.3s-56.3-25-56.3-56.3 25-56.3 56.3-56.3c31.3 0.1 56.3 25.1 56.3 56.3zm-21 0c0-19.6-15.7-35.3-35.3-35.3s-35.3 15.7-35.3 35.3 15.7 35.3 35.3 35.3 35.3-15.7 35.3-35.3zm204.8 0c0 31.3-25 56.3-56.3 56.3s-56.3-25-56.3-56.3 25-56.3 56.3-56.3c31.2 0.1 56.3 25.1 56.3 56.3zm-21 0c0-19.6-15.7-35.3-35.3-35.3s-35.3 15.7-35.3 35.3 15.7 35.3 35.3 35.3 35.3-15.7 35.3-35.3z" fill="url(#a)"/>
                        <defs>
                            <linearGradient id="c" x1="276.89" x2="505.74" y1="77.23" y2="77.23" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#1c2e36" offset=".0012034"/>
                                <stop stop-color="#1c2e36" offset="1"/>
                            </linearGradient>
                            <linearGradient id="b" x1="560.37" x2="17.963" y1="184.98" y2="184.98" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#1c2e36" offset=".0012034"/>
                                <stop stop-color="#1c2e36" offset="1"/>
                            </linearGradient>
                            <linearGradient id="a" x1="240.43" x2="536.76" y1="187.33" y2="187.33" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#1c2e36" offset=".0012034"/>
                                <stop stop-color="#1c2e36" offset="1"/>
                            </linearGradient>
                        </defs>
                    </svg>')
                ->active('mailcoach*')
                ->can('view mailcoach')
                ->children(array_filter([
                    'Dashboard' => config('statamic.mailcoach.api_token') && config('statamic.mailcoach.api_url')
                        ? cp_route('statamic-mailcoach.index')
                        : null,
                    'Settings' => Auth::user()->can('mailcoach settings')
                        ? cp_route('statamic-mailcoach.settings')
                        : null,
                ]));
        });
    }

    protected function bootPermissions(): void
    {
        Permission::group('mailcoach', 'Mailcoach', function () {
            Permission::register('view mailcoach', function ($permission) {
                $permission
                    ->label('View Mailcoach')
                    ->children([
                        Permission::make('mailcoach settings')
                            ->label('Mailcoach Settings'),
                    ]);
            });
        });
    }
}
