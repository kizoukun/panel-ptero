import http from '@/api/http';

export interface GamePluginCategory {
    category: string;
    eggs: string[];
}

interface ResponseData {
    [key: string]: any;
}

function rawDataToGamePluginCategoryObject(data: ResponseData): GamePluginCategory {
    return {
        category: data.category,
        eggs: data.eggs,
    };
}

export default (uuid: string, eggId: string | number): Promise<GamePluginCategory[]> => {
    return new Promise((resolve, reject) => {
        http.get(`/api/client/servers/${uuid}/game-plugins/categories`)
            .then(({ data }) => {
                const listData = data || [];
                const dataMapped: GamePluginCategory[] = listData.map((datum: any) =>
                    rawDataToGamePluginCategoryObject(datum)
                );
                resolve(dataMapped.filter((datum) => datum.eggs.includes(eggId.toString())));
            })
            .catch(reject);
    });
};
