import { defineStore } from 'pinia'
import { message } from 'ant-design-vue'

message.config({
  top: '100px'
})

export const useAlertStore = defineStore({
  id: 'alert',
  state: () => ({
    alert: null
  }),
  actions: {
    success(msg, key = null) {
      message.success({ content: msg, key })
    },
    error(msg) {
      message.error(msg)
    },
    loading(msg, key) {
      message.loading({ content: msg, key })
    }
  }
})
