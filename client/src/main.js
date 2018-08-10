import Vue from 'vue'
import App from './App.vue'
import "material-design-icons-iconfont/dist/material-design-icons.css";
import "vuetify/dist/vuetify.css";
import Vuetify from "vuetify";
import router from "./router/index";
import store from "./store/index";
import ru from "vee-validate/dist/locale/ru";
import VeeValidate , {Validator} from "vee-validate";
import {beforeEach} from "./utils/routerGuards/authGuard";

Vue.config.productionTip = false

Vue.use(Vuetify, {
    theme: {
        primary: "#3A3A3E",
        secondary: "#7B7B7B",
        accent: "#ED2324"
    }
})

Validator.localize('ru', ru)
Vue.use(VeeValidate, {
    events: 'blur'
})

router.beforeEach(beforeEach);


new Vue({
  render: h => h(App),
  router,
  store
}).$mount('#app')
