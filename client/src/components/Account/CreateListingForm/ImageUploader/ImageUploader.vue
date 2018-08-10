<template>
    <div class="create-listing-photos-wrapper">
        <div class="create-listing-photos-wrapper__title">Фотографии</div>
        <div class="create-listing-photos-wrapper__count">{{ photos.length }} из {{ maxPhotosCount }}</div>
        <div class="create-listing-photos">
            <label class="create-listing-photos__button"
                   for="listing-photos-btn"
                   v-show="needShowPhotosContainer"
            >
                <input type="file"
                       id="listing-photos-btn"
                       style="opacity: 0"
                       @change="uploadPhoto"
                >
                <v-icon class="create-listing-photos__icon">camera_alt</v-icon>
                <div class="create-listing-photos__title">Загрузите Фото</div>
            </label>
            <create-listing-photos   v-if="photos.length"
                                     :photos="photos"
                                     :mode="mode"
                                     @removePhoto="removePhoto"
            ></create-listing-photos>
        </div>
    </div>
</template>

<script>
    import CreateListingPhotos from "../CreateListingPhotos/CreateListingPhotos";
   export default {
       name: 'image-uploader',
       components: {CreateListingPhotos},
       props: ["needShowPhotosContainer", "photos", "maxPhotosCount", "mode"],
       methods: {
           uploadPhoto(e)
           {
              this.$emit('uploadPhoto', e);
           },
           removePhoto(param)
           {
                   this.$emit('removePhoto', param);
           }
       }

   }
</script>

<style lang="stylus" scoped>
  .create-listing
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
</style>
