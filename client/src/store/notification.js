import {noticationMutations,notificationActions,notifactionTypes} from "../constants/notificationConstants";

const initialState = {
    needShow: false,
    type: null , // one of success,error
    msg: null
}

export const state = Object.assign({},initialState);

export const actions = {
    [notificationActions.DISPLAY_NOTIFICATION](context, data)
    {
        context.commit(noticationMutations.SET_NOTIFICATION, data);

    },
    [notificationActions.DESTROY_NOTIFICATION](context)
    {
        context.commit(noticationMutations.CLEAR_NOTIFICATION);
    }
};

export const mutations = {
    [noticationMutations.SET_NOTIFICATION](state,data)
    {
        state.needShow = true;
        state.type = data.type;
        state.msg = data.msg;
    },
    [noticationMutations.CLEAR_NOTIFICATION](state)
    {
        state.needShow = false;
        state.type = null;
        state.msg = null;
    }
};

const getters = {
    needShow: state => state.needShow,
    type: state => state.type,
    msg: state => state.msg
};

export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
};