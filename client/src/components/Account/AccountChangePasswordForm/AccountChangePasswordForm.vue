<template>
    <form class="change-password-form">
        <h3>Изменить пароль</h3>
            <v-text-field
                    v-validate="'required'"
                    v-model="old_password"
                    :error-messages="errors.first('old_password')"
                    label="Старый Пароль"
                    type="password"
                    data-vv-name="old_password"
            >

            </v-text-field>
            <v-text-field
                    v-validate="{ required: true, min: 6, max: 30, is: new_password_confirmation }"
                    v-model="new_password"
                    :error-messages="errors.first('new_password')"
                    label="Новый Пароль"
                    type="password"
                    data-vv-name="new_password"
            ></v-text-field>

            <v-text-field
                    v-model="new_password_confirmation"
                    v-validate="'required'"
                    label="Подтвердите Новый Пароль"
                    :errors-messages="errors.first('new_password_confirmation')"
                    type="password"
                    data-vv-name="new_password_confirmation"
            ></v-text-field>
            <v-btn color="primary"
                   @click.prevent="updatePassword"
            >Обновить</v-btn>
    </form>
</template>

<script>
   import {NOTIFICATION_MODULE,notificationActions} from "../../../constants/notificationConstants";
   import  AccountService from "../../../services/AccountService";
   export default {
        name: 'change-password-form',
        data: () => ({
            isLoading: false,
            old_password: '',
            new_password: '',
            new_password_confirmation: ''
        }),
       methods: {
           updatePassword()
           {
               this.isLoading = true;
               this.$validator.validate().then(valid => {
                   if(valid){
                       AccountService.updateUserPassword(this.givePasswords())
                                     .then(data => {
                                         this.isLoading = false;
                                         this.$router.push({
                                             name: 'account'
                                         })
                                         this.$store.dispatch(`${NOTIFICATION_MODULE}/${notificationActions.DISPLAY_NOTIFICATION}`, {
                                             msg: data.msg
                                         })
                                     }).catch(err => {
                                         this.isLoading = false;
                                         let serverErrors = err.response.data.errors;
                                            for(let key in serverErrors){
                                            this.$validator.errors.add(key,serverErrors[key]);
                           }
                       })
                   }
               })
           },
           givePasswords()
           {
               return {
                   old_password: this.old_password,
                   new_password: this.new_password,
                   new_password_confirmation: this.new_password_confirmation
               }
           }


       },
        $_veeValidate:
       {
           events: ""
       },

   }
</script>

<style lang="stylus">
    .change-password-form
        width: 70%
        margin: 15px auto 0
        text-align: center
</style>
