<template>
    <div>
        <full-screen-loader :isLoading="isLoading || isUpdatePhotoLoading"></full-screen-loader>
        <form class="create-listing-form">
            <h3 class="text-xs-center">{{  formTitle }}</h3>

            <div class="create-listing__input-wrapper">
                <label v-if="activeCategoryName">Категория:</label>
                <input type="text"
                       name="listing.category"
                       v-validate="'required'"
                       id="category"
                       placeholder="Выберите Категорию"
                       @click="toggleCategoryContainer()"
                       :value="activeCategoryName"
                       :class="{'has-error' : errors.has('listing.category')}"
                >
                <field-error v-if="errors.has('listing.category')"
                             :errorMsg="errors.first('listing.category')"
                ></field-error>
            </div>

            <div class="create-listing__input-wrapper">
                <label v-if="activeCityName">Город:</label>
                <input type="text"
                       name="listing.city"
                       v-validate="'required'"
                       id="city"
                       placeholder="Выберите Город"
                       @click="toggleCityContainer()"
                       :value="activeCityName"
                       :class="{'has-error' : errors.has('listing.city')}"
                >
                <field-error v-if="errors.has('listing.city')"
                             :errorMsg="errors.first('listing.city')"
                ></field-error>
            </div>

            <v-text-field
                    v-validate="'required|min:2|max:30'"
                    v-model="listing.title"
                    :error-messages="errors.first('title')"
                    label="Название"
                    data-vv-name="title"
            ></v-text-field>
            <v-text-field
                    class="create-listing__price"
                    v-validate="'required'"
                    v-model="listing.price"
                    :error-messages="errors.first('price')"
                    label="Цена"
                    data-vv-name="price"
                    type="number"
            ></v-text-field>
            <div>
                <v-textarea
                        name="body"
                        v-validate="'required'"
                        v-model="listing.body"
                        :error-messages="errors.first('body')"
                        label="Описание"
                        data-vv-name="body"
                ></v-textarea>
            </div>

            <div class="create-listing__input-wrapper" v-show="activeCityName">
                <label v-if="activePlaceName">Место сделки</label>
                <input type="text"
                       name="place"
                       placeholder="Укажите место сделки на карте"
                       :value="activePlaceName"
                       disabled
                >
                <div id="listing-place-map"
                     ref="listingPlaceMap"
                ></div>
            </div>


            <image-uploader
                    v-if="isCreateMode"
                    :photos="photos"
                    :maxPhotosCount="maxPhotosCount"
                    :needShowPhotosContainer="needShowPhotosContainer"
                    :mode="mode"
                    @uploadPhoto="uploadPhoto"
                    @removePhoto="removePhotoFromComponent"

            ></image-uploader>

            <image-uploader
                    v-if="isUpdateMode"
                    :photos="photosLinks"
                    :maxPhotosCount="maxPhotosCount"
                    :needShowPhotosContainer="needShowPhotosUpdateContainer"
                    @uploadPhoto="uploadPhotoToServer"
                    @removePhoto="removePhotoFromServer"
                    :mode="mode"
            ></image-uploader>

            <v-btn color="primary"
                   @click.prevent="sendListing"
                   class="create-listing-form__btn-send"
            >{{ buttonText }}</v-btn>

            <category-dialog-container  :needShowCategoryContainer="needShowCategoryContainer"
                                        :categories="categories"
                                        @toggle-category-container="toggleCategoryContainer"
                                        @handle-active-category="putActiveCategory"
            ></category-dialog-container>
            <city-dialog-container :needShowCityContainer="needShowCityContainer"
                                   :cities="cities"
                                   @toggle-city-container="toggleCityContainer"
                                   @handle-active-city="putActiveCity"
            ></city-dialog-container>
        </form>
    </div>
</template>

