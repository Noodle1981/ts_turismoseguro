import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import DetalleQuintaView from '../views/DetalleQuintaView.vue'
import AuthView from '../views/AuthView.vue'
import ValidadorActaView from '../views/ValidadorActaView.vue'
import EventosCercanosView from '../views/EventosCercanosView.vue'
import ExplorarQuintasView from '../views/ExplorarQuintasView.vue'
import StaffAuthView from '../views/StaffAuthView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
    meta: {
      title: 'Turismo Seguro — Alquileres Diurnos Verificados',
    },
  },
  {
    path: '/explorar',
    name: 'explorar',
    component: ExplorarQuintasView,
    meta: {
      title: 'Catálogo de Filtros y Quintas Verificadas — Turismo Seguro',
    },
  },
  {
    path: '/quintas/:id',
    name: 'quinta-detalle',
    component: DetalleQuintaView,
    meta: {
      title: 'Detalle de Quinta Diurna — Turismo Seguro',
    },
  },
  {
    path: '/login',
    name: 'login',
    component: AuthView,
    meta: {
      title: 'Iniciar Sesión y Registro — Turismo Seguro',
      hideLayout: true,
      guestOnly: true,
    },
  },
  {
    path: '/registro',
    name: 'registro',
    component: AuthView,
    meta: {
      title: 'Registro de Propietario — Turismo Seguro',
      hideLayout: true,
      guestOnly: true,
    },
  },
  {
    path: '/staff/login',
    name: 'staff-login',
    component: StaffAuthView,
    meta: {
      title: 'Portal Staff & Auditoría — Turismo Seguro',
      hideLayout: true,
    },
  },
  {
    path: '/staff',
    redirect: '/staff/login',
  },
  {
    path: '/administracion/login',
    redirect: '/staff/login',
  },
  {
    path: '/validador',
    redirect: '/validador/bandeja',
  },
  {
    path: '/validador/bandeja',
    name: 'validador-bandeja',
    component: () => import('../views/ValidadorBandejaView.vue'),
    meta: {
      title: 'Bandeja Operativa de Solicitudes — Turismo Seguro',
      requiresAuth: true,
      roles: ['validator', 'supervisor', 'admin', 'super_admin'],
    },
  },
  {
    path: '/validador/mapa',
    name: 'validador-mapa',
    component: () => import('../views/ValidadorMapaView.vue'),
    meta: {
      title: 'Mapa Territorial de Inspecciones — Turismo Seguro',
      requiresAuth: true,
      roles: ['validator', 'supervisor', 'admin', 'super_admin'],
    },
  },
  {
    path: '/validador/acta/:id?',
    name: 'validador-acta',
    component: ValidadorActaView,
    meta: {
      title: 'Acta de Inspección In Situ — Turismo Seguro',
      requiresAuth: true,
      roles: ['validator', 'supervisor', 'admin', 'super_admin'],
    },
  },
  {
    path: '/supervisor',
    redirect: '/supervisor/mesa',
  },
  {
    path: '/supervisor/mesa',
    name: 'supervisor-mesa',
    component: () => import('../views/SupervisorDictamenesView.vue'),
    meta: {
      title: 'Mesa de Supervisión y Dictámenes Técnicos — Turismo Seguro',
      requiresAuth: true,
      roles: ['supervisor', 'admin', 'super_admin'],
    },
  },
  {
    path: '/eventos',
    name: 'eventos',
    component: EventosCercanosView,
    meta: {
      title: 'Eventos & Experiencias Cercanas — Turismo Seguro',
    },
  },
  {
    path: '/inquilino',
    redirect: '/inquilino/panel',
  },
  {
    path: '/inquilino/panel',
    name: 'inquilino-panel',
    component: () => import('../views/InquilinoPanelView.vue'),
    meta: {
      title: 'Mis Alquileres Diurnos & Pólizas — Turismo Seguro',
      requiresAuth: true,
      roles: ['rent', 'super_admin'],
    },
  },
  {
    path: '/propietario',
    redirect: '/propietario/panel',
  },
  {
    path: '/propietario/panel',
    name: 'propietario-panel',
    component: () => import('../views/PropietarioPanelView.vue'),
    meta: {
      title: 'Panel de Propietario — Turismo Seguro',
      hideLayout: true,
      requiresAuth: true,
      roles: ['client', 'super_admin'],
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

// Guardia de Navegación Global para Control de Acceso y Aislamiento de Roles (Vue Router 4 Modern Return API)
router.beforeEach((to) => {
  const authRaw = localStorage.getItem('ts_auth_user')
  const token = localStorage.getItem('ts_auth_token')
  let user: { role?: string; name?: string } | null = null

  if (token && authRaw) {
    try {
      user = JSON.parse(authRaw)
    } catch {
      user = null
    }
  }

  const isAuthenticated = !!token && !!user
  const userRole = user?.role ?? null

  // 1. Rutas que requieren autenticación
  if (to.meta.requiresAuth) {
    if (!isAuthenticated) {
      const allowedRoles = (to.meta.roles as string[]) || []
      const isStaffRoute = allowedRoles.some((r) =>
        ['validator', 'supervisor', 'admin', 'super_admin'].includes(r)
      )

      if (isStaffRoute) {
        return { path: '/staff/login', query: { redirect: to.fullPath } }
      } else if (allowedRoles.includes('client')) {
        return { path: '/login', query: { role: 'owner', redirect: to.fullPath } }
      } else {
        return { path: '/login', query: { role: 'renter', redirect: to.fullPath } }
      }
    }

    // Si está autenticado, validar que su rol tenga permiso para esta ruta
    const allowedRoles = (to.meta.roles as string[]) || []
    if (allowedRoles.length > 0 && userRole && !allowedRoles.includes(userRole)) {
      if (['supervisor', 'admin', 'super_admin'].includes(userRole)) {
        return { path: '/supervisor/mesa' }
      }
      if (userRole === 'validator') {
        return { path: '/validador/bandeja' }
      }
      if (userRole === 'client') {
        return { path: '/propietario/panel' }
      }
      if (userRole === 'rent') {
        return { path: '/inquilino/panel' }
      }
      return { path: '/' }
    }
  }

  // 2. Rutas exclusivas para visitantes no autenticados (login / registro)
  if (to.meta.guestOnly && isAuthenticated) {
    if (userRole === 'client') {
      return { path: '/propietario/panel' }
    } else if (userRole === 'rent') {
      return { path: '/inquilino/panel' }
    } else if (['supervisor', 'admin', 'super_admin'].includes(userRole || '')) {
      return { path: '/supervisor/mesa' }
    } else if (userRole === 'validator') {
      return { path: '/validador/bandeja' }
    }
  }
})

router.afterEach((to) => {
  if (to.meta.title) {
    document.title = String(to.meta.title)
  }
})

export default router
