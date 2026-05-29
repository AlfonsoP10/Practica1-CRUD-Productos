<template>
  <div>
    <h1>Login</h1>

    <form @submit.prevent="iniciarSesion">
      <div>
        <label>Email</label>
        <input v-model="email" type="email" />
      </div>

      <div>
        <label>Contraseña</label>
        <input v-model="password" type="password" />
      </div>

      <button type="submit">Ingresar</button>
    </form>

    <p v-if="error" style="color:red">
      {{ error }}
    </p>

    <hr>

    <p>
      Usuario: admin@test.com
    </p>

    <p>
      Contraseña: 123456
    </p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const route = useRoute()
const auth = useAuthStore()

const email = ref('')
const password = ref('')
const error = ref('')

const iniciarSesion = async () => {
  try {
    await auth.login(email.value, password.value)

    const redirect = route.query.redirect || '/admin'

    router.push(redirect)
  } catch {
    error.value = 'Credenciales incorrectas'
  }
}
</script>