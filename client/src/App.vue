<template>
    <v-app>
      <top-nav></top-nav>
      <router-view></router-view>
      <notification></notification>
    </v-app>
</template>

<script>
    import TopNav from "./components/Navigation/TopNav";
    import Notification from "./components/Shared/Notification";
    import {AUTH_MODULE,authActions} from "./constants/authConstants";
    import AuthService from "./services/AuthService";
    import {mapGetters} from "vuex";


  export default {
      components: {
          Notification,
          TopNav},
      computed: {
        ...mapGetters({
            isLoggedIn: `${AUTH_MODULE}/isLoggedIn`
        })
      },
      methods: {
          resolveAuthState()
          {
              if(!this.isLoggedIn){
                  let hasAuthData = AuthService.hasAuthDataInLocalStorage();
                  if(hasAuthData){
                      let isValid = AuthService.isAuthDataValid();
                      if(isValid){
                          this.$store.dispatch(`${AUTH_MODULE}/${authActions.LOGIN_USER_INTO_APP}`, AuthService.getAuthData())
                      }
                      else
                          this.$store.dispatch(`${AUTH_MODULE}/${authActions.CLEAR_AUTH}`)
                  }
              }
          }
      },
      created()
      {
        this.resolveAuthState();
      }


  };
</script>

<style lang="stylus">
  @import "styles/_fonts.styl"
  @import "styles/_variables.styl"
  @import "styles/_reset.styl"
</style>
