<template>
  <div>
    <nav class="navbar">
      <router-link to="/">Inicio</router-link>
      <router-link to="/catalogo">Catálogo</router-link>
      <router-link to="/admin">Admin</router-link>
      <router-link to="/login">Login</router-link>
    </nav>

    <main class="contenido">
      <router-view />
    </main>
  </div>
</template>

<style scoped>
.navbar {
  background: #222;
  padding: 15px;
  display: flex;
  gap: 15px;
}

.navbar a {
  color: white;
  text-decoration: none;
  font-weight: bold;
}

.navbar a.router-link-active {
  color: #42b883;
}

.contenido {
  padding: 25px;
}
</style>