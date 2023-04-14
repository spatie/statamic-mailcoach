<?php

namespace Spatie\StatamicMailcoach\Tests;

use Illuminate\Http\Request;
use Spatie\MailcoachSdk\Facades\Mailcoach;
use Spatie\StatamicMailcoach\Actions\SubscribeFromRegistrationAction;
use Statamic\Auth\File\User;
use Statamic\Events\UserRegistered;

class SubscribeFromRegistrationActionTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('statamic.mailcoach.add_new_users', true);
        config()->set('mailcoach-sdk.api_token', 'api-token');
        config()->set('mailcoach-sdk.endpoint', 'https://mailcoach.example/api');
        config()->set('statamic.mailcoach.users.email_list_uuid', 'some-uuid');

        $user = new User();
        $user->email('rias@spatie.be');

        $this->user = $user;
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

        app(SubscribeFromRegistrationAction::class)->handle(new UserRegistered($this->user));
    }

    /** @test * */
    public function it_does_nothing_when_add_new_users_is_false(): void
    {
        config()->set('statamic.mailcoach.add_new_users', false);

        Mailcoach::spy()->shouldNotReceive('createSubscriber');

        app(SubscribeFromRegistrationAction::class)->handle(new UserRegistered($this->user));
    }

    /** @test * */
    public function it_checks_consent(): void
    {
        Mailcoach::spy()->shouldReceive('createSubscriber')->once();

        config()->set('statamic.mailcoach.users.check_consent', true);
        config()->set('statamic.mailcoach.users.check_consent_field', 'newsletter');

        $this->app->forgetInstance('request');
        $this->app->bind('request', function () {
            return new Request(['newsletter' => 1]);
        });

        app(SubscribeFromRegistrationAction::class)->handle(new UserRegistered($this->user));
    }

    /** @test * */
    public function it_checks_consent_and_does_nothing_if_not_given(): void
    {
        Mailcoach::spy()->shouldNotReceive('createSubscriber');

        config()->set('statamic.mailcoach.users.check_consent', true);
        config()->set('statamic.mailcoach.users.check_consent_field', 'newsletter');

        $this->app->forgetInstance('request');
        $this->app->bind('request', function () {
            return new Request(['newsletter' => 0]);
        });

        app(SubscribeFromRegistrationAction::class)->handle(new UserRegistered($this->user));
    }

    /** @test * */
    public function it_can_take_attributes_from_the_user_field(): void
    {
        $this->user->set('attribute', 'bar');

        config()->set('statamic.mailcoach.users.attributes', [
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

        app(SubscribeFromRegistrationAction::class)->handle(new UserRegistered($this->user));
    }

    /** @test * */
    public function it_adds_tags(): void
    {
        config()->set('statamic.mailcoach.users.tags', ['one', 'two']);

        Mailcoach::spy()->shouldReceive('createSubscriber')->with('some-uuid', [
            'extra_attributes' => [],
            'first_name' => null,
            'last_name' => null,
            'email' => 'rias@spatie.be',
            'tags' => ['one', 'two'],
            'skip_confirmation' => false,
        ])->once();

        app(SubscribeFromRegistrationAction::class)->handle(new UserRegistered($this->user));
    }

    /** @test * */
    public function it_can_skip_confirmation(): void
    {
        config()->set('statamic.mailcoach.users.disable_double_opt_in', true);

        Mailcoach::spy()->shouldReceive('createSubscriber')->with('some-uuid', [
            'extra_attributes' => [],
            'first_name' => null,
            'last_name' => null,
            'email' => 'rias@spatie.be',
            'tags' => [],
            'skip_confirmation' => true,
        ])->once();

        app(SubscribeFromRegistrationAction::class)->handle(new UserRegistered($this->user));
    }
}
