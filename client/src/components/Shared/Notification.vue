<template>
        <v-snackbar
                class="notification-block"
                :color="getColor()"
                :value="needShow"
                :timeout="0"
        >
            {{msg}}
        </v-snackbar>
</template>

<script>
    import {mapActions,mapGetters} from "vuex";
    import {NOTIFICATION_MODULE,notificationActions, notificationTypes} from "../../constants/notificationConstants";

    export default {
        name: 'notification',
        computed: {
        ...mapGetters({
            needShow: `${NOTIFICATION_MODULE}/needShow`,
            type: `${NOTIFICATION_MODULE}/type`,
            msg: `${NOTIFICATION_MODULE}/msg`
        })
        },
       methods: {
       ...mapActions({
           clearNotification: `${NOTIFICATION_MODULE}/${notificationActions.DESTROY_NOTIFICATION}`
       }),
         getColor()
         {
             switch(this.type){
                 case notificationTypes.SUCCESS:
                     return 'success';
                 case notificationTypes.ERROR:
                     return 'error';
             }
         }
       },
       watch: {
            needShow(val) {
                if(val){
                    window.setTimeout(() => this.clearNotification(), 3000);
                }
            }
       }
   } 
</script>

<style lang="stylus">
    @media (min-width: 0px) and (max-width: 2000px)
        .v-snack__wrapper
            max-width: 100% !important
            min-width: 100% !important
            margin: 0 !important
            text-align: center
            .v-snack__content
                justify-content: center
                text-transform: uppercase
</style>
