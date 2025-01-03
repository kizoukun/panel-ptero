<?php

namespace Pterodactyl\Http\Controllers\Api\Client\Servers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Pterodactyl\Models\Server;
use Illuminate\Http\JsonResponse;
use GuzzleHttp\Exception\GuzzleException;
use Pterodactyl\Models\GamePluginInstall;
use Symfony\Component\HttpFoundation\Response;
use Pterodactyl\Repositories\Eloquent\GamePluginRepository;
use Pterodactyl\Exceptions\Repository\RecordNotFoundException;
use Pterodactyl\Repositories\Wings\DaemonGamePluginRepository;
use Pterodactyl\Transformers\Api\Client\GamePluginTransformer;
use Pterodactyl\Http\Controllers\Api\Client\ClientApiController;
use Pterodactyl\Exceptions\Http\Connection\DaemonConnectionException;
use Pterodactyl\Http\Requests\Api\Client\Servers\GamePlugins\GamePluginInstallRequest;

class GamePluginController extends ClientApiController
{
    public function __construct(
        protected GamePluginRepository $gamePluginRepository,
        private DaemonGamePluginRepository $daemonGamePluginRepository,
    ) {
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

        $filteredPlugins = $plugins->filter(function ($plugin) use ($server) {
            return in_array($server->egg_id, array_map('intval', $plugin->eggs));
        });

        return $this->fractal->collection($filteredPlugins)
            ->transformWith($this->getTransformer(GamePluginTransformer::class))
            ->toArray();
    }

    public function category(Server $server): Collection
    {
        return $this->gamePluginRepository->getGameCategories($server);
    }

    /**
     * @throws DaemonConnectionException
     * @throws RecordNotFoundException
     * @throws GuzzleException
     */
    public function install(GamePluginInstallRequest $request, Server $server): JsonResponse
    {

        $pluginId = $request->get('plugin_id');
        $data = GamePluginInstall::query()->where('server_id', $server->id)->where('game_plugin_id', $pluginId)->first();
        if ($data) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }

        $gamePlugin = $this->gamePluginRepository->find($pluginId);

        $this->daemonGamePluginRepository->setServer($server)->install($gamePlugin->download_url, $gamePlugin->install_folder, $gamePlugin->is_delete_all, $gamePlugin->delete_folder, $gamePlugin->decompress_type, $gamePlugin->delete_from_base);

        $install = new GamePluginInstall();
        $install->server_id = $server->id;
        $install->game_plugin_id = $request->get('plugin_id');
        $install->save();

        return new JsonResponse([], Response::HTTP_ACCEPTED);
    }

    /**
     * @throws DaemonConnectionException
     * @throws RecordNotFoundException
     * @throws GuzzleException
     */
    public function uninstall(GamePluginInstallRequest $request, Server $server): JsonResponse
    {
        $pluginId = $request->get('plugin_id');

        $install = GamePluginInstall::query()->where('server_id', $server->id)->where('game_plugin_id', $pluginId)->first();
        $gamePlugin = $this->gamePluginRepository->find($pluginId);
        if ($install) {
            $this->daemonGamePluginRepository->setServer($server)->uninstall($gamePlugin->install_folder, $gamePlugin->delete_from_base, $gamePlugin->is_delete_all, $gamePlugin->delete_folder);
            $install->delete();
        }

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
