<?php

namespace Spatie\StatamicMailcoach\Actions;

use Spatie\MailcoachSdk\Exceptions\InvalidData;
use Spatie\MailcoachSdk\Facades\Mailcoach;
use Statamic\Events\SubmissionCreated;
use Statamic\Support\Arr;

class SubscribeFromSubmissionAction
{
    public function handle(SubmissionCreated $event)
    {
        /** @var \Statamic\Contracts\Forms\Submission $submission */
        $submission = $event->submission;

        /** @var \Statamic\Contracts\Forms\Form $form */
        $form = $submission->form();

        $formConfig = collect(config('statamic.mailcoach.forms', []))
            ->firstWhere('form', $form->handle());

        if (! $formConfig) {
            return;
        }

        if (($formConfig['check_consent'] ?? false) && !$submission->get($formConfig['check_consent_field'])) {
            return;
        }

        $emailField = $formConfig['primary_email_field'] ?? 'email';

        $attributes = collect($formConfig['attributes'] ?? [])
            ->mapWithKeys(function (array $attribute) use ($submission) {
                $handle = trim(str_replace(['{{', '}}'], '', $attribute['value']));

                return [$attribute['key'] => $submission->get($handle) ?? $handle];
            })->toArray();

        if (! isset($formConfig['email_list_uuid'])) {
            return;
        }

        try {
            Mailcoach::createSubscriber(
                $formConfig['email_list_uuid'],
                [
                    'extra_attributes' => Arr::except($attributes, ['first_name', 'last_name']),
                    'first_name' => $attributes['first_name'] ?? null,
                    'last_name' => $attributes['last_name'] ?? null,
                    'email' => $submission->get($emailField),
                    'tags' => $formConfig['tags'] ?? [],
                    'skip_confirmation' => $formConfig['disable_double_opt_in'] ?? false,
                ],
            );
        } catch (InvalidData $e) {
            // Do nothing
        }
    }
}
