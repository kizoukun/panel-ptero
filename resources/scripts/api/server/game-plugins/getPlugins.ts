import useSWR from 'swr';
import http, { FractalResponseData } from '@/api/http';

export interface GamePlugin {
    id: number;
    name: string;
    description: string;
    version: string;
    isInstalled?: boolean;
}

function rawDataToPluginObject({ attributes }: FractalResponseData): GamePlugin {
    return {
        id: attributes.id,
        name: attributes.name,
        description: attributes.description,
        version: attributes.version,
        isInstalled: attributes.is_installed,
    };
}

const fetchGamePlugins = async (uuid: string, category?: string | null, filter?: string): Promise<GamePlugin[]> => {
    const queryBuilder = new URLSearchParams();
    if (category) {
        queryBuilder.append('category', category);
    }

    if (filter) {
        queryBuilder.append('filter', filter);
    }

    const response = await http.get(
        `/api/client/servers/${uuid}/game-plugins${queryBuilder.toString() ? `?${queryBuilder.toString()}` : ''}`
    );
    return (response.data.data || []).map((datum: any) => rawDataToPluginObject(datum));
};

export default (uuid: string, category?: string | null, filter?: string) => {
    const { data, error, mutate, isValidating } = useSWR(
        ['server:game-plugins', uuid, category, filter],
        async () => fetchGamePlugins(uuid, category, filter),
        { revalidateOnFocus: false, revalidateOnMount: false }
    );

    async function revalidateData() {
        return await mutate(data);
    }

    return {
        data,
        isLoading: isValidating,
        isError: error,
        revalidate: revalidateData,
    };
};
