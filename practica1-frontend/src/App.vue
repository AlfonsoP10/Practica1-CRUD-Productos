<template>
  <main class="contenedor">
    <ProductosList />
  </main>
</template>

<script setup>
import ProductosList from './components/ProductosList.vue'
</script>

<style>
body {
  margin: 0;
  font-family: Arial, sans-serif;
  background: #f4f6f8;
  color: #222;
}

.contenedor {
  width: 90%;
  max-width: 1000px;
  margin: 30px auto;
  background: white;
  padding: 25px;
  border-radius: 10px;
}
</style>