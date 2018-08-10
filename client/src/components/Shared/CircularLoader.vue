<template>
    <div class="text-xs-center">
        <div class="circular-loader">
            <v-progress-circular
                    :size="200"
                    color="primary"
                    indeterminate
            ></v-progress-circular>
        </div>
    </div>
</template>

<script>
   export default {
        name: 'circular-progress'
   
   } 
</script>

<style lang="stylus">
</style>
