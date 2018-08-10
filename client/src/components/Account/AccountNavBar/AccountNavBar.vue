<template>
    <div>
        <ul class="account-navbar">
            <router-link
                    v-for="link in links"
                    tag="li"
                    :to="{'name' : link.name}"
                    exact
                    :key="link.name"
            >
                <a>{{link.text}}</a>
            </router-link>
        </ul>
    </div>
</template>

<script>
   export default {
       name: 'account-nav-bar',
        data: () => ({
            links: [
                {
                    name: "account",
                    text: "Мои объявления",
                },
                {
                    name: "account.favorites",
                    text: "Избранное"
                },
                {
                    name: "account.settings",
                    text: "Настройки"
                },
                {
                    name: "account.change-password",
                    text: "Сменить пароль"
                }
            ]
        })
   
   } 
</script>

<style lang="stylus">
    @import "../../../styles/_variables.styl"
    .account-navbar
        display: flex
        justify-content: space-between
        text-align: center
        li
            background-color: white
            transition: font-weight .3s
            border-bottom: 2px solid white
            width: 25%
            a
                display: block
                color: black
                width: 100%
                padding: 20px
                font-size: 1.3rem
                &:visited
                    color: black
            &:hover
                background-color: #f5f5f5
                border-bottom: 2px solid #e0e0e0
        li.router-link-active
            font-weight: 900
            border-bottom: 2px solid black
</style>
