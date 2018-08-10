import {mainPageActions,mainPageMutations} from "../constants/mainPageConstants";
import MainPageService from "../services/MainPageService";
import {mainListingsMutations,mainListingsActions,MAIN_LISTINGS} from "../constants/mainListingsConstants";
import {mapArrayToObjectById} from "../utils/transformers/mapArrayToObjectByid";
import pick from "lodash/pick";
import ListingsService from "../services/ListingsService";

const initialState = {
    isLoading: false,
    isLoaded: false,
    categories: [],
    cities: [],
    activeCategory: null,
    activeCity: null,
    searchTerm: null,
    lastCategory: null
}

export const state = Object.assign({},initialState);

export const actions = {
    [mainPageActions.FETCH_MAIN_PAGE_DETAILS](context)
    {
        let sliceParams = pick(context.state, ["activeCategory", "activeCity", "searchTerm"]);
        let params = {
            category: sliceParams.activeCategory ? sliceParams.activeCategory.id : null,
            city: sliceParams.activeCity ? sliceParams.activeCity.id : null,
            searchTerm: sliceParams.searchTerm ? sliceParams.searchTerm : null
        }

        context.commit(mainPageMutations.FETCH_MAIN_PAGE_START);

        MainPageService.getMainPageDetails(params).then(data => {
            context.commit(`${MAIN_LISTINGS}/${mainListingsMutations.PUT_RECENT_LISTINGS}`, data.listingsPaginator, {root: true});
            context.commit(mainPageMutations.PUT_MAIN_PAGE_DETAILS, data);
            context.commit(mainPageMutations.SET_LAST_CATEGORY);
        })
    },
    [mainPageActions.SEARCH_LISTINGS_BY_PARAMS](context)
    {
        let sliceParams = pick(context.state, ["activeCategory", "activeCity", "searchTerm"]);
        let params = {
            category: sliceParams.activeCategory ? sliceParams.activeCategory.id : null,
            city: sliceParams.activeCity ? sliceParams.activeCity.id : null,
            searchTerm: sliceParams.searchTerm ? sliceParams.searchTerm : null
        }

        context.commit(`${MAIN_LISTINGS}/${mainListingsMutations.FETCH_RECENT_LISTINGS_START}`, null, {root: true});

        ListingsService.searchListingsByParams(params).then(data => {
                console.log(data);
                const {listingsPaginator} = data;
                context.commit(`${MAIN_LISTINGS}/${mainListingsMutations.PUT_RECENT_LISTINGS}`, listingsPaginator, {root: true});
                context.commit(mainPageMutations.SET_LAST_CATEGORY);
        })
    },
    [mainPageActions.LOAD_MORE_LISTINGS_BY_PARAMS](context, page)
    {
        let sliceParams = pick(context.state, ["activeCategory", "activeCity", "searchTerm"]);
        let params = {
            category: sliceParams.activeCategory ? sliceParams.activeCategory.id : null,
            city: sliceParams.activeCity ? sliceParams.activeCity.id : null,
            searchTerm: sliceParams.searchTerm ? sliceParams.searchTerm : null,
            page: page
        }

        context.commit(`${MAIN_LISTINGS}/${mainListingsMutations.FETCH_RECENT_LISTINGS_START}`, null, {root: true});

        ListingsService.searchListingsByParams(params).then(data => {
            const {listingsPaginator} = data;
            console.log(listingsPaginator);
            context.commit(`${MAIN_LISTINGS}/${mainListingsMutations.PUT_MORE_LISTINGS}`, listingsPaginator, {root: true});
        })
    }
};

export const mutations = {
    [mainPageMutations.FETCH_MAIN_PAGE_START](state)
    {
        state.isLoading = true;
    },
    [mainPageMutations.PUT_MAIN_PAGE_DETAILS](state,data)
    {
        state.isLoading = false;
        state.categories = mapArrayToObjectById(data.categories);
        state.cities = mapArrayToObjectById(data.cities);
    },
    [mainPageMutations.PUT_ACTIVE_CATEGORY](state,category)
    {
        state.activeCategory = category;
    },
    [mainPageMutations.PUT_ACTIVE_CITY](state,city)
    {
        state.activeCity = city;
    },
    [mainPageMutations.PUT_SEARCH_TERM](state,searchTerm)
    {
        state.searchTerm = searchTerm;
    },
    [mainPageMutations.SET_LAST_CATEGORY](state)
    {
            state.lastCategory = state.activeCategory;
    },
    [mainPageMutations.RESET_CITY](state)
    {
        state.activeCity = null;
    },
    [mainPageMutations.RESET_CATEGORY](state)
    {
        state.activeCategory = null;
    }
};

const getters = {
   categories: state => state.categories,
   cities: state => state.cities,
   isLoading: state => state.isLoading,
   isLoaded: state => state.isLoaded,
   activeCategory: state => state.activeCategory,
   activeCity: state => state.activeCity,
   searchTerm: state => state.searchTerm,
   activeCategoryName: state => {
       const {activeCategory} = state;
       return activeCategory ? activeCategory.name : null;
   },
   activeCityName: state => {
       const {activeCity} = state;
       return activeCity ? activeCity.name : null;
   },
   lastCategoryName: state => state.lastCategory ? state.lastCategory.name : null,
   lastCategory: state => state.lastCategory
};

export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
};