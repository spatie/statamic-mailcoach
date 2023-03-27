<?php

namespace Spatie\StatamicMailcoach\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Statamic\Facades\Addon;
use Statamic\Facades\Blueprint as BlueprintAPI;
use Statamic\Facades\Path;
use Statamic\Facades\User;
use Statamic\Facades\YAML;
use Statamic\Fields\Blueprint;
use Statamic\Fields\Field;
use Statamic\Support\Arr;
use Stillat\Proteus\Support\Facades\ConfigWriter;

class MailcoachSettingsController
{
    public function show()
    {
        $blueprint = $this->getBlueprint();

        $fields = $blueprint
            ->fields()
            ->addValues($this->preProcess(config('statamic.mailcoach')))
            ->preProcess();

        return view('statamic-mailcoach::settings', [
            'blueprint' => $blueprint->toPublishArray(),
            'meta' => $fields->meta(),
            'route' => cp_route('statamic-mailcoach.settings'),
            'values' => $fields->values(),
        ]);
    }

    public function store(Request $request)
    {
        $blueprint = $this->getBlueprint();

        // Get a Fields object, and populate it with the submitted values.
        $fields = $blueprint->fields()->addValues($request->all());

        // Perform validation. Like Laravel's standard validation, if it fails,
        // a 422 response will be sent back with all the validation errors.
        $fields->validate();

        $data = $this->postProcess($fields->process()->values()->toArray());

        if (! file_exists(config_path('statamic/mailcoach.php'))) {
            Artisan::call('vendor:publish --tag=statamic-mailcoach-config');
        }

        ConfigWriter::ignoreFunctionCalls()->writeMany('statamic.mailcoach', $data);
    }

    protected function postProcess(array $values): array
    {
        $values = $this->removeId($values);

        $userConfig = Arr::get($values, 'users');

        return array_merge(
            $values,
            ['users' => $userConfig[0]]
        );
    }

    private function removeId($value)
    {
        if (is_array($value)) {
            Arr::forget($value, 'id');

            $value = array_filter(array_map(function ($value) {
                return $this->removeId($value);
            }, $value));
        }

        return $value;
    }

    protected function preProcess(array $config): array
    {
        return array_merge(
            $config,
            ['users' => [Arr::get($config, 'users', [])]]
        );
    }

    private function getBlueprint(): Blueprint
    {
        $path = Path::assemble(Addon::get('spatie/statamic-mailcoach')->directory(), 'resources', 'config.yaml');

        $blueprint = YAML::file($path)->parse();

        $blueprint['users']['fields'][3]['field']['fields'][0]['field']['options'] = User::blueprint()->fields()->all()
            ->mapWithKeys(fn (Field $field, string $handle) => [$handle => $field->display()])
            ->values()
            ->all();

        return BlueprintAPI::makeFromFields($blueprint);
    }
}
