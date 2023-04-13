<template>
  <a-form
    :model="formState"
    name="user_login"
    class="login-form"
    @finish="onFinish"
    @finishFailed="onFinishFailed"
  >
    <a-form-item
      label="Email"
      name="email"
      :rules="[
        { required: true, message: 'Please input your email address!' },
        { type: 'email', message: 'Please input valid email address!' }
      ]"
    >
      <a-input v-model:value="formState.email">
        <template #prefix>
          <MailOutlined class="site-form-item-icon" />
        </template>
      </a-input>
    </a-form-item>

    <a-form-item
      label="Password"
      name="password"
      :rules="[
        {
          required: true,
          message: 'Please input your password!'
        },
        {
          min: 6,
          message: 'Password must be minimum 6 characters!'
        },
        {
          max: 16,
          message: 'Password must be maximum 16 characters'
        }
      ]"
    >
      <a-input-password v-model:value="formState.password">
        <template #prefix>
          <LockOutlined class="site-form-item-icon" />
        </template>
      </a-input-password>
    </a-form-item>

    <a-form-item>
      <a-form-item name="remember" no-style>
        <a-checkbox v-model:checked="formState.remember">Remember me</a-checkbox>
      </a-form-item>
    </a-form-item>

    <a-form-item>
      <a-button :disabled="disabled" type="primary" html-type="submit" class="login-form-button">
        Log in
      </a-button>
    </a-form-item>
  </a-form>
</template>
<script setup>
import { computed, reactive } from 'vue'
import { MailOutlined, LockOutlined } from '@ant-design/icons-vue'
import { useAuthStore } from './../../stores/auth.store.js'
import { useAlertStore } from './../../stores/alert.store.js'

const auth = useAuthStore()
const alertStore = useAlertStore()

const formState = reactive({
  email: '',
  password: '',
  remember: true
})

const emit = defineEmits(['success'])

const disabled = computed(() => {
  return !(formState.email && formState.password)
})

const onFinish = async (values) => {
  const user = await auth.login(values.email, values.password)

  if (user) {
    emit('success', user)
  }
}

const onFinishFailed = (errorInfo) => {
  errorInfo?.errorFields.map((item) => {
    item?.errors.map((error) => {
      alertStore.error(error)
    })
  })

  return
}
</script>
