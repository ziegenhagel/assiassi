import Vue from 'vue'
import App from './App.vue'

window.APP_URL="http://127.0.0.1:8000/api/"

Vue.config.productionTip = false

new Vue({
  render: h => h(App),
}).$mount('#app')
