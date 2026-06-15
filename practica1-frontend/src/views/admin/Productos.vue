<template>
  <div class="gestion-container">
    <header class="gestion-header">
      <div class="header-texto">
        <h1 class="titulo-pagina">Gestión de Productos</h1>
        <p class="subtitulo-pagina">
          Visualiza, edita o elimina los artículos del inventario.
        </p>
      </div>
    </header>

    <div class="card-tabla-wrapper">
      <ProductosList />
    </div>
  </div>
</template>

<script setup>
import ProductosList from '@/components/ProductosList.vue'
</script>

<style scoped>
.gestion-container {
  max-width: 1200px;
  margin: 40px auto;
  padding: 0 20px;
  font-family: system-ui, -apple-system, sans-serif;
  color: #334155;
  box-sizing: border-box;
}

.gestion-header {
  margin-bottom: 32px;
  border-bottom: 1px solid #e2e8f0;
  padding-bottom: 24px;
}

.header-texto {
  max-width: 750px;
}

.titulo-pagina {
  font-size: 28px;
  font-weight: 700;
  color: #0f172a;
  margin: 0 0 8px 0;
}

.subtitulo-pagina {
  margin: 0;
  color: #64748b;
  font-size: 15px;
  line-height: 1.5;
}

.card-tabla-wrapper {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  padding: 24px;
  overflow-x: auto;
}
</style>