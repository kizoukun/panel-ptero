<?php

namespace Pterodactyl\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Pterodactyl\Exceptions\Repository\RecordNotFoundException;
use Pterodactyl\Models\GamePlugin;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Prologue\Alerts\AlertsMessageBag;
use Pterodactyl\Services\GamePlugin\GamePluginUpdateService;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\View\Factory as ViewFactory;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Exceptions\Model\DataValidationException;
use Pterodactyl\Http\Requests\Admin\GamePluginFormRequest;
use Pterodactyl\Contracts\Repository\EggRepositoryInterface;
use Pterodactyl\Services\GamePlugin\GamePluginCreationService;
use Pterodactyl\Contracts\Repository\GamePluginRepositoryInterface;

class GamePluginsController extends Controller
{
    public function __construct(
        private readonly AlertsMessageBag $alert,
        private readonly ViewFactory $view,
        protected EggRepositoryInterface $eggRepository,
        protected GamePluginRepositoryInterface $repository,
        protected GamePluginCreationService $creationService,
        protected GamePluginUpdateService $updateService,
    ) {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(): View
    {

        $plugins = QueryBuilder::for(GamePlugin::query())
            ->allowedFilters(['id', 'name', 'category'])
            ->allowedSorts(['id'])
            ->paginate(config()->get('pterodactyl.paginate.admin.servers', 25));

        return $this->view->make('admin.game-plugins.index', [
            'plugins' => $plugins,
        ]);
    }

    public function create(): View
    {
        return $this->view->make('admin.game-plugins.new', [
            'eggs' => $this->eggRepository->all(),
        ]);
    }

    /**
     * @throws DataValidationException
     */
    public function store(GamePluginFormRequest $request): RedirectResponse
    {

        $this->creationService->handle($request->normalize());

        $this->alert->success('Successfully created a game plugins.')->flash();

        return redirect(route('admin.game-plugins'));
    }

    public function view(GamePlugin $game_plugin): View
    {
        return $this->view->make('admin.game-plugins.view', [
            'plugin' => $game_plugin,
            'eggs' => $this->eggRepository->all(),
        ]);
    }

    /**
     * @throws DataValidationException
     * @throws RecordNotFoundException
     */
    public function update(GamePluginFormRequest $request, GamePlugin $game_plugin): RedirectResponse
    {

        $this->updateService->handle($game_plugin, $request->normalize());

        $this->alert->success('Successfully updated game plugins.')->flash();

        return redirect(route('admin.game-plugins'));
    }

    /**
     * @throws RecordNotFoundException
     */
    public function delete(GamePlugin $game_plugin): RedirectResponse
    {
        $game_plugin->delete();

        $this->alert->success('Successfully deleted game plugins.')->flash();

        return redirect(route('admin.game-plugins'));
    }
}
