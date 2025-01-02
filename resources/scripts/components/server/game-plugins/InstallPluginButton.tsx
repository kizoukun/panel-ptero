import React, { useState } from 'react';
import { faPencilRuler } from '@fortawesome/free-solid-svg-icons';
import Icon from '@/components/elements/Icon';
import { ServerContext } from '@/state/server';
import { useFlashKey } from '@/plugins/useFlash';
import { Dialog } from '@/components/elements/dialog';
import { Button } from '@/components/elements/button/index';
import getPlugins, { GamePlugin } from '@/api/server/game-plugins/getPlugins';
import installPlugin from '@/api/server/game-plugins/installPlugin';

type InstallPluginButtonProps = {
    plugin: GamePlugin;
};

const InstallPluginButton = ({ plugin }: InstallPluginButtonProps) => {
    const [confirm, setConfirm] = useState(false);

    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);

    const { clearFlashes } = useFlashKey('server:game-plugins');

    const { revalidate } = getPlugins(uuid);

    async function onInstallPlugin() {
        clearFlashes();
        installPlugin(uuid, plugin.id).then(() => {
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
                confirm={'Install'}
                variant={'primary'}
                onConfirmed={onInstallPlugin}
            >
                This will install game plugins and remove some of the folder if not all from your server.
            </Dialog.Confirm>
            <Button
                size={Button.Sizes.Small}
                shape={Button.Shapes.IconSquare}
                type={'button'}
                onClick={() => setConfirm(true)}
            >
                <Icon icon={faPencilRuler} className={`w-3 h-auto`} />
            </Button>
        </>
    );
};

export default InstallPluginButton;
