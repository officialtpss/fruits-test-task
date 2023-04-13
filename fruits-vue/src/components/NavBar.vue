<template>
  <div>
    <div class="logo" />
    <a-menu
      theme="dark"
      mode="horizontal"
      :default-selected-keys="['2']"
      :style="{ lineHeight: '64px' }"
      v-model="current"
    >
      <a-menu-item :class="{ 'ant-menu-item-selected': current === 'home' }">
        <router-link :to="{ name: 'home' }">Home</router-link>
      </a-menu-item>
      <a-menu-item :class="{ 'ant-menu-item-selected': current === 'fruits' }" v-if="isLoggedIn">
        <router-link :to="{ name: 'fruits' }">Fruits</router-link>
      </a-menu-item>
      <a-menu-item
        :class="{ 'ant-menu-item-selected': current === 'favorite-fruits' }"
        v-if="isLoggedIn"
      >
        <router-link :to="{ name: 'favorite-fruits' }">Favorite Fruits</router-link>
      </a-menu-item>
      <a-menu-item v-if="isLoggedIn"
        ><a href="#" v-on:click.prevent="logout">Logout</a></a-menu-item
      >
      <a-menu-item :class="{ 'ant-menu-item-selected': current === 'login' }" v-else
        ><router-link :to="{ name: 'login' }">Login</router-link></a-menu-item
      >
    </a-menu>
  </div>
</template>
<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth.store.js'

const route = useRoute()
const auth = useAuthStore()

const current = computed(() => {
  return route.name ?? null
})

const isLoggedIn = computed(() => {
  return useAuthStore().isLoggedIn
})

const logout = () => {
  auth.logout()
}
</script>
