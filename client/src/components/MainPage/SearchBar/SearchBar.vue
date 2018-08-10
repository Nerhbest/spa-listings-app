<template>
    <div class="search-bar">
        <v-layout class="search-bar" row>
            <v-flex class="search-bar__category" xs3>
                <input type="text"
                       name="category"
                       placeholder="Любая категория"
                       @click="toggleCategoryContainer()"
                       :value="activeCategoryName"
                >
                <v-icon v-if="activeCategory"
                        class="search-bar__category-reset-btn"
                        @click="resetCategory()"
                >close</v-icon>
            </v-flex>
            <v-flex class="search-bar__search-input" xs5>
                <input type="text"
                       name="search-input"
                       placeholder="Поиск по объявлениям"
                       @input="updateSearchTerm"
                >
            </v-flex>
            <v-flex class="search-bar__city" xs3>
                <input type="text"
                       name="city"
                       placeholder="Город"
                       @click="toggleCityContainer()"
                       :value="activeCityName"
                >
                <v-icon v-if="activeCity"
                        class="search-bar__city-reset-btn"
                        @click="resetCity()"
                >close</v-icon>
            </v-flex>
            <v-flex class="search-bar__find" xs1>
                <button @click="searchListingsByParams">Найти</button>
            </v-flex>
        </v-layout>
        <category-dialog-container
                                   :categories="categories"
                                   :needShowCategoryContainer="needShowCategoryContainer"
                                   @toggle-category-container="toggleCategoryContainer"
                                   @handle-active-category="handleActiveCategory"
        >
        </category-dialog-container>
        <city-dialog-container
                                :cities="cities"
                                :needShowCityContainer="needShowCityContainer"
                                @toggle-city-container="toggleCityContainer"
                                @handle-active-city="handleActiveCity"
        >
        </city-dialog-container>
    </div>
</template>

<script>
    import {mapActions, mapGetters, mapMutations} from "vuex";
    import {MAIN_PAGE, mainPageActions, mainPageMutations} from "../../../constants/mainPageConstants";
    import CategoryDialogContainer from "./CategoryDialogContainer/CategoryDialogContainer";
    import CityDialogContainer from "./CityDialogContainer/CityDialogContainer";
    import ListingsService from "../../../services/ListingsService";
    import throttle from "lodash/throttle";

    export default {
       components: {
           CityDialogContainer,
           CategoryDialogContainer},
       name: 'search-bar',
       data(){
           return {
               needShowCategoryContainer: false,
               needShowCityContainer: false
           }
       },
       computed: {
       ...mapGetters({
           categories: `${MAIN_PAGE}/categories`,
           cities : `${MAIN_PAGE}/cities`,
           activeCategory: `${MAIN_PAGE}/activeCategory`,
           activeCity: `${MAIN_PAGE}/activeCity`,
           searchTerm: `${MAIN_PAGE}/searchTerm`,
           activeCategoryName: `${MAIN_PAGE}/activeCategoryName`,
           activeCityName: `${MAIN_PAGE}/activeCityName`
       })
       },
       methods: {
           ...mapMutations({
               putActiveCategory: `${MAIN_PAGE}/${mainPageMutations.PUT_ACTIVE_CATEGORY}`,
               putActiveCity: `${MAIN_PAGE}/${mainPageMutations.PUT_ACTIVE_CITY}`,
               putSearchTerm: `${MAIN_PAGE}/${mainPageMutations.PUT_SEARCH_TERM}`,
               resetCategory: `${MAIN_PAGE}/${mainPageMutations.RESET_CATEGORY}`,
               resetCity: `${MAIN_PAGE}/${mainPageMutations.RESET_CITY}`
           }),
           ...mapActions({
               searchListingsByParams: `${MAIN_PAGE}/${mainPageActions.SEARCH_LISTINGS_BY_PARAMS}`
          }),
           toggleCategoryContainer()
           {
               this.needShowCategoryContainer = !this.needShowCategoryContainer;
           },
           toggleCityContainer() {
                this.needShowCityContainer = !this.needShowCityContainer;
           },
           handleActiveCity(city)
           {
                  this.putActiveCity(city);
           },
           handleActiveCategory(category)
           {
               this.putActiveCategory(category);
           },
           updateSearchTerm(e){
                const {value} = e.target;
                this.putSearchTerm(value);
           }
       }
   }
</script>

<style lang="stylus">
    @import "../../../styles/_variables.styl"
    .search-bar
        border: 2px solid $secondary-color
        border-radius: 4px
        &__category
            position: relative
            &-reset-btn
                position: absolute
                right: 10px
                top: 20%
                &:hover
                    background-color: $secondary-color
                    border-radius: 50%
        &__city
            position: relative
            &-reset-btn
                position: absolute
                right: 10px
                top: 20%
                &:hover
                    background-color: $secondary-color
                    border-radius: 50%
        input
            display: block
            width: 100%
            border-right: 1px solid $secondary-color
            padding: 10px
            outline: none
        &__find
            button
                display: block
                background: $primary-color
                width: 100%
                color: white
                padding: 10px
        &__search-input
            position: relative
            .search-bar__container
                position: absolute
                top: 45px
                left: 0
                width: 100%
                z-index: 5000
                background-color: white
                li
                    padding: 15px
                    border-bottom: 2px solid black

    .category-container
        &__title
            background-color: $primary-color
            color: white
            justify-content: center
            font-family: "PT Sans"
</style>
