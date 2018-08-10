import axios from "axios";
import {BASE_URL} from "../config/index";

export default class AuthService {
    static login(values)
    {
        return axios.post(`${BASE_URL}/login`, values)
        .then(resp => resp.data);
    }

    static register(values)
    {
        return axios.post(`${BASE_URL}/register` , values)
        .then(resp => resp.data);
    }

    static logout()
    {
        return axios.post(`${BASE_URL}/logout`).then(resp => resp.data);
    }

    static loginUserIntoApp(data)
    {
        window.localStorage.setItem("token", data.token);
        window.localStorage.setItem("expires_in", data.expires_in);
        window.localStorage.setItem("user", JSON.stringify(data.user))
    }

    static setAuthHeader(token)
    {
        axios.defaults.headers.common['Authorization'] = token ? `Bearer ${token}` : "";
    }

    static logoutFromApp()
    {
        window.localStorage.setItem("token", "");
        window.localStorage.setItem("expires_in", "");
        window.localStorage.setItem("user", "")
        AuthService.setAuthHeader();
    }

    static hasAuthDataInLocalStorage()
    {
        return !! (window.localStorage.getItem("token") && window.localStorage.getItem("expires_in"));
    }

    static isAuthDataValid()
    {
        let expires_in = window.localStorage.getItem("expires_in");
        if(!expires_in) return false;

        return (expires_in >= this.currentTimestamp());
    }

    static currentTimestamp()
    {
        return Math.round(+Date.now() / 1000);
    }

    static getAuthData()
    {

        let authData = {
            token: window.localStorage.getItem("token"),
            expires_in: window.localStorage.getItem("expires_in"),
            user: JSON.parse(window.localStorage.getItem("user"))
        }
        
        return authData;
    }
};