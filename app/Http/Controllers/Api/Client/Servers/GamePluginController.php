<?php

namespace Pterodactyl\Http\Controllers\Api\Client\Servers;

use Illuminate\Support\Facades\Log;
use Pterodactyl\Http\Requests\Api\Client\Servers\GamePlugins\GamePluginInstallRequest;
use Pterodactyl\Models\GamePluginInstall;
use Pterodactyl\Models\Server;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Pterodactyl\Repositories\Eloquent\GamePluginRepository;
use Pterodactyl\Transformers\Api\Client\GamePluginTransformer;
use Pterodactyl\Http\Controllers\Api\Client\ClientApiController;

class GamePluginController extends ClientApiController
{
    public function __construct(
        protected GamePluginRepository $gamePluginRepository,
    )
    {
        parent::__construct();
    }

    public function index(Server $server): array
    {
        $category = $this->request->input('category');
        $filter = $this->request->input('filter');

        $query = $this->gamePluginRepository->getBuilder();

        if (!empty($category)) {
            $query->whereRaw('LOWER(category) LIKE ?', ['%' . strtolower($category) . '%']);
        }

        if (!empty($filter)) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($filter) . '%']);
        }

        $plugins = $query->get();

        $installs = GamePluginInstall::query()->where('server_id', $server->id)->get();

        $plugins->each(function ($plugin) use ($installs) {
            $plugin->is_installed = $installs->contains('game_plugin_id', $plugin->id);
        });

        return $this->fractal->collection($plugins)
            ->transformWith($this->getTransformer(GamePluginTransformer::class))
            ->toArray();
    }

    public function category(Server $server): array
    {
        return $this->gamePluginRepository->getGameCategories()->pluck('category')->toArray();
    }

    public function install(GamePluginInstallRequest $request, Server $server): JsonResponse
    {

        $pluginId = $request->get('plugin_id');
        $data = GamePluginInstall::query()->where('server_id', $server->id)->where('game_plugin_id', $pluginId)->first();
        if ($data) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }
        $install = new GamePluginInstall();
        $install->server_id = $server->id;
        $install->game_plugin_id = $request->get('plugin_id');
        $install->save();

        return new JsonResponse([], Response::HTTP_ACCEPTED);
    }

    public function uninstall(GamePluginInstallRequest $request, Server $server): JsonResponse
    {
        $pluginId = $request->get('plugin_id');
        $install = GamePluginInstall::query()->where('server_id', $server->id)->where('game_plugin_id', $pluginId)->first();
        if ($install) {
            $install->delete();
        }

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
