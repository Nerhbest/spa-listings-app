<template>
    <div class="create-listing-photos-list">
        <div    class="create-listing-photos-list__item-wrapper"
                v-for="photo in photos"
                :key="photo.id"
        >
            <img v-if="mode == 'update'" class="create-listing-photos-list__item"
                 :src="photo.url"
            />
            <img v-if="mode == 'create'" class="create-listing-photos-list__item"
                 :src="photo.src"
            />

            <div class="create-listing-photos-list__item-icon" @click="removePhoto(mode == 'create' ? photo.id : photo)">
                    <i class="material-icons">close</i>
            </div>
        </div>
    </div>
</template>

<script>
   export default {
        name: 'create-listing-photos',
        props: ["photos", "mode"],
        computed: {
        },
        methods: {
            removePhoto(param)
            {
                this.$emit('removePhoto', param);
            }
        }

   }
</script>

<style lang="stylus" scoped>
    .create-listing-photos-list
        margin: 15px 0
        display: flex
        justify-content: space-around
        &__item-wrapper
            width: 30%
            position: relative
        &__item
            width: 100%
            display: block
            height: 200px
        &__item-icon
            display: flex
            justify-content: center
            align-items: center
            position: absolute
            top: 10px
            right: 15px
            width: 10%
            padding: 3px
            border-radius: 50%
            background-color: #E0E0E0
            color: black
            transition: .5s background-color
            &:hover
                background-color: #9E9E9E
</style>
