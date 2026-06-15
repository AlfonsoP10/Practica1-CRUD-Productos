import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const routes = [
  {
    path: '/',
    component: () => import('@/views/HomeView.vue'),
  },
  {
    path: '/catalogo',
    component: () => import('@/views/CatalogoView.vue'),
  },
  {
    path: '/catalogo/:id',
    component: () => import('@/views/ProductoDetalle.vue'),
    props: true,
  },
  {
    path: '/login',
    component: () => import('@/views/LoginView.vue'),
  },
  {
    path: '/carrito',
    name: 'carrito',
    component: () => import('@/views/CartView.vue'),
  },
  {
    path: '/admin',
    component: () => import('@/layouts/AdminLayout.vue'),
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        component: () => import('@/views/admin/Dashboard.vue'),
      },
      {
        path: 'productos',
        component: () => import('@/views/admin/Productos.vue'),
      },
      {
        path: 'nuevo',
        component: () => import('@/views/admin/NuevoProducto.vue'),
      },
    ],
  },
  {
    path: '/404',
    component: () => import('@/views/NotFound.vue'),
  },
  {
    path: '/:pathMatch(.*)*',
    redirect: '/404',
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach(async (to) => {
  const auth = useAuthStore()

  if (auth.token && !auth.user) {
    await auth.fetchUser()
  }

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return {
      path: '/login',
      query: { redirect: to.fullPath },
    }
  }
})

export default router