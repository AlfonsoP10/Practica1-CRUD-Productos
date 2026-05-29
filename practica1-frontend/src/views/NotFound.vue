<template>
  <div class="error-container">
    <div class="error-contenido">
      <h1 class="codigo-error">404</h1>
      
      <h2 class="titulo-error">Página no encontrada</h2>
      <p class="descripcion-error">
        Lo sentimos, la ruta o el recurso que estás intentando visitar no existe o ha sido movido de lugar.
      </p>

      <div class="acciones-error">
        <router-link to="/" class="btn-regresar">
          &larr; Volver al inicio
        </router-link>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Contenedor que centra todo vertical y horizontalmente */
.error-container {
  min-height: 75vh;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: system-ui, -apple-system, sans-serif;
  padding: 20px;
  box-sizing: border-box;
}

/* Tarjeta o bloque de contenido */
.error-contenido {
  text-align: center;
  max-width: 500px;
  padding: 20px;
}

/* El número 404 estilizado y grande */
.codigo-error {
  font-size: 120px;
  font-weight: 900;
  line-height: 1;
  margin: 0 0 16px 0;
  background: linear-gradient(135deg, #4f46e5 0%, #312e81 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  letter-spacing: -0.05em;
}

/* Título del error */
.titulo-error {
  font-size: 24px;
  font-weight: 700;
  color: #111827;
  margin: 0 0 12px 0;
}

/* Texto de descripción */
.descripcion-error {
  font-size: 15px;
  color: #6b7280;
  line-height: 1.6;
  margin: 0 0 32px 0;
}

/* Enlace transformado en botón interactivo */
.btn-regresar {
  display: inline-block;
  background: #4f46e5;
  color: white;
  text-decoration: none;
  padding: 12px 28px;
  font-size: 15px;
  font-weight: 600;
  border-radius: 10px;
  box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
  transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
}

.btn-regresar:hover {
  background: #4338ca;
  box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
  transform: translateY(-2px);
}

.btn-regresar:active {
  transform: translateY(0);
}
</style>