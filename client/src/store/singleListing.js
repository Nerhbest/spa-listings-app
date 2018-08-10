import {singleListingActions,singleListingMutations} from "../constants/singleListingConstants";
import ListingsService from "../services/ListingsService";
import {NOTIFICATION_MODULE,noticationMutations,notificationTypes} from "../constants/notificationConstants";
const initialState = {
    isLoading: false,
    isLoaded: false,
    listing: {},
    isFavoriteLoading: false
}

export const state = Object.assign({},initialState);

export const actions = {
    [singleListingActions.FETCH_SINGLE_LISTING](context,id)
    {
        context.commit(singleListingMutations.FETCH_SINGLE_LISTING_START);
        ListingsService.fetchSingleListing(id).then(data => {
            const {listing} = data;
            context.commit(singleListingMutations.PUT_SINGLE_LISTING, listing);
        })
    },
    [singleListingActions.TOGGLE_FAVORITE_LISTING](context,listingId)
    {
        context.commit(singleListingMutations.PUT_FAVORITE_LOADING);
        ListingsService.toggleFavoriteListing(listingId).then( data => {
            context.commit(singleListingMutations.UPDATE_FAVORITE_STATUS, data);
            context.commit(`${NOTIFICATION_MODULE}/${noticationMutations.SET_NOTIFICATION}`, {
                type: notificationTypes.SUCCESS,
                msg: data.msg
            }, {root: true});
        }).catch(err => {
            let serverErrors = err.response.data.errors;
            context.commit(singleListingMutations.HIDE_FAVORITE_ACTION_LOADER);
            context.commit(`${NOTIFICATION_MODULE}/${noticationMutations.SET_NOTIFICATION}`, {
                type: notificationTypes.ERROR,
                msg: serverErrors.msg
            }, {root: true});
        })
    }
};

export const mutations = {
    [singleListingMutations.FETCH_SINGLE_LISTING_START](state) {
        state.isLoading = true;
        state.isLoaded = false;
    },
    [singleListingMutations.PUT_SINGLE_LISTING](state,listing) {
        state.isLoading = false;
        state.isLoaded = true;
        state.listing = listing;
    },
    [singleListingMutations.PUT_FAVORITE_LOADING](state)
    {
        state.isFavoriteLoading = true;
    },
    [singleListingMutations.UPDATE_FAVORITE_STATUS](state, data)
    {
        state.isFavoriteLoading = false;
        state.listing.is_favorite = data.is_favorite;
    },
    [singleListingMutations.HIDE_FAVORITE_ACTION_LOADER](state)
    {
        state.isFavoriteLoading = false;
    }
}

const getters = {
   isLoading: state => state.isLoading,
   isLoaded: state => state.isLoaded,
   listing: state => state.listing,
   isFavoriteLoading: state => state.isFavoriteLoading
};

export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
};