import Vue from "vue";
import VueRouter from "vue-router";
import LoginForm from "../components/Auth/Login/LoginForm.vue";
import RegisterForm from "../components/Auth/Register/RegisterForm.vue";
import AccountPage from "../components/Account/AccountPage.vue";
import AccountListings from "../components/Account/AccountListings/AccountListings.vue";
import AccountFavoriteListings from "../components/Account/AccountFavoritesListings/AccountFavoritesListings.vue";
import AccountSettings from "../components/Account/AccountSettings/AccountSettings.vue";
import AccountChangePasswordForm from "../components/Account/AccountChangePasswordForm/AccountChangePasswordForm.vue";
import MainPage from "../components/MainPage/MainPage.vue";
import CreateListingForm from "../components/Account/CreateListingForm/CreateListingForm.vue";
import SingleListing from "../components/SingleListing/SingleListing.vue";


Vue.use(VueRouter);

export default new VueRouter({
    mode: 'history',
    routes: [
        {
            path: "/",
            component: MainPage,
            name: 'main-page'
        },
        {
            path: "/login",
            component: LoginForm,
            name: "login"
        },
        {
            path: "/register",
            component: RegisterForm,
            name: 'register'
        },
        {
            path: "/listing/create",
            component: CreateListingForm,
            name: 'listing-create',
            props: {mode: 'create'},
            meta: {
                needAuth: true
            }
        },
        {
           path: "/listing/update/:id",
           component: CreateListingForm,
           name: 'listing-update',
           props: {mode: 'update'},
           meta: {
               needAuth: true
           }
        },
        {
            path: '/listing/:id',
            component: SingleListing,
            name: 'single-listing'
        },
        {
            path: "/account",
            component: AccountPage,
            meta: {
                needAuth: true
            },
            children: [
                {
                    path: "/",
                    component: AccountListings,
                    name: 'account',
                    meta: {
                        needAuth: true
                    }
                },
                {
                    path: "/favorites",
                    component: AccountFavoriteListings,
                    name: 'account.favorites',
                    meta: {
                        needAuth: true
                    }
                },
                {
                    path: '/settings',
                    component: AccountSettings,
                    name: 'account.settings',
                    meta: {
                        needAuth: true
                    }
                },
                {
                    path: '/change-password',
                    component: AccountChangePasswordForm,
                    name: 'account.change-password',
                    meta: {
                        needAuth: true
                    }
                }
            ]
        }
    ]
})