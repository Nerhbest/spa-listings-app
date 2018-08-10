<template>
    <v-progress-linear :indeterminate="isLoading"></v-progress-linear>
</template>

<script>
   export default {
        name: 'loader',
        props: ["isLoading"]
   }
</script>

<style lang="stylus">

</style>
