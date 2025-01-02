<?php

namespace Pterodactyl\Transformers\Api\Client;

use Pterodactyl\Models\GamePlugin;

class GamePluginTransformer extends BaseClientTransformer
{
    /**
     * Return the resource name for the JSONAPI output.
     */
    public function getResourceName(): string
    {
        return GamePlugin::RESOURCE_NAME;
    }

    public function transform(GamePlugin $model): array
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'description' => $model->description,
            'category' => $model->category,
            'version' => $model->version,
            'is_installed' => $model->is_installed,
        ];
    }
}
