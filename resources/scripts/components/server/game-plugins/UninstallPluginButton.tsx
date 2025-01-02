import React, { useState } from 'react';
import { faTrashAlt } from '@fortawesome/free-solid-svg-icons';
import Icon from '@/components/elements/Icon';
import { ServerContext } from '@/state/server';
import { useFlashKey } from '@/plugins/useFlash';
import { Dialog } from '@/components/elements/dialog';
import { Button } from '@/components/elements/button/index';
import getPlugins, { GamePlugin } from '@/api/server/game-plugins/getPlugins';
import uninstallPlugin from '@/api/server/game-plugins/uninstallPlugin';

type UninstallPluginButtonProps = {
    plugin: GamePlugin;
};
const UninstallPluginTsx = ({ plugin }: UninstallPluginButtonProps) => {
    const [confirm, setConfirm] = useState(false);

    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);

    const { clearFlashes } = useFlashKey('server:game-plugins');

    const { revalidate } = getPlugins(uuid);

    async function onUninstallInstallPlugin() {
        clearFlashes();
        uninstallPlugin(uuid, plugin.id).then(() => {
            setConfirm(false);
            revalidate();
        });
    }

    return (
        <>
            <Dialog.Confirm
                open={confirm}
                onClose={() => setConfirm(false)}
                title={'Remove Game Plugins'}
                confirm={'Uninstall'}
                onConfirmed={onUninstallInstallPlugin}
            >
                This will be uninstall and remove game plugins from your server.
            </Dialog.Confirm>
            <Button.Danger
                variant={Button.Variants.Secondary}
                size={Button.Sizes.Small}
                shape={Button.Shapes.IconSquare}
                type={'button'}
                onClick={() => setConfirm(true)}
            >
                <Icon icon={faTrashAlt} className={`w-3 h-auto`} />
            </Button.Danger>
        </>
    );
};

export default UninstallPluginTsx;
