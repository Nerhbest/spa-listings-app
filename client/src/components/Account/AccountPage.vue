<template>
    <div class="account">
            <div v-if="isLoading">
                <loader :isLoading="isLoading"></loader>
            </div>
            <div v-if="isLoaded">
                <v-container>
                    <v-layout  column class="account-wrapper">
                        <v-flex xs12>
                            <account-nav-bar></account-nav-bar>
                        </v-flex>
                        <v-flex xs12>
                            <router-view></router-view>
                        </v-flex>
                    </v-layout>
                </v-container>
            </div>
    </div>
</template>

<script>
    import AccountNavBar from "./AccountNavBar/AccountNavBar";
    import {ACCOUNT_MODULE,accountActions, accountMutations} from "../../constants/accountConstants";
    import {mapActions,mapGetters} from "vuex";
    import Loader from "../Shared/Loader";
    import LogoutDialog from "./LogOutDialog/LogOutDialog";

    export default {
       components: {
           Loader,
           AccountNavBar},
       name: 'account',
       methods: {
       ...mapActions({
           fetchAccountInfo: `${ACCOUNT_MODULE}/${accountActions.FETCH_ACCOUNT_INFO}`
       })
       },
       computed: {
           ...mapGetters({
               isLoading: `${ACCOUNT_MODULE}/isLoading`,
               isLoaded: `${ACCOUNT_MODULE}/isLoaded`,
               userInfo: `${ACCOUNT_MODULE}/userInfo`,
               listingsPaginator: `${ACCOUNT_MODULE}/listingsPaginator`,
               favoriteListingsPaginator: `${ACCOUNT_MODULE}/favoriteListingsPaginator`,
           })
       },
       mounted()
       {
            this.fetchAccountInfo();
       }
   
   } 
</script>

<style lang="stylus">
    .account
        &-wrapper
            border: 1px solid #dadada
            border-radius: 4px
</style>
