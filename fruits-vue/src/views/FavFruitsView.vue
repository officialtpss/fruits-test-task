<template>
  <!-- Breadcrumb -->
  <a-breadcrumb :style="{ margin: '16px 0' }">
    <a-breadcrumb-item>Home</a-breadcrumb-item>
    <a-breadcrumb-item>Favourite Fruits</a-breadcrumb-item>
  </a-breadcrumb>

  <!-- Content -->
  <div :style="{ background: '#fff', padding: '24px', minHeight: '380px' }">
    <!-- Listing -->
    <a-row :gutter="16" type="flex">
      <template v-for="fruit in favFruits" :key="fruit.id">
        <a-col class="gutter-row" :span="6" style="margin-bottom: 15px">
          <a-card hoverable>
            <a-card-meta :title="fruit.name" :description="`${fruit.family} (${fruit.genus})`" />
            <template #cover>
              <div class="ant-card-body" style="padding-bottom: 0">
                <p><b>Protein</b>: {{ fruit.protein }}</p>
                <p><b>Carbohydrates</b>: {{ fruit.carbohydrates }}</p>
                <p><b>Sugar</b>: {{ fruit.sugar }}</p>
                <p><b>Fat</b>: {{ fruit.fat }}</p>
                <p><b>Calories</b>: {{ fruit.calories }}</p>
              </div>
            </template>
          </a-card>
        </a-col>
      </template>
    </a-row>
  </div>
</template>
<script setup>
import { inject, ref } from 'vue'
import { useAlertStore } from '../stores/alert.store'

const axiosInstance = inject('axios')
const alertStore = useAlertStore()

const favFruits = ref([])

async function getFavFruitList() {
  await axiosInstance
    .get('favorite-fruits')
    .then((response) => {
      favFruits.value = response.data.data.favouriteFruits
    })
    .catch((error) => {
      alertStore.error(
        error?.response?.data?.message ??
          error?.message ??
          'Something went wrong. Please try again later!'
      )
    })
}

getFavFruitList()
</script>

<style>
.ant-card-meta-title {
  font-weight: 600;
  font-size: 20px;
}
</style>
