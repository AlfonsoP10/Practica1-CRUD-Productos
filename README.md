# Proyecto Integrador - Tienda Online Full-Stack

## Descripción

Este proyecto consiste en una aplicación web Full-Stack desarrollada con Laravel y Vue.js. La aplicación permite administrar productos mediante operaciones CRUD, gestionar autenticación de usuarios, navegar entre vistas mediante Vue Router y realizar compras utilizando un carrito implementado con Pinia.

Además, incorpora la subida y visualización de imágenes de productos mediante Laravel Storage y FormData.

---

## Tecnologías Utilizadas

### Backend

* Laravel 12
* Laravel Sanctum
* MySQL
* Laravel Storage

### Frontend

* Vue 3
* Vue Router 4
* Pinia
* Axios
* Vite

---

## Funcionalidades Implementadas

### Gestión de Productos

* Crear productos
* Editar productos
* Eliminar productos
* Listar productos
* Ver detalle de producto
* Subida de imágenes
* Vista previa de imágenes

### Autenticación

* Inicio de sesión
* Protección de rutas privadas
* Acceso restringido al panel de administración

### Catálogo

* Búsqueda de productos
* Paginación de resultados
* Visualización de imágenes

### Carrito de Compras

* Agregar productos
* Eliminar productos
* Modificar cantidades
* Cálculo automático de subtotales
* Cálculo automático del total
* Persistencia mediante LocalStorage

### Mejoras de UX

* Validación de formularios
* Mensajes de éxito y error
* Indicadores de carga
* Vista previa de imágenes

---

## Instalación del Backend

```bash
cd backend

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate

php artisan storage:link

php artisan serve
```

Configurar en el archivo .env:

```env
FILESYSTEM_DISK=public
```

Servidor:

```txt
http://localhost:8000
```

---

## Instalación del Frontend

```bash
cd frontend

npm install

npm run dev
```

Servidor:

```txt
http://localhost:5173
```

---

## Estructura General

```txt
backend/
├── app/
├── database/
├── routes/
└── storage/

frontend/
├── src/
│   ├── components/
│   ├── views/
│   ├── stores/
│   ├── router/
│   └── services/
└── public/
```

---

## Evidencias Incluidas

* Login funcional
* Catálogo con imágenes
* Formulario de productos
* Vista previa de imágenes
* Carrito de compras
* Persistencia LocalStorage
* Protección de rutas
* Paginación
* Panel de administración

---

## Autor

Alfonso Pineda Hernández

Ingeniería en Sistemas Computacionales

Universidad Politécnica de Texcoco
