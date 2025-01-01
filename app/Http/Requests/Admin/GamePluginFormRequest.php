<?php

namespace Pterodactyl\Http\Requests\Admin;

use Pterodactyl\Models\GamePlugin;

class GamePluginFormRequest extends AdminFormRequest
{
    protected function prepareForValidation(): void
    {
        $is_delete_all = false;
        if (strtolower($this->input('is_delete_all')) === 'on') {
            $is_delete_all = true;
        }
        $this->merge([
            'is_delete_all' => $is_delete_all,
        ]);
    }

    /**
     * Set up the validation rules to use for these requests.
     */
    public function rules(): array
    {
        if ($this->method() === 'PATCH') {
            return GamePlugin::getRulesForUpdate($this->route()->parameter('game_plugin'));
        }

        return GamePlugin::getRules();
    }

    public function validated($key = null, $default = null): array
    {
        $data = parent::validated();

        return array_merge($data, [
            'eggs' => array_get($data, 'eggs', []),
            'delete_folder' => array_get($data, 'delete_folder', []),
        ]);
    }
}
