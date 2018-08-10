import axios from "axios";
import {BASE_URL} from "../config/index";

export default class AccountService
{
    static fetchAccountInfo()
    {
        return axios.get(`${BASE_URL}/account-info`).then(resp => resp.data);
    }

    static updateUserCredentials(params)
    {
        return axios.post(`${BASE_URL}/account/update-credentials`, params).then(resp => resp.data);
    }

    static updateUserPassword(params)
    {
        return axios.post(`${BASE_URL}/account/update-password`, params).then(resp => resp.data);
    }
};