<template>
    <div>
        <full-screen-loader :isLoading="isFavoriteActionLoading || isFavoriteListingLoading"></full-screen-loader>
        <v-layout column class="favorite-listings" v-if="favoriteListingsPaginator.data.length">
            <v-layout wrap>
                <v-flex xs4
                        v-for="listing in favoriteListingsPaginator.data"
                        :key="listing.id"
                >
                    <v-card class="favorite-listings__listing">
                        <v-card-media
                                :src="listing.main_photo"
                                height="200px"
                        >
                        </v-card-media>
                        <v-card-title>
                            <div>
                                <h3>{{listing.title}}</h3>
                                <h4 class="text-secondary">{{listing.price}} Р</h4>
                            </div>
                        </v-card-title>
                        <v-card-actions>
                            <v-btn color="primary" :to="{'name' : 'single-listing', params: {id: listing.id}}">Показать</v-btn>
                            <v-btn color="primary" @click="removeFromFavorites(listing.id)">Убрать из избранного</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>
            <div class="text-xs-center">
                <v-pagination
                        :length="favoriteListingsPaginator.last_page"
                        circle
                        :value="favoriteListingsPaginator.current_page"
                        @input="updatePage"
                >
                </v-pagination>
            </div>
        </v-layout>
        <v-layout
                  justify-center
                  v-if="favoriteListingsPaginator.data.length == 0"
                  class="pa-5">
            <h2 class="text-xs-center not-found">На данный момент у вас нет избранных объявлений</h2>
        </v-layout>
    </div>
</template>

<script>
    import {mapGetters,mapActions} from "vuex";
    import {ACCOUNT_MODULE, accountActions} from "../../../constants/accountConstants";
    import FullScreenLoader from "../../Shared/FullScreenLoader";

    export default {
        components: {FullScreenLoader},
        computed: {
            ...mapGetters({
                isFavoriteListingLoading: `${ACCOUNT_MODULE}/isFavoriteListingLoading`,
                favoriteListingsPaginator: `${ACCOUNT_MODULE}/favoriteListingsPaginator`,
                currentPage: `${ACCOUNT_MODULE}/favoriteListingsPaginatorCurrentPage`,
                total: `${ACCOUNT_MODULE}/favoriteListingsPaginatorTotal`,
                lastPage : `${ACCOUNT_MODULE}/favoriteListingsPaginatorLastPage`,
                isFavoriteActionLoading: `${ACCOUNT_MODULE}/isFavoriteActionLoading`,
            })
        },
        methods: {
        ...mapActions({
            loadFavoritesListings: `${ACCOUNT_MODULE}/${accountActions.LOAD_MORE_FAVORITE_LISTINGS}`,
            removeFromFavorites: `${ACCOUNT_MODULE}/${accountActions.REMOVE_FROM_FAVORITE_LISTINGS}`
        }),
            updatePage(page)
            {
                const {currentPage,lastPage} = this;
                if(page == currentPage || page > lastPage) return false;

                this.loadFavoritesListings(page);
            }
        }

    }
</script>

<style lang="stylus" scoped>
    @import "../../../styles/_variables.styl"

    .favorite-listings
        padding: 20px
        &__listing
            margin: 10px
    .not-found
        color: $secondary-color
</style>
