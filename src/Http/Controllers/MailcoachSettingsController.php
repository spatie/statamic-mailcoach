<?php

namespace Spatie\StatamicMailcoach\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Statamic\CP\PublishForm;
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

        return PublishForm::make($blueprint)
            ->asConfig()
            ->icon('mailcoach')
            ->title('Mailcoach Settings')
            ->values($this->preProcess(config('statamic.mailcoach')))
            ->submittingTo(cp_route('statamic-mailcoach.settings.update'));
    }

    public function update(Request $request)
    {
        $blueprint = $this->getBlueprint();

        // Use PublishForm to handle validation and processing
        $values = PublishForm::make($blueprint)
            ->submit($request->all());

        $data = $this->postProcess($values);

        if (! file_exists(config_path('statamic/mailcoach.php'))) {
            Artisan::call('vendor:publish --tag=statamic-mailcoach-config');
        }

        ConfigWriter::ignoreFunctionCalls()->writeMany('statamic.mailcoach', $data);

        return ['saved' => true];
    }

    protected function postProcess(array $values): array
    {
        $values = $this->removeId($values);

        $userConfig = Arr::get($values, 'users', []);

        // Handle both array and nested array formats
        if (is_array($userConfig) && !empty($userConfig)) {
            $values['users'] = isset($userConfig[0]) ? $userConfig[0] : $userConfig;
        } else {
            $values['users'] = [];
        }

        return $values;
    }

    private function removeId($value)
    {
        if (is_array($value)) {
            Arr::forget($value, 'id');

            $value = array_filter(array_map(function ($value) {
                return $this->removeId($value);
            }, $value), function ($item) {
                // Keep false booleans, only filter out null and empty arrays
                return $item !== null && $item !== [];
            });
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
