<?php

namespace Pterodactyl\Models;

/**
 * @property int $id
 * @property string $name
 * @property string $version
 * @property string $category
 * @property string $description
 * @property array $eggs
 * @property string $download_url
 * @property string|null $decompress_type
 * @property string $install_folder
 * @property bool $is_delete_all
 * @property array $delete_folder
 */
class GamePlugin extends Model
{
    public const RESOURCE_NAME = 'game_plugins';

    protected $table = 'game_plugins';

    /**
     * Fields that are not mass assignable.
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Cast values to correct type.
     */
    protected $casts = [
        'id' => 'integer',
        'eggs' => 'array',
        'is_delete_all' => 'boolean',
        'delete_folder' => 'array',
    ];

    public static array $validationRules = [
        'name' => 'required|string|max:191',
        'version' => 'required|string|max:191',
        'category' => 'required|string|max:191',
        'description' => 'string|nullable',
        'eggs' => 'array|nullable',
        'download_url' => 'string',
        'decompress_type' => 'string|nullable',
        'install_folder' => 'string',
        'is_delete_all' => 'boolean',
        'delete_folder' => 'array',
    ];

    protected $attributes = [
        'eggs' => '[]',
        'is_delete_all' => false,
        'delete_folder' => '[]',
    ];

}
