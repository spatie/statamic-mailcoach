<?php

namespace Spatie\StatamicMailcoach\Tests;

use Illuminate\Http\Request;
use Spatie\MailcoachSdk\Facades\Mailcoach;
use Spatie\StatamicMailcoach\Actions\SubscribeFromSubmissionAction;
use Statamic\Auth\File\User;
use Statamic\Events\SubmissionCreated;
use Statamic\Forms\Form;
use Statamic\Forms\Submission;

class SubscribeFromSubmissionActionTest extends TestCase
{
    private Submission $submission;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('statamic.mailcoach.forms', [
            [
                'form' => 'contact',
                'email_list_uuid' => 'some-uuid',
                'disable_double_opt_in' => false,
                'tags' => [],
                'attributes' => [],
                'primary_email_field' => 'email',
            ]
        ]);

        config()->set('mailcoach-sdk.api_token', 'api-token');
        config()->set('mailcoach-sdk.endpoint', 'https://mailcoach.example/api');

        $submission = new Submission();
        $submission->form((new Form())->handle('contact'));
        $submission->set('email', 'rias@spatie.be');

        $this->submission = $submission;
    }

    /** @test * */
    public function it_calls_the_mailcoach_api(): void
    {
        Mailcoach::spy()->shouldReceive('createSubscriber')->with('some-uuid', [
            'extra_attributes' => [],
            'first_name' => null,
            'last_name' => null,
            'email' => 'rias@spatie.be',
            'tags' => [],
            'skip_confirmation' => false,
        ])->once();

        app(SubscribeFromSubmissionAction::class)->handle(new SubmissionCreated($this->submission));
    }

    /** @test * */
    public function it_checks_consent(): void
    {
        Mailcoach::spy()->shouldReceive('createSubscriber')->once();

        config()->set('statamic.mailcoach.forms.0.check_consent', true);
        config()->set('statamic.mailcoach.forms.0.check_consent_field', 'newsletter');

        $this->submission->set('newsletter', 1);

        app(SubscribeFromSubmissionAction::class)->handle(new SubmissionCreated($this->submission));
    }

    /** @test * */
    public function it_checks_consent_and_does_nothing_if_not_given(): void
    {
        Mailcoach::spy()->shouldNotReceive('createSubscriber');

        config()->set('statamic.mailcoach.forms.0.check_consent', true);
        config()->set('statamic.mailcoach.forms.0.check_consent_field', 'newsletter');

        $this->submission->set('newsletter', 0);

        app(SubscribeFromSubmissionAction::class)->handle(new SubmissionCreated($this->submission));
    }

    /** @test * */
    public function it_can_take_attributes_from_the_submission(): void
    {
        $this->submission->set('attribute', 'bar');

        config()->set('statamic.mailcoach.forms.0.attributes', [
            ['key' => 'foo', 'value' => 'attribute']
        ]);

        Mailcoach::spy()->shouldReceive('createSubscriber')->with('some-uuid', [
            'extra_attributes' => [
                'foo' => 'bar',
            ],
            'first_name' => null,
            'last_name' => null,
            'email' => 'rias@spatie.be',
            'tags' => [],
            'skip_confirmation' => false,
        ])->once();

        app(SubscribeFromSubmissionAction::class)->handle(new SubmissionCreated($this->submission));
    }

    /** @test * */
    public function it_adds_tags(): void
    {
        config()->set('statamic.mailcoach.forms.0.tags', ['one', 'two']);

        Mailcoach::spy()->shouldReceive('createSubscriber')->with('some-uuid', [
            'extra_attributes' => [],
            'first_name' => null,
            'last_name' => null,
            'email' => 'rias@spatie.be',
            'tags' => ['one', 'two'],
            'skip_confirmation' => false,
        ])->once();

        app(SubscribeFromSubmissionAction::class)->handle(new SubmissionCreated($this->submission));
    }

    /** @test * */
    public function it_can_skip_confirmation(): void
    {
        config()->set('statamic.mailcoach.forms.0.disable_double_opt_in', true);

        Mailcoach::spy()->shouldReceive('createSubscriber')->with('some-uuid', [
            'extra_attributes' => [],
            'first_name' => null,
            'last_name' => null,
            'email' => 'rias@spatie.be',
            'tags' => [],
            'skip_confirmation' => true,
        ])->once();

        app(SubscribeFromSubmissionAction::class)->handle(new SubmissionCreated($this->submission));
    }
}
