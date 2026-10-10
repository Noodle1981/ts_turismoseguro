import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/auth/LoginView.vue'
import RegisterRentView from '../views/auth/RegisterRentView.vue'
import RegisterClientView from '../views/auth/RegisterClientView.vue'
import ForgotPasswordView from '../views/auth/ForgotPasswordView.vue'
import ResetPasswordView from '../views/auth/ResetPasswordView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
    meta: {
      title: 'Inicio — Turismo Seguro San Juan',
    },
  },
  // Public Catalog Routes
  {
    path: '/alquileres',
    name: 'properties',
    component: () => import('../views/catalog/PropertyListView.vue'),
    meta: {
      title: 'Alquileres Verificados — Turismo Seguro San Juan',
    },
  },
  {
    path: '/alquileres/:slug',
    name: 'property-detail',
    component: () => import('../views/catalog/PropertyDetailView.vue'),
    meta: {
      title: 'Detalle del Alquiler — Turismo Seguro San Juan',
    },
  },
  {
    path: '/experiencias',
    name: 'experiences',
    component: () => import('../views/catalog/ExperienceListView.vue'),
    meta: {
      title: 'Experiencias y Excursiones — Turismo Seguro San Juan',
    },
  },
  // Host / Client Routes (Protected by role)
  {
    path: '/panel/cliente',
    name: 'client-dashboard',
    component: () => import('../views/client/ClientDashboardView.vue'),
    meta: {
      title: 'Panel del Anfitrión — Turismo Seguro San Juan',
      requiresAuth: true,
      roles: ['client', 'super_admin'],
    },
  },
  {
    path: '/panel/cliente/propiedades/nueva',
    name: 'client-property-new',
    component: () => import('../views/client/PropertyFormView.vue'),
    meta: {
      title: 'Publicar Alojamiento — Turismo Seguro San Juan',
      requiresAuth: true,
      roles: ['client', 'super_admin'],
    },
  },
  {
    path: '/panel/cliente/propiedades/:id/disponibilidad',
    name: 'client-property-availability',
    component: () => import('../views/client/ClientAvailabilityView.vue'),
    meta: {
      title: 'Gestionar Disponibilidad — Turismo Seguro San Juan',
      requiresAuth: true,
      roles: ['client', 'super_admin'],
    },
  },
  // Auth Routes
  {
    path: '/login',
    name: 'login',
    component: LoginView,
    meta: {
      title: 'Iniciar Sesión — Turismo Seguro San Juan',
      guestOnly: true,
    },
  },
  {
    path: '/register-rent',
    name: 'register-rent',
    component: RegisterRentView,
    meta: {
      title: 'Registro de Huésped — Turismo Seguro San Juan',
      guestOnly: true,
    },
  },
  {
    path: '/register-client',
    name: 'register-client',
    component: RegisterClientView,
    meta: {
      title: 'Registro de Propietario — Turismo Seguro San Juan',
      guestOnly: true,
    },
  },
  {
    path: '/forgot-password',
    name: 'forgot-password',
    component: ForgotPasswordView,
    meta: {
      title: 'Recuperar Contraseña — Turismo Seguro San Juan',
      guestOnly: true,
    },
  },
  {
    path: '/reset-password',
    name: 'reset-password',
    component: ResetPasswordView,
    meta: {
      title: 'Restablecer Contraseña — Turismo Seguro San Juan',
      guestOnly: true,
    },
  },
  {
    path: '/403',
    name: 'forbidden',
    component: () => import('../views/errors/ForbiddenView.vue'),
    meta: {
      title: 'Acceso No Autorizado — Turismo Seguro San Juan',
    },
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: () => import('../views/errors/NotFoundView.vue'),
    meta: {
      title: 'Página No Encontrada — Turismo Seguro San Juan',
    },
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0 }
  },
})

// Navigation Guard
router.beforeEach(async (to, _from, next) => {
  const authStore = useAuthStore()

  // Initial session check if token exists but user not loaded yet
  if (authStore.token && !authStore.user && !authStore.isLoading) {
    try {
      await authStore.fetchUser()
    } catch {
      // Token expired or invalid
    }
  }

  // Guest-only guard (login, register, forgot-password, etc.)
  if (to.meta.guestOnly && authStore.isAuthenticated) {
    return next({ path: '/' })
  }

  // Protected route guard
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    return next({
      path: '/login',
      query: { redirect: to.fullPath },
    })
  }

  // Role-based protection guard
  if (to.meta.roles && Array.isArray(to.meta.roles)) {
    const userRole = authStore.user?.role
    if (!userRole || !to.meta.roles.includes(userRole)) {
      return next({ path: '/403' })
    }
  }

  next()
})

router.afterEach((to) => {
  if (to.meta.title) {
    document.title = String(to.meta.title)
  }
})

export default router
