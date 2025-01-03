<?php

namespace Pterodactyl\Repositories\Eloquent;

use Pterodactyl\Models\Server;
use Pterodactyl\Models\GamePlugin;
use Illuminate\Database\Eloquent\Collection;
use Pterodactyl\Contracts\Repository\GamePluginRepositoryInterface;

class GamePluginRepository extends EloquentRepository implements GamePluginRepositoryInterface
{
    /**
     * Return the model backing this repository.
     */
    public function model(): string
    {
        return GamePlugin::class;
    }

    public function getGameCategories(Server $server): Collection
    {
        $data = $this->getBuilder()
            ->selectRaw('LOWER(category) as category, eggs')
            ->get()
            ->groupBy('category')
            ->map(function ($group) {
                return [
                    'category' => $group->first()->category,
                    'eggs' => $group->pluck('eggs')->flatMap(function ($eggs) {
                        if (is_string($eggs)) {
                            $decoded = json_decode($eggs, true);

                            return $decoded !== null ? $decoded : [];
                        }

                        return is_array($eggs) ? $eggs : [];
                    })->unique()->values(),
                ];
            });

        $filtered = $data->filter(function ($category) use ($server) {
            return in_array($server->egg_id, $category['eggs']->toArray());
        });

        return new Collection($filtered->values());
    }
}
