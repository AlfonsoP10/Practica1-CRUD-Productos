<template>
  <div class="gestion-container">
    <header class="gestion-header">
      <div class="header-texto">
        <h1 class="titulo-pagina">Gestión de Productos</h1>
        <p class="subtitulo-pagina">
          Visualiza, edita o elimina los artículos del inventario. Para añadir stock o lanzar un nuevo artículo al catálogo, utiliza la acción rápida.
        </p>
      </div>
      
      <router-link to="/admin/nuevo-producto" class="btn-crear">
        <span class="icono-mas">+</span> Nuevo Producto
      </router-link>
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
/* Contenedor General Adaptable */
.gestion-container {
  max-width: 1200px;
  margin: 40px auto;
  padding: 0 20px;
  font-family: system-ui, -apple-system, sans-serif;
  color: #334155;
  box-sizing: border-box;
}

/* Encabezado con distribución Flexbox para alinear texto e hilo de acciones */
.gestion-header {
  display: flex;
  flex-direction: column;
  gap: 20px;
  margin-bottom: 32px;
  border-bottom: 1px solid #e2e8f0;
  padding-bottom: 24px;
}

@media (min-width: 640px) {
  .gestion-header {
    flex-direction: row;
    justify-content: space-between;
    align-items: flex-end; /* Alínea el botón con la base del texto */
  }
}

.header-texto {
  max-width: 750px;
}

.titulo-pagina {
  font-size: 28px;
  font-weight: 700;
  color: #0f172a;
  margin: 0 0 8px 0;
  letter-spacing: -0.02em;
}

.subtitulo-pagina {
  margin: 0;
  color: #64748b;
  font-size: 15px;
  line-height: 1.5;
}

/* Botón de Creación Estilizado */
.btn-crear {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background-color: #4f46e5; /* El color índigo característico de tu app */
  color: #ffffff;
  text-decoration: none;
  padding: 12px 20px;
  font-size: 14px;
  font-weight: 600;
  border-radius: 10px;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  transition: background-color 0.2s, transform 0.1s;
  flex-shrink: 0; /* Evita que el botón se comprima en pantallas medianas */
}

.btn-crear:hover {
  background-color: #4338ca;
}

.btn-crear:active {
  transform: scale(0.98);
}

.icono-mas {
  font-size: 16px;
  font-weight: 700;
}

/* Envoltura de la Lista: Esto le heredará bordes redondeados y una sombra */
/* sutil a cualquier tabla o grid que renderice el componente hijo <ProductosList /> */
.card-tabla-wrapper {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  padding: 24px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
  overflow-x: auto; /* Previene roturas si tu lista de productos es una tabla ancha */
}
</style>