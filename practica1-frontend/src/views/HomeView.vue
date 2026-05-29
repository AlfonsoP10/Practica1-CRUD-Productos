<template>
  <div class="home-container">
    <section class="hero-banner">
      <div class="hero-contenido">
        <h1>Bienvenido a nuestra tienda online</h1>
        <p>Descubre productos excepcionales con la mejor calidad y soporte garantizado.</p>
        <router-link to="/catalogo" class="btn-cta">
          Explorar catálogo &rarr;
        </router-link>
      </div>
    </section>

    <section class="destacados-seccion">
      <div class="destacados-encabezado">
        <h2>Últimas novedades</h2>
        <p class="subtitulo">Los productos más recientes añadidos a nuestra colección.</p>
      </div>

      <div v-if="productos.length === 0" class="skeleton-grid">
        <div v-for="n in 3" :key="n" class="skeleton-card"></div>
      </div>

      <div v-else class="grid-destacados">
        <div
          v-for="producto in ultimosProductos"
          :key="producto.id"
          class="producto-card"
        >
          <div class="imagen-wrapper">
            <img
              v-if="producto.imagen_url"
              :src="producto.imagen_url"
              :alt="producto.nombre"
              class="producto-imagen"
            >
            <div v-else class="sin-imagen">
              <span>Sin imagen</span>
            </div>
          </div>

          <div class="card-cuerpo">
            <h3 class="producto-titulo" :title="producto.nombre">
              {{ producto.nombre }}
            </h3>
            
            <div class="card-footer">
              <span class="precio">${{ Number(producto.precio).toFixed(2) }}</span>
              <router-link :to="`/catalogo/${producto.id}`" class="btn-enlace">
                Ver detalle
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { getProductos } from '@/services/productoService'

const productos = ref([])

const cargarProductos = async () => {
  try {
    const respuesta = await getProductos()
    productos.value = respuesta.data
  } catch (error) {
    console.error('Error al recuperar destacados:', error)
  }
}

// Mantiene los últimos 3 productos agregados
const ultimosProductos = computed(() => productos.value.slice(-3).reverse())

onMounted(() => {
  cargarProductos()
})
</script>

<style scoped>
/* Contenedor Base */
.home-container {
  max-width: 1200px;
  margin: 40px auto;
  padding: 0 20px;
  font-family: system-ui, -apple-system, sans-serif;
  color: #333;
}

/* Hero Banner con degradado sofisticado */
.hero-banner {
  background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
  color: white;
  padding: 60px 40px;
  border-radius: 20px;
  margin-bottom: 48px;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
  position: relative;
  overflow: hidden;
}

.hero-contenido {
  max-width: 600px;
  position: relative;
  z-index: 2;
}

.hero-banner h1 {
  font-size: 36px;
  font-weight: 800;
  margin: 0 0 16px 0;
  letter-spacing: -0.025em;
  line-height: 1.2;
}

.hero-banner p {
  font-size: 18px;
  color: #9ca3af;
  margin: 0 0 28px 0;
  line-height: 1.5;
}

/* Botón CTA (Call to Action) */
.btn-cta {
  display: inline-block;
  background: #4f46e5;
  color: white;
  text-decoration: none;
  padding: 12px 24px;
  border-radius: 10px;
  font-weight: 600;
  font-size: 15px;
  transition: background 0.2s, transform 0.2s;
}

.btn-cta:hover {
  background: #4338ca;
  transform: translateY(-1px);
}

/* Sección Destacados */
.destacados-seccion {
  margin-bottom: 40px;
}

.destacados-encabezado {
  margin-bottom: 24px;
}

.destacados-encabezado h2 {
  font-size: 24px;
  font-weight: 700;
  margin: 0 0 6px 0;
  color: #111827;
}

.subtitulo {
  margin: 0;
  color: #6b7280;
  font-size: 15px;
}

/* Grid de Tarjetas */
.grid-destacados {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 24px;
}

@media (min-width: 1024px) {
  .grid-destacados {
    grid-template-columns: repeat(3, 1fr); /* Fuerza 3 columnas en pantallas grandes */
  }
}

/* Tarjeta del Producto */
.producto-card {
  background: #ffffff;
  border: 1px solid #f3f4f6;
  border-radius: 16px;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
  transition: transform 0.2s, box-shadow 0.2s;
}

.producto-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
}

/* Contenedor de Imagen */
.imagen-wrapper {
  width: 100%;
  height: 200px;
  background-color: #f3f4f6;
}

.producto-imagen {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.sin-imagen {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #9ca3af;
  font-size: 14px;
  font-weight: 500;
}

/* Información de Tarjeta */
.card-cuerpo {
  padding: 16px;
  display: flex;
  flex-direction: column;
  gap: 12px;
  flex-grow: 1;
}

.producto-titulo {
  font-size: 16px;
  font-weight: 600;
  margin: 0;
  color: #1f2937;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: auto;
}

.precio {
  font-size: 18px;
  font-weight: 700;
  color: #111827;
}

.btn-enlace {
  font-size: 14px;
  font-weight: 600;
  color: #4f46e5;
  text-decoration: none;
  transition: color 0.2s;
}

.btn-enlace:hover {
  color: #4338ca;
}

/* Efecto de carga previo (Skeletons) */
.skeleton-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 24px;
}

.skeleton-card {
  height: 280px;
  background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
  background-size: 200% 100%;
  animation: loading 1.5s infinite;
  border-radius: 16px;
}

@keyframes loading {
  0% { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}
</style>