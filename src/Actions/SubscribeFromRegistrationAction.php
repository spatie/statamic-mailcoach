<?php

namespace Spatie\StatamicMailcoach\Actions;

use Spatie\MailcoachSdk\Facades\Mailcoach;
use Statamic\Events\UserRegistered;
use Statamic\Support\Arr;

class SubscribeFromRegistrationAction
{
    public function handle(UserRegistered $event): void
    {
        if (! config('statamic.mailcoach.add_new_users')) {
            return;
        }

        /** @var \Statamic\Contracts\Auth\User $user */
        $user = $event->user;
        $emailField = config('statamic.mailcoach.users.primary_email_field', 'email');

        $attributes = collect(config('statamic.mailcoach.users.attributes', []))
            ->mapWithKeys(function (array $attribute) use ($user) {
                $handle = $attribute['value'];

                return [$attribute['key'] => $user->$handle ?? $handle];
            })->toArray();

        if ((config('statamic.mailcoach.users.check_consent') ?? false) && ! request(config('statamic.mailcoach.users.check_consent_field'))) {
            return;
        }

        if (! config('statamic.mailcoach.users.email_list_uuid')) {
            return;
        }

        Mailcoach::createSubscriber(
            config('statamic.mailcoach.users.email_list_uuid'),
            [
                'extra_attributes' => Arr::except($attributes, ['first_name', 'last_name']),
                'first_name' => $attributes['first_name'] ?? null,
                'last_name' => $attributes['last_name'] ?? null,
                'email' => $user->$emailField,
                'tags' => config('statamic.mailcoach.users.tags', []),
                'skip_confirmation' => config('statamic.mailcoach.users.disable_double_opt_in'),
            ],
        );
    }
}
