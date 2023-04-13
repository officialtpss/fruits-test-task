<template>
  <a-layout id="components-layout-demo-fixed">
    <!-- Navbar -->
    <a-layout-header :style="{ position: 'fixed', zIndex: 1, width: '100%' }">
      <NavBar />
    </a-layout-header>

    <!-- Content -->
    <a-layout-content :style="{ padding: '0 50px', marginTop: '64px' }">
      <router-view />
    </a-layout-content>

    <!-- Footer -->
    <a-layout-footer :style="{ textAlign: 'center' }">
      ©{{ new Date().getFullYear() }} Created by TPSS
    </a-layout-footer>
  </a-layout>
</template>
<script setup>
import NavBar from './components/NavBar.vue'
</script>
