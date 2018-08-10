import {accountActions,accountMutations} from "../constants/accountConstants";
import AccountService from "../services/AccountService";
import ListingsService from "../services/ListingsService";
import {NOTIFICATION_MODULE, notificationActions, noticationMutations} from "../constants/notificationConstants";

const initialState = {
    isLoading: false,
    isLoaded: false,
    userInfo: null,

    isListingsLoading: false,
    listingsPaginator: {
        current_page: null,
        data: null,
        last_page: null,
        total: null
    },

    isFavoriteListingLoading: false,
    favoriteListingsPaginator: {
        current_page: null,
        data: null,
        last_page: null,
        total: null
    },

    isFavoriteActionLoading: false,
    needShowLogOutDialog: false,

    listingIdForDelete: null,
    isDeleteActionLoading: false
}

export const state = Object.assign({},initialState);

export const actions = {
    [accountActions.FETCH_ACCOUNT_INFO](context)
    {
        context.commit(accountMutations.FETCH_ACCOUNT_INFO_START);
        AccountService.fetchAccountInfo().then(resp => {
            let {accountInfo} = resp;
            context.commit(accountMutations.PUT_ACCOUNT_INFO, accountInfo);
        }).catch(err => {
            console.dir(err);
        })
    },

    [accountActions.LOAD_MORE_USER_LISTINGS](context,page)
    {
        context.commit(accountMutations.LOAD_MORE_USER_LISTINGS_START);
        ListingsService.fetchUserListings(page).then(resp => {
            const {listingsPaginator} = resp;
            context.commit(accountMutations.PUT_USER_LISTINGS, listingsPaginator);
        })
    },

    [accountActions.LOAD_MORE_FAVORITE_LISTINGS](context,page)
    {
        context.commit(accountMutations.LOAD_MORE_FAVORITE_LISTINGS_START);
        ListingsService.fetchUserFavoritesListings(page).then(data => {
                const {favoriteListingsPaginator} = data;
                context.commit(accountMutations.PUT_USER_FAVORITES_LISTINGS,favoriteListingsPaginator);
        })
    },

    [accountActions.REMOVE_FROM_FAVORITE_LISTINGS](context,listingId)
    {
        context.commit(accountMutations.FAVORITE_ACTION_START);
        ListingsService.toggleFavoriteListing(listingId).then(data => {
            context.commit(accountMutations.REMOVE_LISTING_FROM_FAVORITES, listingId);
            context.commit(`${NOTIFICATION_MODULE}/${noticationMutations.SET_NOTIFICATION}`, {
                msg: data.msg
            }, {root: true});
        })
    },

    [accountActions.DELETE_LISTING](context)
    {
        let listingId = context.state.listingIdForDelete;


        if(!listingId){
            context.commit(accountMutations.HIDE_DELETE_DIALOG);
            context.commit(`${NOTIFICATION_MODULE}/${noticationMutations.SET_NOTIFICATION}`, {
                msg: 'Сначала укажите объявление которые намереваетесь удалить'
            }, {root: true});
        }

        context.commit(accountMutations.DELETE_ACTION_START);

        ListingsService.deleteListing(listingId).then(data => {

            context.commit(accountMutations.REMOVE_LISTING_FROM_LIST, listingId);
            context.commit(accountMutations.HIDE_DELETE_DIALOG);
            context.commit(`${NOTIFICATION_MODULE}/${noticationMutations.SET_NOTIFICATION}`, {
                msg: data.msg
            }, {root: true});

        }).catch(err => {
            console.log("ERRR");
            let serverErrors = err.response.data.errors;
            context.commit(accountMutations.HIDE_DELETE_DIALOG);
            context.commit(`${NOTIFICATION_MODULE}/${noticationMutations.SET_NOTIFICATION}`, {
                msg: serverErrors.msg
            }, {root: true});
        })
    }
};

