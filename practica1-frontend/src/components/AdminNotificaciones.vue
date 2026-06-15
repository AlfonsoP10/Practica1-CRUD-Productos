<template>
  <div class="notificaciones-admin">
    <p v-if="error" class="error-ws">
      {{ error }}
    </p>

    <TransitionGroup name="toast" tag="div" class="toast-container">
      <div
        v-for="pedido in pedidosNuevos"
        :key="pedido.id"
        class="toast"
      >
        🛒 Nuevo pedido #{{ pedido.id }} de {{ pedido.cliente }} — ${{ Number(pedido.total).toFixed(2) }}
      </div>
    </TransitionGroup>

    <div
      v-for="alerta in alertasStock"
      :key="alerta.producto_id"
      class="alerta-stock"
    >
      ⚠️ Stock bajo: {{ alerta.nombre }} ({{ alerta.stock_actual }} unidades)
    </div>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue'
import echo from '@/plugins/echo'

const pedidosNuevos = ref([])
const alertasStock = ref([])
const error = ref('')

let canal = null

onMounted(() => {
  console.log('ADMIN NOTIFICACIONES MONTADO')

  try {
    const token = localStorage.getItem('token')

    if (!token) {
      console.warn('No hay token en localStorage')
      error.value = 'Sin token de admin para Reverb'
      return
    }

    console.log('Token encontrado para Reverb')

    canal = echo.private('admin-panel')

    console.log('Conectado al canal admin-panel')

    canal.listen('.NuevoPedidoRecibido', (e) => {
      console.log('Nuevo pedido recibido:', e)

      pedidosNuevos.value.unshift(e)

      setTimeout(() => {
        pedidosNuevos.value = pedidosNuevos.value.filter(
          (pedido) => pedido.id !== e.id
        )
      }, 10000)
    })

    canal.listen('.StockBajoAlerta', (e) => {
      console.log('Stock bajo recibido:', e)

      alertasStock.value.unshift(e)
    })
  } catch (e) {
    console.error('Error al conectar con Reverb:', e)
    error.value = 'No se pudo conectar a Reverb'
  }
})

onUnmounted(() => {
  try {
    echo.leave('admin-panel')
    console.log('Saliendo del canal admin-panel')
  } catch (e) {
    console.error('Error al salir del canal:', e)
  }
})
</script>

<style scoped>
.notificaciones-admin {
  position: fixed;
  top: 80px;
  right: 20px;
  z-index: 9999;
  width: 320px;
}

.error-ws {
  background: #fef2f2;
  color: #b91c1c;
  padding: 10px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
}

.toast-container {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.toast {
  background: #111827;
  color: white;
  padding: 14px;
  border-radius: 10px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.alerta-stock {
  margin-top: 10px;
  background: #fef3c7;
  color: #92400e;
  padding: 14px;
  border-radius: 10px;
  border: 1px solid #fbbf24;
  font-weight: 600;
}

.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from,
.toast-leave-to {
  opacity: 0;
  transform: translateX(30px);
}
</style>