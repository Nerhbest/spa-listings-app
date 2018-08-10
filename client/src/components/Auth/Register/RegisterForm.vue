<template>
    <form class="register-form">
        <h3>Зарегистрируйтесь</h3>
        <div v-if="isLoading">
            <v-progress-linear :indeterminate="true"></v-progress-linear>
        </div>
        <v-text-field
                v-validate="'required|min:3|max:30'"
                v-model="name"
                :error-messages="errors.first('name')"
                label="Username"
                data-vv-name="name"

        >
        </v-text-field>
        <v-text-field
                v-validate="'required|email'"
                v-model="email"
                :error-messages="errors.first('email')"
                label="Email"
                data-vv-name="email"
        >
        </v-text-field>
        <v-text-field
                v-validate="{ required: true, min: 6, max: 30, is: password_confirmation }"
                v-model="password"
                :error-messages="errors.first('password')"
                label="Password"
                type="password"
                data-vv-name="password"
        >
        </v-text-field>
        <v-text-field
                v-model="password_confirmation"
                v-validate="'required'"
                label="Confirm Password"
                :errors-messages="errors.first('password_confirmation')"
                type="password"
                data-vv-name="password_confirmation"
        >
        </v-text-field>
        <v-text-field
                v-model="phone_number"
                v-validate="'required|min:5|max:30'"
                :error-messages="errors.first('phone_number')"
                label="Phone"
                data-vv-name="phone_number"
                hint="Приемлимый формат 79271432222"
        >

        </v-text-field>
        <v-btn  color="primary"
                @click="submit()"
                :disabled="isLoading"
        >
            Отправить
        </v-btn>
    </form>
</template>

<script>
    import AuthService from "../../../services/AuthService";
    import {mapActions} from "vuex";
    import {NOTIFICATION_MODULE,notificationActions,notificationTypes} from "../../../constants/notificationConstants";

    export default {
        data: () => ({
            isLoading: false,
            name: "",
            email: "",
            password: "",
            password_confirmation:"",
            phone_number: ""

        }),
        methods: {
            ...mapActions({
                putNotification: `${NOTIFICATION_MODULE}/${notificationActions.DISPLAY_NOTIFICATION}`
            }),
            submit(){
                let user = this.makeUser();
                this.isLoading = true;
                this.$validator.errors.clear();

                AuthService.register(user)
                .then(resp => {
                    this.isLoading = false;
                    this.$router.push({name: 'login'});
                    this.putNotification({
                        type: notificationTypes.SUCCESS,
                        msg: resp.msg
                    });

                })
                .catch(err => {
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
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                    phone_number: this.phone_number
                }
            },
        },
        mounted()
        {
        }

    }
</script>

<style lang="stylus">
    .register-form
        width: 70%
        margin: 20px auto
        text-align: center
        margin-top: 50px
</style>