<script>
    import ListingsService from "../../../services/ListingsService";
    import CategoryDialogContainer from "../../MainPage/SearchBar/CategoryDialogContainer/CategoryDialogContainer";
    import CityDialogContainer from "../../MainPage/SearchBar/CityDialogContainer/CityDialogContainer";
    import {ListingPlaceMapMixin} from "./Mixins/ListingPlaceMapMixin";
    import {UploadListingFile} from "./Mixins/UploadListingFile";
    import CreateListingPhotos from "./CreateListingPhotos/CreateListingPhotos";
    import FieldError from "../../Shared/FieldError";
    import FullScreenLoader from "../../Shared/FullScreenLoader";
    import {NOTIFICATION_MODULE,notificationTypes,notificationActions} from "../../../constants/notificationConstants";
    import ImageUploader from "./ImageUploader/ImageUploader";


   export default {
       components: {
           ImageUploader,
           FullScreenLoader,
           FieldError,
           CreateListingPhotos,
           CityDialogContainer,
           CategoryDialogContainer},
       name: 'create-listing-form',
       mixins: [ ListingPlaceMapMixin , UploadListingFile],
       props: ['mode'],
       data: () => ({
           isLoading: false,
           needShowCategoryContainer: false,
           needShowCityContainer: false,
           listing: {
               id: null, // id need for update mode
               category: null,
               city: null,
               title: "",
               body: "",
               price: null,
               place: "",
               lat: null,
               lng: null
           },
           categories: [],
           cities: [],
           isLoadedForUpdateMode: false
       }),
       watch: {
         'listing.city': function(newCity,oldCity) {
             if(newCity == null){
                 this.clearListing();
                 return;
             }

             if(newCity && oldCity == null && this.mode == 'update'){
                 const {listingPlaceMap} = this.$refs;
                 this.loadMap(listingPlaceMap, this.makeCityInfo({
                     name: this.listing.place,
                     lat: this.listing.lat,
                     lng: this.listing.lng
                 }), this.setPlaceInfo);
                 return;
             }

             if(!this.isMapAlreadyLoaded()){
                 const {listingPlaceMap} = this.$refs;
                 this.loadMap(listingPlaceMap, this.makeCityInfo(newCity), this.setPlaceInfo);
             }else{
                if(this.isCityChanged(newCity, oldCity)){
                    this.updateCoordsInMap(this.makeCityInfo(newCity));
                    this.setPlaceInfo(newCity);
                }
             }
         }
       },
       computed: {
           activeCategoryName()  {
               const {category} = this.listing;
               return category ? category.name : null;
           },
           activeCityName()  {
               const {city} = this.listing;
               return city ? city.name : null;
           },
           activePlaceName()
           {
               const {place} = this.listing;
               return place ? place: null;
           },
           formTitle()
           {
               return this.mode == "create" ? "Создать объявление" : "Редактировать объявление";
           },
           isUpdateMode()
           {
               return this.mode == "update";
           },
           isCreateMode()
           {
               return this.mode == "create";
           },
           buttonText()
           {
               if(this.mode == 'create')
                   return "Опубликовать";
               else
                   return "Редактировать";

           }
       },
       methods: {
         sendListing(e)
         {
             switch(this.mode) {
                 case 'create':
                     this.$validator.validate().then(valid => {
                         if(valid)
                             this.createListing();
                     })
                     break;
                 case 'update':
                     this.$validator.validate().then(valid => {
                         if(valid){
                             this.updateListing();
                         }
                     })
                     break;
             }
         },
         makeFormDataFromListing() {
            let formData = new FormData();

            if(this.mode == 'update')
                formData.append('id', this.listing.id);

            formData.append('category_id', this.listing.category.id);
            formData.append('city_id', this.listing.city.id);
            formData.append('title', this.listing.title);
            formData.append('body', this.listing.body);
            formData.append('price', this.listing.price);
            formData.append('place', this.listing.place);
            formData.append('lat', this.listing.lat);
            formData.append('lng', this.listing.lng);

            if(this.mode == 'create'){
                if(this.photos.length){
                    this.photos.forEach(photo => {
                        formData.append("photos[]", photo);
                    })
                }
            }

            return formData;

         },
         updateListing()
         {
             let formData = this.makeFormDataFromListing();
             this.isLoading = true;

             ListingsService.updateListing(formData).then(data => {
                this.isLoading = false;
                this.fillListing(data.listing);

                 this.$store.dispatch(`${NOTIFICATION_MODULE}/${notificationActions.DISPLAY_NOTIFICATION}`, {
                     type: `${NOTIFICATION_MODULE}/${notificationTypes.SUCCESS}`,
                     msg: data.msg
                 })
             }).catch(err => {
                 this.isLoading = false;
                 let serverErrors = err.response.data.errors;

                 for(let key in serverErrors){
                     this.$validator.errors.add(key,serverErrors[key]);
                 }
             })


         },
         createListing()
         {
             let formData = this.makeFormDataFromListing();
             this.isLoading = true;
             ListingsService.storeListing(formData).then(data => {
                 this.isLoading = false;

                 this.$store.dispatch(`${NOTIFICATION_MODULE}/${notificationActions.DISPLAY_NOTIFICATION}`, {
                     type: `${NOTIFICATION_MODULE}/${notificationTypes.SUCCESS}`,
                     msg: data.msg
                 })

                 this.$router.push({
                     name: 'account'
                 })

             }).catch(err => {
                 this.isLoading = false;
                 let serverErrors = err.response.data.errors;

                 for(let key in serverErrors){
                     this.$validator.errors.add(key,serverErrors[key]);
                 }
             })
         },
         putActiveCity(city)
         {
             this.listing.city = city;
         },
         putActiveCategory(category)
         {
             this.listing.category = category;
         },
         toggleCategoryContainer()
         {
               this.needShowCategoryContainer = !this.needShowCategoryContainer;
         },
         toggleCityContainer() {
               this.needShowCityContainer = !this.needShowCityContainer;
         },
         isCityChanged(newCity,oldCity)
         {
             return (newCity.id !== oldCity.id) ? true : false;
         },
         makeCityInfo(city)
         {
             return {
                 lat: city.lat,
                 lng: city.lng,
                 name: city.name
             }
         },
         setPlaceInfo(placeInfo)
         {
             this.listing.lat= placeInfo.lat;
             this.listing.lng = placeInfo.lng;
             this.listing.place = placeInfo.name;
         },
         fillListing(listing)
         {
             this.listing =  {
                 id: listing.id,
                 category: listing.category,
                 city: listing.city,
                 title: listing.title,
                 body: listing.body,
                 price: listing.price,
                 place: listing.place,
                 lat: listing.lat,
                 lng: listing.lng
             };

         },
         clearListing()
         {
             this.listing = {
                 id: null, // id need for update mode
                 category: null,
                 city: null,
                 title: "",
                 body: "",
                 price: null,
                 place: "",
                 lat: null,
                 lng: null
             }
         }
       },
       $_veeValidate:
       {
           events: ""
       },
       mounted()
       {
           switch(this.mode){
               case 'create':
                   ListingsService.fetchListingDataForCreate().then(data => {
                       this.categories = data.categories;
                       this.cities = data.cities;
                   })
                   break;
               case 'update':
                   this.isLoading = true;
                   ListingsService.fetchListingDataForUpdate(this.$route.params.id).then(data => {
                       const {categories,cities,listing} = data;
                       this.categories = categories;
                       this.cities = cities;

                       this.fillListing(listing);

                       this.photosLinks = listing.images;
                       this.isLoading = false;
                       this.isLoadedForUpdateMode = true;
                   })
                   break;
           }
       },
       updated()
       {
           if(this.mode == 'create' && this.listing.id){
               this.clearListing();
           }
       }
   }
</script>

<style lang="stylus" scoped>
    @import "../../../styles/_variables.styl"

    .create-listing-form
        width: 80%
        margin: 20px auto 0
        text-align: center
        &__btn-send
            margin: 0 auto
        .create-listing
            &__price
                width: 20%
            &__input-wrapper
                display: flex
                flex-direction: column
                font-size: 1.2rem
                margin: 1rem 0
                label
                    color: $secondary-color
                    height: 20px
                    line-height: 20px
                    font-size: 12px
                    text-align: left
                input
                    border-bottom: 1px solid $secondary-color
                    outline: none
                .has-error
                    border-bottom: 1px solid red
            &-photos-wrapper
                margin: 20px 0
                text-align: left
                &__title
                    color: $secondary-color
                    font-size: 1.2rem
            &-photos
                color: $secondary-color
                border: 2px solid $secondary-color
                display: flex
                flex-direction: column
                border-radius: 5px
                padding: 10px
                &__button
                    display: flex
                    flex-direction: column
                    text-align: center
                &__icon
                    font-size: 3rem
    #listing-photos-btn
        height: 0
    #listing-place-map
        margin: 20px 0
        width: 100%
        height: 300px
</style>
