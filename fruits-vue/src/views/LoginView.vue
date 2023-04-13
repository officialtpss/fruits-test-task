<template>
  <!-- Breadcrumb -->
  <a-breadcrumb :style="{ margin: '16px 0' }">
    <a-breadcrumb-item>Home</a-breadcrumb-item>
    <a-breadcrumb-item>Login</a-breadcrumb-item>
  </a-breadcrumb>

  <!-- Content -->
  <div :style="{ background: '#fff', padding: '24px', minHeight: '380px' }">
    <a-row :gutter="[16, 16]" :style="{ padding: '0 50px', marginTop: '64px' }">
      <a-col :span="8" />
      <a-col :span="8">
        <h1 style="text-align: center; padding-bottom: 15px; font-size: 32px">Login</h1>
        <Login />
      </a-col>
      <a-col :span="8" />
    </a-row>
  </div>
</template>
<script setup>
import Login from '@/components/auth/LoginForm.vue'
</script>
