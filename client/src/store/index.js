import Vue from "vue";
import Vuex  from "vuex";
import {AUTH_MODULE} from "../constants/authConstants";
import auth from "./auth";
import {NOTIFICATION_MODULE} from "../constants/notificationConstants";
import notification from "./notification";
import {ACCOUNT_MODULE} from "../constants/accountConstants";
import accountInfo from "./accountInfo";
import mainPage from "./mainPage";
import {MAIN_PAGE} from "../constants/mainPageConstants";
import mainListings from "./mainListings";
import {MAIN_LISTINGS} from "../constants/mainListingsConstants";
import singleListing from "./singleListing";
import {SINGLE_LISTING_MODULE} from "../constants/singleListingConstants";

Vue.use(Vuex);


export default new Vuex.Store({
    modules: {
        [AUTH_MODULE] :auth,
        [NOTIFICATION_MODULE] : notification,
        [ACCOUNT_MODULE] : accountInfo,
        [MAIN_PAGE]: mainPage,
        [MAIN_LISTINGS]: mainListings,
        [SINGLE_LISTING_MODULE]: singleListing
    }
});