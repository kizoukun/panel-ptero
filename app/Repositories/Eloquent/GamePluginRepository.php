<?php

namespace Pterodactyl\Repositories\Eloquent;

use Pterodactyl\Contracts\Repository\GamePluginRepositoryInterface;
use Pterodactyl\Models\GamePlugin;

class GamePluginRepository extends EloquentRepository implements GamePluginRepositoryInterface
{
    /**
     * Return the model backing this repository.
     */
    public function model(): string
    {
        return GamePlugin::class;
    }
}
