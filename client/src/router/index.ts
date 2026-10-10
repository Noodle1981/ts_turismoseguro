import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import DetalleQuintaView from '../views/DetalleQuintaView.vue'
import AuthView from '../views/AuthView.vue'
import ValidadorActaView from '../views/ValidadorActaView.vue'
import EventosCercanosView from '../views/EventosCercanosView.vue'
import ExplorarQuintasView from '../views/ExplorarQuintasView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
    meta: {
      title: 'Turismo Seguro San Juan — Alquileres Diurnos Verificados',
    },
  },
  {
    path: '/explorar',
    name: 'explorar',
    component: ExplorarQuintasView,
    meta: {
      title: 'Catálogo de Filtros y Quintas Verificadas — Turismo Seguro San Juan',
    },
  },
  {
    path: '/quintas/:id',
    name: 'quinta-detalle',
    component: DetalleQuintaView,
    meta: {
      title: 'Detalle de Quinta Diurna — Turismo Seguro San Juan',
    },
  },
  {
    path: '/login',
    name: 'login',
    component: AuthView,
    meta: {
      title: 'Iniciar Sesión y Registro — Turismo Seguro San Juan',
    },
  },
  {
    path: '/registro',
    name: 'registro',
    component: AuthView,
    meta: {
      title: 'Registro de Propietario — Turismo Seguro San Juan',
    },
  },
  {
    path: '/validador/acta/:id?',
    name: 'validador-acta',
    component: ValidadorActaView,
    meta: {
      title: 'Acta de Inspección In Situ — Turismo Seguro San Juan',
    },
  },
  {
    path: '/eventos',
    name: 'eventos',
    component: EventosCercanosView,
    meta: {
      title: 'Eventos & Experiencias Cercanas — Turismo Seguro San Juan',
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

router.afterEach((to) => {
  if (to.meta.title) {
    document.title = String(to.meta.title)
  }
})

export default router
