<template>
  <div class="not-found">
    <h1>404</h1>
    <h2>Página no encontrada</h2>

    <p>La ruta que intentaste visitar no existe.</p>

    <router-link to="/">
      Volver al inicio
    </router-link>
  </div>
</template>

<style scoped>
.not-found {
  text-align: center;
  padding: 40px;
}

.not-found h1 {
  font-size: 80px;
  margin: 0;
  color: #222;
}

.not-found a {
  color: #42b883;
  font-weight: bold;
}
</style>