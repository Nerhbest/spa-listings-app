<template>
    <div>
        <full-screen-loader :isLoading="isLoading"></full-screen-loader>
        <v-layout column class="account-listings" v-if="listingsPaginator.data.length">
            <v-layout wrap>
                <v-flex xs4
                        v-for="listing in listingsPaginator.data"
                        :key="listing.id"
                >
                    <v-card class="account-listings__listing">
                        <v-card-media
                                :src="listing.main_photo"
                                height="200px"
                        >
                        </v-card-media>
                        <v-card-title class="account-listings-info">
                            <div>
                                <h3>{{listing.title}}</h3>
                                <h4>{{listing.price}} Р</h4>
                                <div class="account-listings-info__when">
                                    {{ listing.created_at }}
                                </div>
                            </div>
                        </v-card-title>
                        <v-card-actions>
                            <v-btn color="primary" :to="{'name' : 'single-listing', params: {id: listing.id}}">Показать</v-btn>
                            <v-btn color="primary" :to="{'name' : 'listing-update', params: {id : listing.id}}">
                                <v-icon color="white">edit</v-icon>
                            </v-btn>
                            <v-btn color="primary" @click="markListingForDelete(listing.id)">
                                <v-icon color="white">delete</v-icon>
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>
            <div class="text-xs-center">
                <v-pagination
                        :length="lastPage"
                        circle
                        :value="currentPage"
                        @input="updatePage"
                >
                </v-pagination>
            </div>
        </v-layout>

        <v-layout
                justify-center
                v-if="listingsPaginator.data.length == 0"
                class="pa-5">
            <h2 class="text-xs-center not-found">На данный момент у вас нет объявлений</h2>
        </v-layout>
        <delete-listing-dialog :needShow="needShowDeleteDialog"></delete-listing-dialog>
    </div>
</template>

<script>
    import {mapGetters,mapActions,mapMutations} from "vuex";
    import {ACCOUNT_MODULE,accountActions,accountMutations} from "../../../constants/accountConstants";
    import FullScreenLoader from "../../Shared/FullScreenLoader";
    import DeleteListingDialog from "./DeleteListingDialog/DeleteListingDialog";

   export default {
       components: {
           DeleteListingDialog,
           FullScreenLoader},
       computed: {
    ...mapGetters({
        isLoading: `${ACCOUNT_MODULE}/isListingsLoading`,
        listingsPaginator: `${ACCOUNT_MODULE}/listingsPaginator`,
        currentPage: `${ACCOUNT_MODULE}/listingsPaginatorCurrentPage`,
        lastPage: `${ACCOUNT_MODULE}/listingsPaginatorLastPage`,
        needShowDeleteDialog: `${ACCOUNT_MODULE}/needShowDeleteDialog`
    }),
    },
       methods: {
       ...mapActions({
           loadUserListings: `${ACCOUNT_MODULE}/${accountActions.LOAD_MORE_USER_LISTINGS}`
       }),
       ...mapMutations({
          putListingIdForDelete: `${ACCOUNT_MODULE}/${accountMutations.PUT_LISTING_ID_FOR_DELETE}`
       }),
           markListingForDelete(listingId)
           {
                this.putListingIdForDelete(listingId);
           },
           updatePage(page)
           {
               const {currentPage,lastPage} = this;
               if(page == currentPage || page > lastPage) return false;

               this.loadUserListings(page);
           }
       }

   }
</script>

<style lang="stylus" scoped>
    @import "../../../styles/_variables.styl"

    .account-listings
        padding: 20px
        &__listing
            margin: 10px
        &-info
            padding-bottom: 0 !important //override vuetify.css
            &__when
                color: $secondary-color
        &__delete-btn
            background-color: $primary-color
    .not-found
        color: $secondary-color
</style>
