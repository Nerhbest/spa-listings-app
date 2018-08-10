<template>
    <div>
        <div v-if="isLoading">
            <loader :isLoading="isLoading"></loader>
        </div>
        <div    v-else
                class="main-page">
            <v-container>
                <v-layout column>
                    <search-bar></search-bar>
                    <h3 class="main-page__title">
                        {{total}} {{listingsEnd(total)}} {{ lastSelectedCategory }}
                    </h3>
                    <main-listings></main-listings>
                </v-layout>
            </v-container>
        </div>
        <div class="main-page__go-to-top-btn" ref="toTopButton">
            <v-icon color="white">expand_less</v-icon>
        </div>
    </div>
</template>

<script>
   import {mapGetters,mapActions} from "vuex";
   import {MAIN_PAGE,mainPageActions,mainPageMutations} from "../../constants/mainPageConstants";
   import {MAIN_LISTINGS} from "../../constants/mainListingsConstants";
   import Loader from "../Shared/Loader";
   import SearchBar from "./SearchBar/SearchBar";
   import MainListings from "./MainListings/MainListings";

   export default {
       components: {
           MainListings,
           SearchBar,
           Loader},
       computed: {
        ...mapGetters({
            isLoading: `${MAIN_PAGE}/isLoading`,
            isLoaded: `${MAIN_PAGE}/isLoaded`,
            activeCategory: `${MAIN_PAGE}/activeCategory`,
            activeCategoryName: `${MAIN_PAGE}/activeCategoryName`,
            total: `${MAIN_LISTINGS}/total`,
            lastCategory: `${MAIN_PAGE}/lastCategory`,
            lastCategoryName: `${MAIN_PAGE}/lastCategoryName`
        }),
           lastSelectedCategory()
           {
               return this.lastCategory ? `в категории ${this.lastCategory.name}` : "во всех категориях";
           }
        },
        methods: {
            ...mapActions({
                fetchMainPageDetails: `${MAIN_PAGE}/${mainPageActions.FETCH_MAIN_PAGE_DETAILS}`
            }),
            listingsEnd(total) {
                return this.declOfNum(total, ['Объявление','Объявления','Объявлений'])
            },
            declOfNum(number, titles) {
                let cases = [2, 0, 1, 1, 1, 2];
                return titles[ (number%100 > 4 && number%100 < 20) ? 2 : cases[(number%10<5) ? number%10:5] ];
            },
            setUpFixedButton()
            {
                const {toTopButton} = this.$refs;
                toTopButton.addEventListener('click', () => {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    })
                })
                let isButtonVisible = false;
                window.addEventListener('scroll', e => {
                    if(window.pageYOffset > 600 && !isButtonVisible){
                        toTopButton.classList.add('main-page__go-to-top-btn--visible');
                        isButtonVisible = true;
                    }
                    if(window.pageYOffset < 600 && isButtonVisible){
                        toTopButton.classList.remove('main-page__go-to-top-btn--visible');
                        isButtonVisible = false;
                    }
                })
            }
        },
        mounted() {
            this.fetchMainPageDetails();
            this.setUpFixedButton();
        }
   } 
</script>

<style lang="stylus">
    @import "../../styles/_variables.styl"

    .main-page
        &__title
            text-align: center
            padding: 10px
            border-bottom: 1px solid $secondary-color
        &__go-to-top-btn
            position: fixed
            bottom: 15px
            right: 15px
            width: 50px
            height: 50px
            background-color: $primary-color
            border-radius: 50%
            display: flex
            justify-content: center
            transition: background-color .4s
            visibility: hidden
            &--visible
                visibility: visible
            &:hover
                background-color: $secondary-color
            i
                font-size:2.5rem
</style>


