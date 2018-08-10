<template>
        <form class="account-settings">
            <full-screen-loader :isLoading="isLoading"></full-screen-loader>
            <h3>Изменить Личные Данные</h3>
            <v-text-field
                    v-validate="'required|min:2|max:30'"
                    name="name"
                    v-model="userCreds.name"
                    :error-messages="errors.first('name')"
                    label="Username"
                    data-vv-name="name"
            >
            </v-text-field>
            <v-text-field
                    v-validate="'required|email'"
                    v-model="userCreds.email"
                    :error-messages="errors.first('email')"
                    label="Email"
                    data-vv-name="email"
            >
            </v-text-field>
            <v-text-field
                    v-validate="'digits:11'"
                    name="phone_number"
                    v-model="userCreds.phone_number"
                    :error-messages="errors.first('phone_number')"
                    label="Phone"
                    data-vv-name="phone_number"
                    hint="Приемлимый формат 79271432222"
            >

            </v-text-field>
            <v-btn  color="primary"
                    @click="updateUserCredentials"
                    :disabled="isLoading || !isFormValid"
            >
                Отправить
            </v-btn>
        </form>
</template>

<script>
    import {mapGetters, mapMutations} from "vuex";
    import {ACCOUNT_MODULE, accountMutations} from "../../../constants/accountConstants";
   import AccountService from "../../../services/AccountService";
    import {
        NOTIFICATION_MODULE,
        notificationActions,
        notificationTypes
    } from "../../../constants/notificationConstants";
    import FullScreenLoader from "../../Shared/FullScreenLoader";

   export default {
       components: {FullScreenLoader},
       name: 'account-settings',
        data(){
            return  {
                isLoading : false,
                userCreds: {
                    name: "",
                    email: "",
                    phone_number: ""
                }
            }
        }
        ,
        computed: {
            ...mapGetters({
               userInfo: `${ACCOUNT_MODULE}/userInfo`
            }),
            isFormValid()
            {
                return this.errors.items.length === 0;
            }
       },
       methods: {
       ...mapMutations({
           updateUserInfoInStore: `${ACCOUNT_MODULE}/${accountMutations.PUT_USER_INFO}`
       }),
            updateUserCredentials()
            {
                let userCreds = this.collectUserCredentials();
                AccountService.updateUserCredentials(userCreds).then(data => {
                    this.isLoading = false;
                    this.updateUserInfoInStore(userCreds);

                    this.$store.dispatch(`${NOTIFICATION_MODULE}/${notificationActions.DISPLAY_NOTIFICATION}`, {
                        type: `${NOTIFICATION_MODULE}/${notificationTypes.SUCCESS}`,
                        msg: data.msg
                    })
                }).catch(err => {
                    this.isLoading = false;
                    let serverErrors = err.response.data.errors;

                    for(let key in serverErrors){
                        this.$validator.errors.add(key,serverErrors[key]);
                    }
                })
            },
            collectUserCredentials()
            {
                return {
                  name: this.userCreds.name,
                  email: this.userCreds.email,
                  phone_number: this.userCreds.phone_number
                };
            }
       },
       mounted()
       {
            this.userCreds = {
                name: this.userInfo.name,
                email: this.userInfo.email,
                phone_number: this.userInfo.phone_number
            }
       }


   }
</script>

<style lang="stylus">
    .account-settings
        width: 70%
        margin: 15px auto 0
        text-align: center
    .some
        background-color: maroon
        color: white
</style>
