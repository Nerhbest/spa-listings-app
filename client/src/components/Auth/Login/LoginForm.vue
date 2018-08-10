<template>
    <form class="login-form">
        <div v-if="isLoading">
            <loader></loader>
        </div>
        <h3>Войдите</h3>
        <v-text-field
                v-validate="'required|email'"
                v-model="email"
                :error-messages="errors.first('email')"
                label="Email"
                data-vv-name="email"
        >
        </v-text-field>
        <v-text-field
                v-validate="'required|min:3|max:30'"
                v-model="password"
                :error-messages="errors.first('password')"
                label="Password"
                type="password"
                data-vv-name="password"
        >
        </v-text-field>
        <v-btn color="primary"
               @click.prevent="submit()"
               :disabled="isLoading"
        >
            Отправить
        </v-btn>
    </form>
</template>

<script>
    import AuthService from "../../../services/AuthService";
    import {authActions,AUTH_MODULE} from "../../../constants/authConstants";
    import {NOTIFICATION_MODULE,notificationActions, notificationTypes} from "../../../constants/notificationConstants";
    import Loader from "../../Shared/Loader";

    export default {
        components: {Loader},
        data: () => ({
            isLoading: false,
            email: "",
            password: ""
        }),
        methods:
        {
            submit()
            {
                let userDetails = this.makeUser();

                this.isLoading = true;
                AuthService.login(userDetails)
                .then(data => {
                    this.isLoading = false;
                    this.$store.dispatch(`${AUTH_MODULE}/${authActions.LOGIN_USER_INTO_APP}`, data);
                    this.$router.push({name: "account"})
                    this.$store.dispatch(`${NOTIFICATION_MODULE}/${notificationActions.DISPLAY_NOTIFICATION}`, {
                        msg: data.msg
                    })
                })
                .catch(err =>{
                    this.isLoading = false;
                    let serverErrors = err.response.data.errors;
                    for(let key in serverErrors){
                        this.$validator.errors.add(key,serverErrors[key]);
                    }
                })
            },
            makeUser()
            {
                return {
                    email: this.email,
                    password: this.password
                }
            }
        },
        mounted()
        {

        }

    }
</script>

<style lang="stylus">
    .login-form
        width: 70%
        margin: 20px auto
        text-align: center
        margin-top: 50px
</style>
