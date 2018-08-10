<template>
    <div>
        <v-toolbar color="primary">
            <div class="navigation">
                <div>
                    <router-link to="/" class="navigation__title p-1">
                        Объявления.<span class="accent--text">РФ</span>
                    </router-link>
                </div>
                <div v-if="!isLoggedIn">
                    <router-link class="navigation__link" :to="{'name' : 'login'}">Войти</router-link>
                    <router-link class="navigation__link" :to="{'name' : 'register'}">Зарегистрироваться</router-link>
                </div>
                <div v-else>
                    <router-link class="navigation__link" :to="{'name' : 'listing-create'}">Создать Объявление</router-link>
                    <router-link class="navigation__link" :to="{'name' : 'account.favorites'}">
                        Избранное
                    </router-link>
                    <router-link class="navigation__link" :to="{'name' : 'account'}" exact>Мои Объявления</router-link>
                    <router-link class="navigation__icon__account" :to="{'name' : 'account'}" exact>
                        <v-icon color="white">person</v-icon>
                    </router-link>
                    <v-icon color="white" @click="openLogoutDialog()">exit_to_app</v-icon>
                </div>
            </div>
        </v-toolbar>
        <logout-dialog :needShow="needShowLogOutDialog"></logout-dialog>
    </div>
</template>

<script>
    import {authActions,authMutations} from "../../constants/authConstants";
    import {mapGetters,mapMutations} from "vuex";
    import {ACCOUNT_MODULE,accountMutations} from "../../constants/accountConstants";
    import {AUTH_MODULE} from "../../constants/authConstants";
    import LogoutDialog from "../Account/LogOutDialog/LogOutDialog";

   export default {
       components: {LogoutDialog},
       name: 'top-nav',
        computed: {
        ...mapGetters({
            isLoggedIn: `${AUTH_MODULE}/isLoggedIn`,
            needShowLogOutDialog: `${ACCOUNT_MODULE}/needShowLogOutDialog`
        })
        },
        methods: {
        ...mapMutations({
            openLogoutDialog: `${ACCOUNT_MODULE}/${accountMutations.OPEN_LOGOUT_DIALOG}`
        })
        }
   } 
</script>

<style lang="stylus">
    @import "../../styles/_variables.styl"

    .navigation
        font-family: "PT Sans", Helvetica, sans-serif
        text-transform: uppercase
        font-weight: 700
        width: 100%
        display: flex
        justify-content: space-between
        align-items: center
        &__link
            padding: 10px
            transition: background-color .3s
            color: white
            &:hover
                background-color: $secondary-color
        &__link:not(:last-child)
            margin-right: 25px
        &__icon__account
            margin-right: 25px
</style>
