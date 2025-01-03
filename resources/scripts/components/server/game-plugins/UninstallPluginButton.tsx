import React, { useState } from 'react';
import { faTrashAlt } from '@fortawesome/free-solid-svg-icons';
import Icon from '@/components/elements/Icon';
import { ServerContext } from '@/state/server';
import { useFlashKey } from '@/plugins/useFlash';
import { Dialog } from '@/components/elements/dialog';
import { Button } from '@/components/elements/button/index';
import { GamePlugin } from '@/api/server/game-plugins/getPlugins';
import uninstallPlugin from '@/api/server/game-plugins/uninstallPlugin';
import { httpErrorToHuman } from '@/api/http';

type UninstallPluginButtonProps = {
    plugin: GamePlugin;
    revalidate: () => void;
};
const UninstallPluginTsx = ({ plugin, revalidate }: UninstallPluginButtonProps) => {
    const [confirm, setConfirm] = useState(false);

    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);

    const { addError, clearFlashes } = useFlashKey('server:game-plugins');

    async function onUninstallInstallPlugin() {
        clearFlashes();
        await uninstallPlugin(uuid, plugin.id)
            .then(() => {
                revalidate();
            })
            .catch((err) => {
                addError(httpErrorToHuman(err));
            });
        setConfirm(false);
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
