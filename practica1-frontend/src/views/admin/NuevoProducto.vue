<template>
  <div class="formulario-container">
    <div class="header-formulario">
      <router-link to="/catalogo" class="btn-regresar">
        &larr; Volver al catálogo
      </router-link>
      <h1 class="titulo-pagina">Añadir Nuevo Producto</h1>
      <p class="subtitulo-pagina">Registra un nuevo artículo en el inventario completando los campos obligatorios.</p>
    </div>

    <transition name="fade">
      <div v-if="mensaje" class="banner-exito">
        <span class="exito-icono">✨</span>
        <div class="exito-contenido">
          <p>{{ mensaje }}</p>
        </div>
        <button @click="mensaje = ''" class="btn-cerrar-alerta" title="Cerrar aviso">&times;</button>
      </div>
    </transition>

    <div class="card-formulario">
      <ProductoForm
        @productoGuardado="productoCreado"
        @productoCreado="productoCreado"
      />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import ProductoForm from '@/components/ProductoForm.vue'

const mensaje = ref('')

const productoCreado = () => {
  mensaje.value = 'El producto ha sido creado y guardado correctamente en el inventario.'
  
  // Limpia el mensaje automáticamente después de 5 segundos
  setTimeout(() => {
    mensaje.value = ''
  }, 5000)
}
</script>

<style scoped>
/* Contenedor Base Central */
.formulario-container {
  max-width: 700px; /* Ancho ideal para formularios cómodos */
  margin: 40px auto;
  padding: 0 20px;
  font-family: system-ui, -apple-system, sans-serif;
  color: #333;
  box-sizing: border-box;
}

/* Encabezado */
.header-formulario {
  margin-bottom: 32px;
}

.btn-regresar {
  display: inline-block;
  text-decoration: none;
  color: #6b7280;
  font-size: 14px;
  font-weight: 600;
  margin-bottom: 12px;
  transition: color 0.2s;
}

.btn-regresar:hover {
  color: #4f46e5;
}

.titulo-pagina {
  font-size: 28px;
  font-weight: 700;
  color: #111827;
  margin: 0 0 6px 0;
}

.subtitulo-pagina {
  margin: 0;
  color: #6b7280;
  font-size: 15px;
  line-height: 1.5;
}

/* Tarjeta Blanca que envuelve al componente <ProductoForm> */
.card-formulario {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 16px;
  padding: 32px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
}

/* Banner de Alerta / Éxito */
.banner-exito {
  display: flex;
  align-items: center;
  gap: 12px;
  background-color: #ecfdf5; /* Verde esmeralda suave */
  border: 1px solid #a7f3d0;
  padding: 16px;
  border-radius: 12px;
  margin-bottom: 24px;
  position: relative;
}

.exito-icono {
  font-size: 18px;
}

.exito-contenido p {
  margin: 0;
  font-size: 14.5px;
  color: #065f46;
  font-weight: 600;
}

.btn-cerrar-alerta {
  position: absolute;
  right: 16px;
  top: 50%;
  transform: translateY(-50%);
  background: transparent;
  border: none;
  color: #059669;
  font-size: 20px;
  cursor: pointer;
  padding: 4px;
  line-height: 1;
}

.btn-cerrar-alerta:hover {
  color: #047857;
}

/* Animación sutil para la aparición de la alerta (Vue Transition) */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}
</style>