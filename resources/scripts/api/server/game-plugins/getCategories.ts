import http from '@/api/http';
import { ServerContext } from '@/state/server';
import useSWR from 'swr';

export default () => {
    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);

    return useSWR<string[]>(
        ['server:game-plugins:categories', uuid],
        async () => {
            const { data } = await http.get(`/api/client/servers/${uuid}/game-plugins/categories`);

            return data || [];
        },
        { revalidateOnFocus: false, revalidateOnMount: false }
    );
};
