import ServerContentBlock from '@/components/elements/ServerContentBlock';
import ErrorBoundary from '@/components/elements/ErrorBoundary';
import React, { useState } from 'react';
import GreyRowBox from '@/components/elements/GreyRowBox';
import Input from '@/components/elements/Input';
import CategoriesRow from '@/components/server/game-plugins/CategoriesRow';
import GamePluginRow from '@/components/server/game-plugins/GamePluginRow';
import { debounce } from 'debounce';

export default () => {
    const [selectedCategory, setSelectedCategory] = useState<string | null>(null);
    const [pluginName, setPluginName] = useState<string>('');

    const onSetPluginName = debounce((value: string) => {
        setPluginName(value);
    }, 750);

    return (
        <ServerContentBlock title={'File Manager'} showFlashKey={'files'}>
            <ErrorBoundary>
                <div className={'space-y-4'}>
                    <CategoriesRow selectedCategory={selectedCategory} setSelectedCategory={setSelectedCategory} />
                    <GreyRowBox $hoverable={false}>
                        <Input
                            type={'text'}
                            placeholder={'Search plugins by name'}
                            defaultValue={undefined}
                            onInputCapture={(e) => {
                                onSetPluginName(e.currentTarget.value);
                            }}
                        />
                    </GreyRowBox>

                    <GamePluginRow category={selectedCategory} filter={pluginName} />
                </div>
            </ErrorBoundary>
        </ServerContentBlock>
    );
};
