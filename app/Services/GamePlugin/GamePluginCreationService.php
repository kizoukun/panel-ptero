<?php

namespace Pterodactyl\Services\GamePlugin;

use Pterodactyl\Models\GamePlugin;
use Pterodactyl\Contracts\Repository\GamePluginRepositoryInterface;

class GamePluginCreationService
{
    /**
     * GamePluginCreationService constructor.
     */
    public function __construct(protected GamePluginRepositoryInterface $repository)
    {
    }

    /**
     * Create a new game plugin.
     *
     * @throws \Pterodactyl\Exceptions\Model\DataValidationException
     */
    public function handle(array $data): GamePlugin
    {
        return $this->repository->create($data);
    }
}
