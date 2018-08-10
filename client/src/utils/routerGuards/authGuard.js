import store from "../../store/index"
import {authActions, AUTH_MODULE} from "../../constants/authConstants";
import AuthService from "../../services/AuthService";

export const beforeEach = (to,from,next) => {
    if(to.meta.needAuth){
        let hasAuthData = AuthService.hasAuthDataInLocalStorage();
        if(!hasAuthData) return next({path: "/login"})

        if(!AuthService.isAuthDataValid()) {
            store.dispatch(`${AUTH_MODULE}/${authActions.CLEAR_AUTH}`);
            return next({path: "/login"});
        }
    }

    next();
}