<?php

namespace Pterodactyl\Repositories\Wings;

use Webmozart\Assert\Assert;
use Pterodactyl\Models\Server;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\TransferException;
use Pterodactyl\Exceptions\Http\Connection\DaemonConnectionException;

/**
 */
class DaemonGamePluginRepository extends DaemonRepository
{
    /**
     * Returns system information from the wings instance.
     *
     * @throws DaemonConnectionException|GuzzleException
     */
    public function install(string $file_url, string $install_folder, bool $is_delete_all, array $delete_files, ?string $decompress_type, bool $delete_from_base): ResponseInterface
    {
        Assert::isInstanceOf($this->server, Server::class);

        $attributes = [
            'file_url' => $file_url,
            'install_folder' => $install_folder,
            'is_delete_all' => $is_delete_all,
            'delete_from_base' => $delete_from_base,
            'delete_files' => $delete_files,
            'decompress_type' => $decompress_type,
        ];

        try {
            return $this->getHttpClient()->post(
                sprintf('/api/servers/%s/game-plugins/install', $this->server->uuid),
                [
                    'json' => array_filter($attributes, fn ($value) => !is_null($value)),
                ]
            );
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }

    /**
     * @throws DaemonConnectionException
     * @throws GuzzleException
     */
    public function uninstall(string $install_folder, bool $delete_from_base, bool $is_delete_all, array $delete_files): ResponseInterface
    {
        Assert::isInstanceOf($this->server, Server::class);

        $attributes = [
            'install_folder' => $install_folder,
            'delete_from_base' => $delete_from_base,
            'is_delete_all' => $is_delete_all,
            'delete_files' => $delete_files
        ];

        try {
            return $this->getHttpClient()->post(
                sprintf('/api/servers/%s/game-plugins/uninstall', $this->server->uuid),
                [
                    'json' => array_filter($attributes, fn ($value) => !is_null($value)),
                ]
            );
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }
}
