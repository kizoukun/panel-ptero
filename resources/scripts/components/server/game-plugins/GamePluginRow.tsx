import GreyRowBox from '@/components/elements/GreyRowBox';
import InstallPluginButton from '@/components/server/game-plugins/InstallPluginButton';
import UninstallPluginButton from '@/components/server/game-plugins/UninstallPluginButton';
import React, { useEffect } from 'react';
import getPlugins from '@/api/server/game-plugins/getPlugins';
import { ServerContext } from '@/state/server';
import Spinner from '@/components/elements/Spinner';
import useFlash from '@/plugins/useFlash';
import { httpErrorToHuman } from '@/api/http';

interface GamePluginRowProps {
    category?: string | null;
    filter?: string;
}

export default ({ category, filter }: GamePluginRowProps) => {
    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);

    const { data: plugins, isLoading, revalidate, isError } = getPlugins(uuid, category, filter);

    const { addError, clearFlashes } = useFlash();

    function revalidateData() {
        revalidate().then((r) => r);
    }

    useEffect(() => {
        clearFlashes('game-plugins');
        if (isError) {
            addError({ key: 'game-plugins', message: httpErrorToHuman(isError) });
        }
    }, [isError]);

    useEffect(() => {
        revalidateData();
    }, [uuid, category, filter]);

    return (
        <div className={'space-y-2'}>
            {isLoading ? (
                <Spinner size={'large'} centered />
            ) : Array.isArray(plugins) && plugins.length > 0 ? (
                plugins.map((plugin, index) => (
                    <GreyRowBox $hoverable={false} key={`plugin-${index}`}>
                        <div className={`w-full flex justify-between items-center`}>
                            <div>
                                <div className={'flex items-center gap-1'}>
                                    <p className={'text-lg text-primary'}>{plugin.name}</p>
                                    <p className={'text-gray-300'}>{plugin.version}</p>
                                </div>
                                <p className={`mt-1 text-xs text-gray-300`}>{plugin.description}</p>
                            </div>
                            <div>
                                {plugin.isInstalled ? (
                                    <UninstallPluginButton plugin={plugin} revalidate={revalidateData} />
                                ) : (
                                    <InstallPluginButton plugin={plugin} revalidate={revalidateData} />
                                )}
                            </div>
                        </div>
                    </GreyRowBox>
                ))
            ) : (
                <p className={'text-gray-500'}>No plugins available.</p>
            )}
        </div>
    );
};
