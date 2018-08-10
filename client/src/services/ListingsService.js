import axios from "axios";
import {BASE_URL} from "../config/index";

export default class ListingsService
{
    static searchListingsByParams(params)
    {
        return axios.get(`${BASE_URL}/listing/search`, {
            params
        }).then(resp => resp.data);
    }

    static fetchListings(page = 1)
    {
        return axios.get(`${BASE_URL}/listing?page=${page}`).then(resp => resp.data);
    }

    static fetchListingDataForCreate()
    {
        return axios.get(`${BASE_URL}/listing/create`).then(resp => resp.data);
    }

    static fetchListingDataForUpdate(id)
    {
        return axios.get(`${BASE_URL}/listing/edit/${id}`).then(resp => resp.data);
    }

    static storeListing(formData)
    {
        return axios.post(`${BASE_URL}/listing/store`, formData).then(resp => resp.data);
    }

    static updateListing(formData)
    {
        return axios.post(`${BASE_URL}/listing/update`, formData).then(resp => resp.data);
    }

    static deleteListing(listingId)
    {
        return axios.post(`${BASE_URL}/listing/${listingId}/delete`).then(resp => resp.data);
    }

    static fetchUserListings(page = 1)
    {
        return axios.get(`${BASE_URL}/account/listing?page=${page}`).then(resp => resp.data);
    }

    static fetchUserFavoritesListings(page = 1)
    {
        return axios.get(`${BASE_URL}/account/favorite-listing?page=${page}`).then(resp => resp.data);
    }

    static fetchSingleListing(id)
    {
        return axios.get(`${BASE_URL}/listing/${id}`).then(resp => resp.data);
    }

    static toggleFavoriteListing(listingId)
    {
        return axios.post(`${BASE_URL}/listing/favorite/${listingId}`).then(resp => resp.data);
    }

    static uploadPhoto(formData)
    {
        return axios.post(`${BASE_URL}/listing/photo/upload`, formData).then(resp => resp.data);
    }

    static removePhoto(photoInfo)
    {
        return axios.post(`${BASE_URL}/listing/photo/remove`,photoInfo).then(resp => resp.data);
    }
};