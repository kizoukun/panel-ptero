import http from '@/api/http';

export default (uuid: string, pluginId: number) => {
    return new Promise<void>((resolve, reject) => {
        http.post(`/api/client/servers/${uuid}/game-plugins/uninstall`, {
            plugin_id: pluginId,
        })
            .then(() => resolve())
            .catch(reject);
    });
};
