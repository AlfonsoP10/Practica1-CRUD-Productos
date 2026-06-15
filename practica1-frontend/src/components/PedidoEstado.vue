<template>
  <div class="estado-pedido">
    <div v-if="!emailListo" class="estado procesando">
      ⏳ Procesando tu pedido...
    </div>

    <div v-else class="estado listo">
      ✅ ¡Pedido confirmado! Revisa tu correo.
    </div>
  </div>
</template>

<script setup>
import axios from 'axios'
import { onMounted, onUnmounted, ref } from 'vue'

const props = defineProps({
  pedidoId: {
    type: Number,
    required: true,
  },
})

const emailListo = ref(false)
let intervalo = null

const consultarPedido = async () => {
  try {
    const token = localStorage.getItem('token')

    const { data } = await axios.get(
      `http://127.0.0.1:8000/api/pedidos/${props.pedidoId}`,
      {
        headers: {
          Authorization: `Bearer ${token}`,
          Accept: 'application/json',
        },
      }
    )

    emailListo.value = !!data.email_enviado_at

    if (emailListo.value && intervalo) {
      clearInterval(intervalo)
    }
  } catch (error) {
    console.error('Error al consultar pedido:', error)
  }
}

onMounted(() => {
  consultarPedido()

  intervalo = setInterval(() => {
    consultarPedido()
  }, 3000)
})

onUnmounted(() => {
  if (intervalo) {
    clearInterval(intervalo)
  }
})
</script>

<style scoped>
.estado-pedido {
  margin: 20px 0;
}

.estado {
  padding: 14px;
  border-radius: 8px;
  font-weight: bold;
}

.procesando {
  background: #fff7ed;
  color: #c2410c;
}

.listo {
  background: #ecfdf5;
  color: #047857;
}
</style>