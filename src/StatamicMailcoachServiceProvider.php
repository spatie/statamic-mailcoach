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
                ->icon('<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="-5 -5 52 50"><path d="M8.9 26.769a4.842 4.842 0 0 1 3.438 1.419 4.805 4.805 0 0 1 1.406 3.425c0 1.303-.475 2.465-1.338 3.362l-.072.072A4.811 4.811 0 0 1 8.9 36.453c-1.313 0-2.528-.5-3.434-1.406a4.811 4.811 0 0 1-1.407-3.434c0-1.313.5-2.529 1.407-3.435a4.825 4.825 0 0 1 3.43-1.41m.007-3.437h-.01c-2.221 0-4.302.86-5.856 2.416a8.232 8.232 0 0 0-2.415 5.866c0 2.228.856 4.309 2.416 5.865a8.227 8.227 0 0 0 5.865 2.416c2.228 0 4.31-.856 5.866-2.416l.069-.069.025-.025.024-.025a8.244 8.244 0 0 0 2.297-5.747 8.298 8.298 0 0 0-2.415-5.853 8.269 8.269 0 0 0-5.857-2.425h-.009v-.003ZM33.09 26.769a4.851 4.851 0 0 1 3.435 1.419 4.802 4.802 0 0 1 1.413 3.421c0 1.3-.5 2.529-1.407 3.435a4.814 4.814 0 0 1-3.434 1.41c-1.313 0-2.528-.5-3.434-1.407a4.811 4.811 0 0 1-1.407-3.434c0-1.313.5-2.529 1.407-3.435a4.825 4.825 0 0 1 3.43-1.41m.007-3.437h-.01c-2.221 0-4.303.86-5.856 2.416a8.226 8.226 0 0 0-2.415 5.866c0 2.228.856 4.306 2.415 5.865a8.226 8.226 0 0 0 5.866 2.416c2.228 0 4.278-.844 5.831-2.381h.003l.032-.035a8.237 8.237 0 0 0 2.415-5.866c0-2.224-.86-4.296-2.419-5.853a8.276 8.276 0 0 0-5.85-2.425h-.009l-.003-.003ZM33.622 5.547l-1.178 10.428c-4.2 1.166-8.135 3.34-11.444 6.319-3.31-2.978-7.24-5.153-11.444-6.319L8.378 5.547 21 10.975l12.622-5.428ZM4.3.05l.666 5.884 1.178 10.428.26 2.307 2.237.619c3.671 1.018 7.153 2.94 10.062 5.559l2.3 2.069 2.3-2.07c2.91-2.618 6.388-4.54 10.063-5.558l2.237-.62.26-2.305L37.04 5.934 37.706.05 32.27 2.39 21.006 7.235 9.744 2.391 4.306.05H4.3Z"/></svg>')
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
