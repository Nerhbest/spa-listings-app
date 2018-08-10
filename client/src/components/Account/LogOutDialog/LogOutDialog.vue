<template>
    <v-dialog
            :value="needShow"
            max-width="300"
    >
        <v-card>
            <v-card-title class="headline">Действительно хотите выйти?</v-card-title>
            <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn
                        color="green darken-1"
                        flat="flat"
                        @click="hideLogOutDialog()"
                >
                    Нет
                </v-btn>

                <v-btn
                        color="green darken-1"
                        flat="flat"
                        @click="logout()"
                >
                    Да
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    import {mapMutations,mapActions} from "vuex";
    import {ACCOUNT_MODULE,accountMutations} from "../../../constants/accountConstants";
    import {AUTH_MODULE,authActions} from "../../../constants/authConstants";
    import {NOTIFICATION_MODULE,notificationActions} from "../../../constants/notificationConstants";

   export default {
         name: 'logout-dialog',
         props: ['needShow'],
        methods: {
        ...mapMutations({
            hideLogOutDialog: `${ACCOUNT_MODULE}/${accountMutations.HIDE_LOGOUT_DIALOG}`
        }),
        ...mapActions({
            logoutFromApp: `${AUTH_MODULE}/${authActions.LOGOUT_FROM_APP}`
        }),

         logout()
         {
             this.logoutFromApp().then(() => {

                 this.hideLogOutDialog();
                 this.$router.push({
                     name: 'main-page'
                 })
                 this.$store.dispatch(`${NOTIFICATION_MODULE}/${notificationActions.DISPLAY_NOTIFICATION}`, {
                     msg: "Вы вышли из аккаунта"
                 })

             })
         }

        }

   }
</script>

<style lang="stylus">

</style>