export const mutations = {
    [accountMutations.FETCH_ACCOUNT_INFO_START](state)
    {
        state.isLoading = true;
    },
    [accountMutations.LOAD_MORE_USER_LISTINGS_START](state)
    {
      state.isListingsLoading = true;
    },
    [accountMutations.LOAD_MORE_FAVORITE_LISTINGS_START](state)
    {
        state.isFavoriteListingLoading = true;
    },
    [accountMutations.FAVORITE_ACTION_START](state)
    {
        state.isFavoriteActionLoading = true;
    },
    [accountMutations.PUT_USER_LISTINGS](state,listingsPaginator)
    {
        state.isListingsLoading = false;

        state.listingsPaginator  = {
            current_page: listingsPaginator.current_page,
            data: listingsPaginator.data,
            last_page: listingsPaginator.last_page,
            total: listingsPaginator.total
        };
    },
    [accountMutations.PUT_USER_FAVORITES_LISTINGS](state,favoriteListingsPaginator)
    {
        state.isFavoriteListingLoading = false;

        state.favoriteListingsPaginator = {
            current_page: favoriteListingsPaginator.current_page,
            data: favoriteListingsPaginator.data,
            last_page: favoriteListingsPaginator.last_page,
            total: favoriteListingsPaginator.total
        };
    },
    [accountMutations.PUT_ACCOUNT_INFO](state,data)
    {
        const {listingsPaginator,favoriteListingsPaginator} = data ;

        state.isLoading = false;
        state.isLoaded = true;
        state.userInfo = data.userInfo;
        state.listingsPaginator  = {
            current_page: listingsPaginator.current_page,
            data: listingsPaginator.data,
            last_page: listingsPaginator.last_page,
            total: listingsPaginator.total
        };
        state.favoriteListingsPaginator = {
            current_page: favoriteListingsPaginator.current_page,
            data: favoriteListingsPaginator.data,
            last_page: favoriteListingsPaginator.last_page,
            total: favoriteListingsPaginator.total
        };
    },
    [accountMutations.PUT_USER_INFO](state,data)
    {
        state.userInfo.name = data.name;
        state.userInfo.email = data.email;
        state.userInfo.phone_number = data.phone_number;
    },
    [accountMutations.REMOVE_LISTING_FROM_FAVORITES](state,listingId)
    {
        state.isFavoriteActionLoading = false;
        state.favoriteListingsPaginator.data = state.favoriteListingsPaginator.data.filter(favoriteListing => favoriteListing.id !== listingId);
    },
    [accountMutations.OPEN_LOGOUT_DIALOG](state)
    {
        state.needShowLogOutDialog = true;
    },
    [accountMutations.HIDE_LOGOUT_DIALOG](state)
    {
        state.needShowLogOutDialog = false;
    },

    [accountMutations.DELETE_ACTION_START](state)
    {
        state.isDeleteActionLoading = true;
    },
    [accountMutations.PUT_LISTING_ID_FOR_DELETE](state,listingId)
    {
        console.log(listingId)
        state.listingIdForDelete = listingId;
    },
    [accountMutations.REMOVE_LISTING_FROM_LIST](state,listingId)
    {
        state.listingsPaginator.data = state.listingsPaginator.data.filter(listing => listing.id != listingId);
    },
    [accountMutations.HIDE_DELETE_DIALOG](state)
    {
        state.isDeleteActionLoading = false;
        state.listingIdForDelete = null;
    }
};

const getters = {
    isLoading: state => state.isLoading,
    isLoaded: state => state.isLoaded,
    userInfo: state => state.userInfo,

    isListingsLoading: state => state.isListingsLoading,
    listingsPaginator: state => state.listingsPaginator,
    listingsPaginatorCurrentPage: state => state.listingsPaginator.current_page,
    listingsPaginatorLastPage: state => state.listingsPaginator.last_page,
    listingsPaginatorTotal: state => state.listingsPaginator.total,

    isFavoriteListingLoading: state => state.isFavoriteListingLoading,
    favoriteListingsPaginator: state => state.favoriteListingsPaginator,
    favoriteListingsPaginatorCurrentPage: state => state.favoriteListingsPaginator.current_page,
    favoriteListingsPaginatorTotal: state => state.favoriteListingsPaginator.total,
    favoriteListingsPaginatorLastPage: state => state.favoriteListingsPaginator.last_page,

    needShowLogOutDialog: state => state.needShowLogOutDialog,
    isFavoriteActionLoading: state => state.isFavoriteActionLoading,


    needShowDeleteDialog: state => state.listingIdForDelete != null,
    isDeleteActionLoading: state => state.isDeleteActionLoading
};

export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
};