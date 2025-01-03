import { action, Action, Thunk, thunk } from 'easy-peasy';
import getCategories, { GamePluginCategory } from '@/api/server/game-plugins/getCategories';

export interface GamePluginStore {
    categories: GamePluginCategory[];
    setCategories: Action<GamePluginStore, GamePluginCategory[]>;
    getCategories: Thunk<GamePluginStore, { uuid: string; eggId: number | string }, any, any>;
}

const gamePlugins: GamePluginStore = {
    categories: [],

    setCategories: action((state, payload) => {
        state.categories = payload;
    }),

    getCategories: thunk(async (action, payload) => {
        const { uuid, eggId } = payload;
        const data: GamePluginCategory[] | Error = await getCategories(uuid, eggId).catch((error: Error) => {
            return error;
        });

        if (data instanceof Error) {
            return;
        }

        action.setCategories(data);
    }),
};

export default gamePlugins;
