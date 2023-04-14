<?php

namespace Spatie\StatamicMailcoach\Tests;

use Spatie\MailcoachSdk\MailcoachSdkServiceProvider;
use Spatie\StatamicMailcoach\StatamicMailcoachServiceProvider;
use Statamic\Providers\StatamicServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Statamic\Statamic;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            StatamicServiceProvider::class,
            MailcoachSdkServiceProvider::class,
            StatamicMailcoachServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Statamic' => Statamic::class,
        ];
    }
}
