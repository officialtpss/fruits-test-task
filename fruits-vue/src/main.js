import { createApp, markRaw } from 'vue'
import App from './App.vue'
import { createPinia } from 'pinia'
import router from './router'
import Antd from 'ant-design-vue'
import 'ant-design-vue/dist/antd.css'
import axiosInstance from './services/api'

const app = createApp(App)

/* Pinia Store */
const pinia = createPinia()

pinia.use(({ store }) => {
  store.router = markRaw(router)
})
app.use(pinia)

/* Router */
app.use(router)

/* Ant design */
app.use(Antd)

app.config.globalProperties.axios = { ...axiosInstance }

app.provide('axios', axiosInstance)

/* App mount */
app.mount('#app')
