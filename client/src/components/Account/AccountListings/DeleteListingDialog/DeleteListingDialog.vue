<template>
    <v-dialog
            :value="needShow"
            width="500"
            persistent
    >

        <v-card>
            <v-card-title class="headline">Действительно хотите удалить это объявление?</v-card-title>
            <v-card-text class="text-xs-center">
                Выполение этого действия приведет к безвозвратному удалению объявления
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                        color="green darken-1"
                        flat="flat"
                        :disabled="isDeleteActionLoading"
                        @click.native="hideDeleteDialog()"
                >
                    Нет
                </v-btn>

                <v-btn
                        color="green darken-1"
                        flat="flat"
                        :disabled="isDeleteActionLoading"
                        @click="deleteListing()"
                >
                    Да
                </v-btn>
            </v-card-actions>
            <v-progress-linear
                                 v-if="isDeleteActionLoading"
                                :indeterminate="true"></v-progress-linear>
        </v-card>
    </v-dialog>
</template>

<script>
    import {mapMutations,mapGetters,mapActions} from "vuex";
    import {ACCOUNT_MODULE,accountMutations,accountActions} from "../../../../constants/accountConstants";

   export default {
       name: 'delete-listing-dialog',
       props: ["needShow"],
       computed: {
       ...mapGetters({
           isDeleteActionLoading: `${ACCOUNT_MODULE}/isDeleteActionLoading`
       })
       },
       methods: {
           ...mapActions({
                deleteListing: `${ACCOUNT_MODULE}/${accountActions.DELETE_LISTING}`
           }),
           ...mapMutations({
                hideDeleteDialog: `${ACCOUNT_MODULE}/${accountMutations.HIDE_DELETE_DIALOG}`
           })
       }
   }
</script>

<style lang="stylus">

</style>
