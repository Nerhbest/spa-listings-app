import axios from "axios";
import {BASE_URL} from "../config/index";

export default class MainPageService
{
  static getMainPageDetails(params)
  {
      return axios.get(`${BASE_URL}/main-page`, {
          params
      }).then(resp => resp.data);
  }
};