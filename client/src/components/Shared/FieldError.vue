<template>
    <div class="v-messages error--text">
        <div class="v-messages__wrapper">
            <div class="v-messages__message">
                {{ errorMsg }}
            </div>
        </div>
    </div>
</template>

<script>
   export default {
     name: 'field-error',
     props: ['errorMsg']
   
   } 
</script>

<style lang="stylus" scoped>
    .v-messages__message
        margin-top: 5px
        text-align: left
</style>
