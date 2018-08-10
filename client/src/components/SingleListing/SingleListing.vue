<template>
    <v-container>
        <full-screen-loader :isLoading="isLoading || isFavoriteLoading"></full-screen-loader>
        <v-layout column
                  v-if="isLoaded"
                  class="listing-wrapper">
            <v-layout xs12>
                <v-breadcrumbs divider="/" :large="true">
                    <v-breadcrumbs-item
                            v-for="breadcrumb in listing.breadcrumbs"
                            :key="breadcrumb.slug"
                            :disabled="true"
                    >
                        {{ breadcrumb.name}}
                    </v-breadcrumbs-item>
                </v-breadcrumbs>
            </v-layout>
            <v-layout column class="listing">
                <v-flex class="listing-main-info mb-3">
                    <v-layout class="pa-4" align-center>
                        <v-flex xs6>
                            <h1 class="listing__title">{{listing.title}}</h1>
                            <div class="listing__when">
                                #{{listing.id}}, размещено {{ listing.created_at }}
                            </div>
                            <div    v-if="!isAuthUserOwnThatPost(listing.owner.id)"
                                    class="listing__favorite-action-btn">
                                <v-btn color="primary"
                                       @click="toggleFavorite(listing.id)"
                                >{{ favoriteText }}</v-btn>
                            </div>
                        </v-flex>
                        <v-flex xs6>
                            <h1 class="listing__price text-xs-right">
                                {{ listing.price }} руб
                            </h1>
                        </v-flex>
                    </v-layout>
                </v-flex>
                <v-flex class="listing-middle-line">
                    <v-layout>
                        <v-flex xs8 class="listing__photos">
                            <v-carousel v-if="listing.images.length">
                                <v-carousel-item
                                                 v-for="photo in listing.images"
                                                 :key="photo.id"
                                                 :src="photo.url"
                                >
                                </v-carousel-item>
                            </v-carousel>
                            <img v-else :src="listing.main_photo" class="listing__photos-main-photo">
                        </v-flex>
                        <v-flex xs4 column class="listing__owner  pa-4 text-xs-center">
                            <div class="listing__owner-phone">
                                <div>Телефон</div>
                                {{ listing.owner.phone_number }}
                            </div>
                            <div class="listing__owner-info text-xs-left mt-2 mb-2">
                                <div class="color-secondary">Контактное Лицо:</div>
                                <div>{{ listing.owner.name }}</div>
                                <div>На сайте с {{ listing.owner.created_at }}</div>
                            </div>
                            <div class="listing__small-adress text-xs-left">
                                <div class="color-secondary">Адрес:</div>
                                {{  listing.place }}
                            </div>
                        </v-flex>
                    </v-layout>
                </v-flex>
                <v-flex class="listing-address">
                    <div class="listing-address-info">
                        <span class="pl-4 pr-4">Адрес:</span>
                        <p class="pl-4 pr-4">
                            {{ listing.place }}
                        </p>
                    </div>
                    <div id="listing-place-map" ref="listingPlaceMap"></div>
                </v-flex>
                <v-flex column class="listing__description pa-4">
                    <div>Описание:</div>
                    {{ listing.body }}
                </v-flex>
            </v-layout>
        </v-layout>
    </v-container>
</template>

<script>
   import {mapGetters,mapActions} from "vuex";
   import {AUTH_MODULE} from "../../constants/authConstants";
   import {SINGLE_LISTING_MODULE,singleListingActions} from "../../constants/singleListingConstants";
   import FullScreenLoader from "../Shared/FullScreenLoader";
   import ymaps from "ymaps";

   export default {
       components: {
           FullScreenLoader},
       name: 'single-listing-component',
       computed: {
           ...mapGetters({
               isLoggedIn: `${AUTH_MODULE}/isLoggedIn`,
               isAuthUserOwnThatPost: `${AUTH_MODULE}/isAuthUserOwner`,
               isLoading: `${SINGLE_LISTING_MODULE}/isLoading`,
               isLoaded: `${SINGLE_LISTING_MODULE}/isLoaded`,
               listing: `${SINGLE_LISTING_MODULE}/listing`,
               isFavoriteLoading: `${SINGLE_LISTING_MODULE}/isFavoriteLoading`
           }),
           favoriteText()
           {
               return this.listing.is_favorite  ? "Убрать из избранного" : "В Избранное"
           }
       },
       methods: {
           ...mapActions({
               fetchSingleListing: `${SINGLE_LISTING_MODULE}/${singleListingActions.FETCH_SINGLE_LISTING}`,
               toggleFavoriteStatus: `${SINGLE_LISTING_MODULE}/${singleListingActions.TOGGLE_FAVORITE_LISTING}`
           }),
           toggleFavorite(id)
           {
               if(!this.isLoggedIn){
                   this.$router.push({
                       name: 'login'
                   })
               }

               this.toggleFavoriteStatus(id);
           },
           loadMap(placeInfo)
           {
               this.$nextTick(() => {
                    const {listingPlaceMap} = this.$refs;
                    ymaps.load('https://api-maps.yandex.ru/2.1/?lang=ru_RU').then(ymaps => {
                        const map = new ymaps.Map(listingPlaceMap, {
                            center: [placeInfo.lat, placeInfo.lng],
                            zoom: 15
                        })

                        const placemark = new ymaps.Placemark([placeInfo.lat, placeInfo.lng], false);
                        map.geoObjects.add(placemark);
                   });

               });
           },
       },
       watch:
       {
            isLoaded(loaded)
            {
                if(loaded)
                    this.loadMap({lat: this.listing.lat, lng: this.listing.lng})
            }
       },
       mounted()
       {
           this.fetchSingleListing(this.$route.params.id);
       }
   }
</script>

<style lang="stylus" scoped>
    @import "../../styles/_variables.styl"
    .listing
        font-family: Arial
        border: 1px solid lightgray
        border-radius: 4px
        &__owner
            font-size: 1.1rem
            .color-secondary
                color: $secondary-color
            &-phone
                background-color: $primary-color
                color: white
                padding: 10px
                font-size: 1.2rem
        &__photos-main-photo
            width: 100%
            height: 500px
        &-address
            margin-top: 1rem
            &-info
                font-size: 1.2rem
                span
                    color: $secondary-color
        &__description
            div
                font-size: 1.2rem
                color: $secondary-color
    #listing-place-map
        width: 100%
        height: 300px
</style>
