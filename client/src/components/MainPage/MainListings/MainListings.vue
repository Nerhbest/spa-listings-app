<template>
    <div>
        <full-screen-loader :isLoading="isLoading"></full-screen-loader>
        <v-layout row wrap>
            <v-flex xs4
                    v-for="listing in listings"
                    :key="listing.id"
            >
                <v-card class="listing ma-2"
                        :data-listing-id="listing.id"
                >
                    <v-card-media
                            :src="listing.main_photo"
                            height="200px"
                    ></v-card-media>
                    <div class="listing__description">
                        <h3 class="listing__title">{{listing.title}}</h3>
                        <div class="listing__price">
                            {{listing.price}} Р
                        </div>
                        <div class="listing__when">
                            {{ listing.created_at }}
                        </div>
                    </div>
                    <v-card-actions>
                        <v-btn color="primary" @click="goToSingleListing(listing.id)">Показать</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>
        <div class="more-listings mt-2">
            <v-btn v-if="hasMoreListings"
                   color="primary"
                   class="more-listings__btn"
                   @click.stop="loadMoreListings"
                   :disabled="isLoading"
            >
                Ещё
            </v-btn>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from "vuex";
    import {MAIN_LISTINGS, mainListingsActions} from "../../../constants/mainListingsConstants";
    import {MAIN_PAGE,mainPageActions} from "../../../constants/mainPageConstants";
    import CircularProgress from "../../Shared/CircularLoader";
    import Loader from "../../Shared/Loader";
    import FullScreenLoader from "../../Shared/FullScreenLoader";
    import {BASE_URL} from "../../../config/index";

   export default {
       components: {
           FullScreenLoader,
           Loader,
           CircularProgress},
       name: 'main-listings',
        computed: {
        ...mapGetters({
            listings: `${MAIN_LISTINGS}/listings`,
            current_page: `${MAIN_LISTINGS}/current_page`,
            total: `${MAIN_LISTINGS}/total`,
            last_page: `${MAIN_LISTINGS}/last_page`,
            isLoading: `${MAIN_LISTINGS}/isLoading`,
            scrollToListingId: `${MAIN_LISTINGS}/scrollToListingId`
        }),
            hasMoreListings() {
                return this.current_page < this.last_page;
            }
        },
       watch: {
         scrollToListingId(val)
         {
             this.$nextTick(() => {
                 let listingDomElement = window.document.querySelector(`[data-listing-id="${val}"]`);
                 if(listingDomElement){
                         window.scrollTo({
                             top: listingDomElement.offsetTop,
                             behavior: "smooth"
                         });
                 };
             });
         }
       },
       methods: {
       ...mapActions({
           fetchMoreListings: `${MAIN_PAGE}/${mainPageActions.LOAD_MORE_LISTINGS_BY_PARAMS}`
       }),
        loadMoreListings()
        {
            let page = this.current_page + 1;
            if(page > this.last_page) return false;
            this.fetchMoreListings(page);
        },
        goToSingleListing(id)
        {
            this.$router.push({
                name: 'single-listing',
                params: {
                    id
                },
                exact: true
            })
        }
       }
   }
</script>

<style lang="stylus" scoped>
    @import "../../../styles/_variables.styl"
    .listing
        &__description
            display: flex
            flex-direction: column
            padding: 15px 15px 10px
        &__price
            color: $primary-color
            font-size: 1.3rem
            font-family: "PT Sans", sans-serif
        &__when
            color: $secondary-color
    .more-listings
        &__btn
            display: block !important
            width: 20%
            margin: 0 auto
</style>



