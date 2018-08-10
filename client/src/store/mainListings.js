import {mainListingsActions,mainListingsMutations} from "../constants/mainListingsConstants";
import {mapArrayToObjectById} from "../utils/transformers/mapArrayToObjectByid";
import ListingsService from "../services/ListingsService";
import merge from "lodash/merge";

const initialState = {
    isLoading: false,
    listings: [],
    current_page: null,
    last_page: null,
    total: null,
    scrollToListingId: null
};

export const state = Object.assign({},initialState);

export const actions = {
    [mainListingsActions.FETCH_MORE_LISTINGS](context, page)
    {
        context.commit(`${mainListingsMutations.FETCH_MORE_LISTINGS_START}`);


        ListingsService.fetchListings(page).then(data => {
            const {listingsPaginator} = data;
            context.commit(`${mainListingsMutations.PUT_MORE_LISTINGS}` , listingsPaginator);
        });
    }
};

export const mutations = {
    [mainListingsMutations.FETCH_RECENT_LISTINGS_START](state) {
        state.isLoading = true;
    },
    [mainListingsMutations.PUT_RECENT_LISTINGS](state,listingsPaginator) {
        const {data,current_page,total,last_page} = listingsPaginator;

        state.isLoading = false;
        state.listings =  data;
        state.current_page = current_page;
        state.total = total;
        state.last_page = last_page;
    },
    [mainListingsMutations.FETCH_MORE_LISTINGS_START](state)
    {
        state.isLoading = true;
    },
    [mainListingsMutations.PUT_MORE_LISTINGS](state,listingsPaginator)
    {
        const {data,current_page,total,last_page} = listingsPaginator;

        state.isLoading = false;
        state.listings = state.listings.concat(data);
        state.current_page = current_page;
        state.total = total;
        state.last_page = last_page;
        state.scrollToListingId = data[0].id;
    }
};

const getters = {
    listings: state => state.listings,
    current_page: state => state.current_page,
    total: state => state.total,
    last_page: state => state.last_page,
    isLoading: state => state.isLoading,
    scrollToListingId: state => state.scrollToListingId
};

export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
};