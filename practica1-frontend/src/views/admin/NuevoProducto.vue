<template>
  <div>
    <h1>Nuevo Producto</h1>

    <ProductoForm
      @productoGuardado="productoCreado"
      @productoCreado="productoCreado"
    />

    <p v-if="mensaje" style="color: green">
      {{ mensaje }}
    </p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import ProductoForm from '@/components/ProductoForm.vue'

const mensaje = ref('')

const productoCreado = () => {
  mensaje.value = 'Producto creado correctamente'
}
</script>