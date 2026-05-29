<template>
  <div class="login-wrapper">
    <div class="login-card">
      
      <div class="login-header">
        <h1 class="login-titulo">Iniciar Sesión</h1>
        <p class="login-subtitulo">Ingresa tus credenciales para acceder al sistema</p>
      </div>

      <form @submit.prevent="iniciarSesion" class="login-form">
        <div class="form-group">
          <label for="email">Correo electrónico</label>
          <input 
            id="email"
            v-model="email" 
            type="email" 
            placeholder="ejemplo@correo.com"
            required
          />
        </div>

        <div class="form-group">
          <label for="password">Contraseña</label>
          <input 
            id="password"
            v-model="password" 
            type="password" 
            placeholder="••••••••"
            required
          />
        </div>

        <div v-if="error" class="mensaje-error">
          <span class="error-icono">⚠️</span>
          <p>{{ error }}</p>
        </div>

        <button type="submit" class="btn-ingresar">
          Ingresar al sistema
        </button>
      </form>

      <div class="credenciales-demo">
        <div class="demo-titulo">
          <span class="demo-icono">💡</span>
          <strong>Acceso de prueba rápido</strong>
        </div>
        <div class="demo-detalles">
          <p><strong>Usuario:</strong> <code>admin@test.com</code></p>
          <p><strong>Contraseña:</strong> <code>123456</code></p>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const route = useRoute()
const auth = useAuthStore()

const email = ref('')
const password = ref('')
const error = ref('')

const iniciarSesion = async () => {
  try {
    error.value = '' // Limpia errores previos al intentar de nuevo
    await auth.login(email.value, password.value)

    const redirect = route.query.redirect || '/admin'
    router.push(redirect)
  } catch {
    error.value = 'Las credenciales ingresadas son incorrectas.'
  }
}
</script>

<style scoped>
/* Contenedor principal que centra la tarjeta vertical y horizontalmente */
.login-wrapper {
  min-height: 80vh;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: system-ui, -apple-system, sans-serif;
  padding: 20px;
}

/* Tarjeta contenedora */
.login-card {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 20px;
  width: 100%;
  max-width: 420px;
  padding: 40px 32px;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
}

/* Encabezado */
.login-header {
  text-align: center;
  margin-bottom: 32px;
}

.login-titulo {
  font-size: 26px;
  font-weight: 700;
  color: #111827;
  margin: 0 0 8px 0;
}

.login-subtitulo {
  font-size: 14px;
  color: #6b7280;
  margin: 0;
}

/* Estilos del Formulario */
.login-form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.form-group label {
  font-size: 14px;
  font-weight: 500;
  color: #374151;
}

.form-group input {
  width: 100%;
  padding: 12px 16px;
  font-size: 15px;
  border: 1px solid #d1d5db;
  border-radius: 10px;
  background-color: #fff;
  outline: none;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  transition: all 0.2s ease;
  box-sizing: border-box;
}

.form-group input:focus {
  border-color: #4f46e5;
  box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
}

/* Botón de envío */
.btn-ingresar {
  width: 100%;
  border: none;
  background: #4f46e5;
  color: white;
  padding: 14px;
  font-size: 15px;
  font-weight: 600;
  border-radius: 10px;
  cursor: pointer;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  transition: background 0.2s, transform 0.1s;
  margin-top: 4px;
}

.btn-ingresar:hover {
  background: #4338ca;
}

.btn-ingresar:active {
  transform: scale(0.98);
}

/* Mensajes de Alerta de Error */
.mensaje-error {
  display: flex;
  align-items: center;
  gap: 10px;
  background: #fef2f2;
  border: 1px solid #fca5a5;
  padding: 12px;
  border-radius: 8px;
}

.error-icono {
  font-size: 16px;
  flex-shrink: 0;
}

.mensaje-error p {
  margin: 0;
  font-size: 13.5px;
  color: #991b1b;
  font-weight: 500;
}

/* Bloque Informativo de Credenciales Demo */
.credenciales-demo {
  margin-top: 32px;
  background-color: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 16px;
}

.demo-titulo {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 13px;
  color: #475569;
  margin-bottom: 8px;
}

.demo-icono {
  font-size: 14px;
}

.demo-detalles {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.demo-detalles p {
  margin: 0;
  font-size: 13px;
  color: #64748b;
}

.demo-detalles code {
  background: #cbd5e1;
  color: #1e293b;
  padding: 2px 6px;
  border-radius: 4px;
  font-family: monospace;
  font-size: 12px;
}
</style>