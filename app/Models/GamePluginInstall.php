<?php

namespace Pterodactyl\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @property int $id
 * @property int $server_id
 * @property int $game_plugin_id
 *
 */
class GamePluginInstall extends Model
{
    protected $table = 'game_plugins_install';
}
