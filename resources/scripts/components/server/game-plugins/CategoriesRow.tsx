import React, { useEffect } from 'react';
import GreyRowBox from '@/components/elements/GreyRowBox';
import getCategories from '@/api/server/game-plugins/getCategories';

interface CategoriesRowProps {
    selectedCategory: string | null;
    setSelectedCategory: (category: string | null) => void;
}

export default ({ selectedCategory, setSelectedCategory }: CategoriesRowProps) => {
    const { data: categoriesData, mutate } = getCategories();

    useEffect(() => {
        mutate(categoriesData).then((r) => r);
    }, []);

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
                    <React.Fragment key={`item-${item}-${index}`}>
                        <div onClick={() => onClickCategory(item)}>
                            <p
                                className={`${
                                    selectedCategory === item ? 'text-primary' : 'text-gray-300'
                                }  hover:cursor-pointer capitalize`}
                            >
                                {item}
                            </p>
                        </div>
                        {index < categoriesData.length - 1 && <span className={'text-gray-400'}>&nbsp;|&nbsp;</span>}
                    </React.Fragment>
                ))}
            </div>
        </GreyRowBox>
    );
};
