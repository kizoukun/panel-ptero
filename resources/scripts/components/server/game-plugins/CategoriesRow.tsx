import React from 'react';
import GreyRowBox from '@/components/elements/GreyRowBox';
import { ServerContext } from '@/state/server';

interface CategoriesRowProps {
    selectedCategory: string | null;
    setSelectedCategory: (category: string | null) => void;
}

export default ({ selectedCategory, setSelectedCategory }: CategoriesRowProps) => {
    const categoriesData = ServerContext.useStoreState((state) => state.gamePlugins.categories);

    function onClickCategory(item: string) {
        if (item === selectedCategory) {
            setSelectedCategory(null);
            return;
        }

        setSelectedCategory(item);
    }

    return (
        <GreyRowBox $hoverable={false}>
            <div className={'flex gap-4 overflow-x-auto'}>
                {categoriesData?.map((item, index) => (
                    <React.Fragment key={`item-${item.category}-${index}`}>
                        <div onClick={() => onClickCategory(item.category)}>
                            <p
                                className={`${
                                    selectedCategory === item.category ? 'text-primary' : 'text-gray-300'
                                }  hover:cursor-pointer capitalize`}
                            >
                                {item.category}
                            </p>
                        </div>
                        {index < categoriesData.length - 1 && <span className={'text-gray-400'}>&nbsp;|&nbsp;</span>}
                    </React.Fragment>
                ))}
            </div>
        </GreyRowBox>
    );
};
