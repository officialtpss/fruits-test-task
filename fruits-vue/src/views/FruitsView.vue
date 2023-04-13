<template>
  <!-- Breadcrumb -->
  <a-breadcrumb :style="{ margin: '16px 0' }">
    <a-breadcrumb-item>Home</a-breadcrumb-item>
    <a-breadcrumb-item>Fruits</a-breadcrumb-item>
  </a-breadcrumb>

  <!-- Content -->
  <div :style="{ background: '#fff', padding: '24px', minHeight: '380px' }">
    <!-- Search Form -->
    <a-row justify="end" :style="{ padding: '25px 0' }">
      <a-form
        :model="formState"
        name="horizontal_login"
        layout="inline"
        autocomplete="off"
        @finish="findFruitList"
        @finishFailed="findFruitList"
      >
        <a-form-item label="Name" name="name">
          <a-input v-model:value="formState.name" />
        </a-form-item>

        <a-form-item label="Family" name="family">
          <a-input v-model:value="formState.family" />
        </a-form-item>

        <a-form-item>
          <a-button type="primary" html-type="submit">Search</a-button>
        </a-form-item>
      </a-form>
    </a-row>

    <!-- Listing -->
    <a-row :gutter="16" type="flex">
      <template v-for="fruit in fruits" :key="fruit.id">
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
            <template #actions>
              <HeartFilled
                key="fav-rm"
                title="Remove from Favourite"
                @click="handleFavouriteFruit(fruit, 'remove')"
                v-if="favouriteFruits.find((id) => id === fruit.id)"
              />
              <HeartOutlined
                key="fav-add"
                title="Add to Favourite"
                @click="handleFavouriteFruit(fruit, 'add')"
                v-else
              />
            </template>
          </a-card>
        </a-col>
      </template>
    </a-row>

    <!-- Pagination -->
    <a-row justify="center">
      <a-pagination
        v-model:current="page"
        :total="total"
        :defaultPageSize="limit"
        show-less-items
        :onChange="paginationCallback"
        v-show="total > 0"
      />
    </a-row>
  </div>
</template>
<script setup>
import { inject, ref, reactive } from 'vue'
import { useAlertStore } from '../stores/alert.store'
import { HeartOutlined, HeartFilled } from '@ant-design/icons-vue'

const axiosInstance = inject('axios')

const alertStore = useAlertStore()

const page = ref(1)
const total = ref(0)
const limit = ref(12)
const fruits = ref([])
const favouriteFruits = ref([])

const formState = reactive({
  name: '',
  family: ''
})

async function getFruitList(page = 1, itemsPerPage = 12) {
  await axiosInstance
    .post('fruits', {
      page: page,
      limit: itemsPerPage,
      name: formState.name,
      family: formState.family
    })
    .then((response) => {
      fruits.value = response.data.data.fruits
      favouriteFruits.value = response.data.data.favourites
      total.value = response.data.data.total
    })
    .catch((error) => {
      alertStore.error(
        error?.response?.data?.message ??
          error?.message ??
          'Something went wrong. Please try again later!'
      )
    })
}

getFruitList()

const paginationCallback = (page, itemsPerPage) => {
  getFruitList(page, itemsPerPage)
}

const findFruitList = () => {
  getFruitList(1, limit.value)
}

const handleFavouriteFruit = async (fruit, action = 'add') => {
  await axiosInstance
    .post('add-to-favorite', { fruitId: fruit.id })
    .then(() => {
      if (action === 'add') {
        alertStore.success(`${fruit.name} added to your favourite list.`)
      } else {
        alertStore.success(`${fruit.name} removed from your favourite list.`)
      }

      getFruitList(page.value, limit.value)
    })
    .catch((error) => {
      alertStore.error(
        error?.response?.data?.message ??
          error?.response?.data?.error ??
          error?.message ??
          'Something went wrong. Please try again later!'
      )
    })
}
</script>

<style>
.ant-card-meta-title {
  font-weight: 600;
  font-size: 20px;
}
</style>
