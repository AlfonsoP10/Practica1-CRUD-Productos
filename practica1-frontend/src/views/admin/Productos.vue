<template>
  <div>
<h1>Gestión de Productos</h1>
<p>Desde esta sección puedes editar o eliminar productos. Para crear uno nuevo usa la opción "Nuevo Producto".</p>
    <ProductosList />
  </div>
</template>

<script setup>
import ProductosList from '@/components/ProductosList.vue'
</script>