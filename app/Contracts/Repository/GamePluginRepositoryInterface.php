<?php

namespace Pterodactyl\Contracts\Repository;

use Illuminate\Support\Collection;

interface GamePluginRepositoryInterface extends RepositoryInterface
{
    /**
     * Return a collection of unique game categories.
     */
    public function getGameCategories(): Collection;
}
