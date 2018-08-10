import {authMutations,authActions} from "../constants/authConstants";
import AuthService from "../services/AuthService";

const initialState = {
    isLoggedIn: false,
    token: false,
    expires_in: false,
    user: {
    }
}

export const state = Object.assign({},initialState);

export const actions = {
    [authActions.LOGIN_USER_INTO_APP](context, data) {
        AuthService.loginUserIntoApp(data);
        AuthService.setAuthHeader(data.token);
        context.commit(authMutations.SET_AUTH_DATA, data);
    },
    [authActions.LOGOUT_FROM_APP](context)
    {
        return AuthService.logout().then(data => {
            AuthService.logoutFromApp();
            context.commit(authMutations.CLEAR_AUTH_DATA);
        })
    }
};

export const mutations = {
    [authMutations.SET_AUTH_DATA](state, data){
        state.isLoggedIn = true;
        state.token = data.token;
        state.expires_in = data.expires_in;
        state.user = data.user;
    },
    [authMutations.CLEAR_AUTH_DATA](state)
    {
        state.isLoggedIn = false;
        state.user = {};
        state.token = false;
        state.expires_in = false;
    }
};

const getters = {
    isLoggedIn: state => state.isLoggedIn,
    expires_in: state => state.expires_in,
    user: state => state.user,
    token: state => state.token,
    isAuthUserOwner: state => id => state.user.id == id
};

export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
};