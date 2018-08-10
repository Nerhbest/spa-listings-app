<template>
            <v-layout row justify-center>
                <v-dialog :value="isLoading" persistent fullscreen content-class="loading-dialog">
                    <v-container fill-height>
                        <v-layout row justify-center align-center>
                            <v-progress-circular indeterminate :size="70" :width="7" color="primary"></v-progress-circular>
                        </v-layout>
                    </v-container>
                </v-dialog>
            </v-layout>
</template>

<script>
   export default {
        props: ['isLoading'],
        name: 'full-screen-loader'
   }
</script>

<style lang="stylus">
    .loading-dialog
        background-color: rgba(0,0,0,.3)
</style>
