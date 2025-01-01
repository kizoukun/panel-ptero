<?php

namespace Pterodactyl\Services\GamePlugin;

use Pterodactyl\Exceptions\Repository\RecordNotFoundException;
use Pterodactyl\Models\GamePlugin;
use Pterodactyl\Contracts\Repository\GamePluginRepositoryInterface;

class GamePluginUpdateService
{
    /**
     * GamePluginUpdateService constructor.
     */
    public function __construct(protected GamePluginRepositoryInterface $repository)
    {
    }

    /**
     * Create a new game plugin.
     *
     * @throws \Pterodactyl\Exceptions\Model\DataValidationException
     * @throws RecordNotFoundException
     */
    public function handle(GamePlugin $plugin, array $data): void
    {
        $this->repository->withoutFreshModel()->update($plugin->id, $data);
    }
}
