<?php

namespace Pterodactyl\Contracts\Repository;

use Illuminate\Support\Collection;
use Pterodactyl\Models\Server;

interface GamePluginRepositoryInterface extends RepositoryInterface
{
    /**
     * Return a collection of unique game categories.
     */
    public function getGameCategories(Server $server): Collection;
}
