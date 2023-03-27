<?php

namespace Spatie\StatamicMailcoach\Fieldtypes;

use Statamic\Fields\Fields;
use Statamic\Fields\Fieldtype;
use Statamic\Fieldtypes\Select;
use Statamic\Fieldtypes\Taggable;

class UserFields extends Select
{
    protected $component = 'select';

    public array $options = [
        'one',
    ];
}
