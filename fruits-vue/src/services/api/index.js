import axios from 'axios'
import router from "../../router";
import {useAuthStore} from "../../stores/auth.store";

const axiosInstance = axios.create({
  baseURL: import.meta.env.VITE_APP_API_URL,
  withCredentials: false
})

// Add a request interceptor
axiosInstance.interceptors.request.use(
  function (config) {
    const token = localStorage.getItem('token')

    if (token) {
      config.headers['Authorization'] = `Bearer ${token}`
    }

    config.headers['Accept'] = 'application/json'
    config.headers['Content-Type'] = 'application/json'

    // Do something before request is sent
    return config
  },

  function (error) {
    // Do something with request error
    return Promise.reject(error)
  }
)

// Add a response interceptor
axiosInstance.interceptors.response.use(
  function (response) {
    // Any status code that lie within the range of 2xx cause this function to trigger
    // Do something with response data

    return response
  },
  function (error) {
    if (error.response.status === 401) {
        const authStore = useAuthStore();
        authStore.logout()
    }
    // Any status codes that falls outside the range of 2xx cause this function to trigger
    // Do something with response error
    return Promise.reject(error)
  }
)

export default axiosInstance
