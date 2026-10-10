<script setup lang="ts">
import { ref } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { useAuth } from '../../composables/useAuth'
import BadgeVerified from '../common/BadgeVerified.vue'
import { Menu, X, LogOut, ChevronDown } from 'lucide-vue-next'

const router = useRouter()
const { user, isAuthenticated, logout } = useAuth()

const isMobileMenuOpen = ref(false)
const isUserMenuOpen = ref(false)

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
}

const toggleUserMenu = () => {
  isUserMenuOpen.value = !isUserMenuOpen.value
}

const handleLogout = async () => {
  isUserMenuOpen.value = false
  isMobileMenuOpen.value = false
  await logout()
  router.push('/login')
}

const roleNames: Record<string, string> = {
  super_admin: 'Super Admin',
  supervisor: 'Supervisor',
  validator: 'Validador',
  client: 'Anfitrión',
  rent: 'Huésped',
}
</script>

<template>
  <div>
    <!-- Top Safety Guarantee Ticker -->
    <section class="w-full bg-secondary text-on-secondary py-1.5 px-4 sm:px-6 text-xs font-semibold">
      <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2 text-center md:text-left">
        <div class="flex items-center gap-1.5 mx-auto md:mx-0">
          <span class="material-symbols-outlined text-secondary-fixed text-[16px]">verified_user</span>
          <span class="tracking-wide">
            Auditoría in situ de cada propiedad • Trato directo por WhatsApp • Sin comisiones por reserva
          </span>
        </div>
        <div class="hidden lg:flex items-center gap-4 text-[11px] text-secondary-fixed font-medium">
          <span class="flex items-center gap-1">
            <span class="material-symbols-outlined text-[14px]">location_on</span>
            San Juan Capital &amp; 19 Departamentos
          </span>
          <span class="flex items-center gap-1">
            <span class="material-symbols-outlined text-[14px]">lock</span>
            Cuentas cotejadas presencialmente
          </span>
        </div>
      </div>
    </section>

    <!-- Launch Promo Ribbon -->
    <div class="w-full bg-tertiary-container text-tertiary-fixed py-1.5 px-4 sm:px-6 border-b border-tertiary-fixed/20 text-xs">
      <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2">
        <div class="flex items-center gap-1.5 mx-auto md:mx-0">
          <span class="material-symbols-outlined text-tertiary-fixed text-[16px] animate-pulse">campaign</span>
          <span>
            <strong class="font-bold text-on-tertiary">¡Lanzamiento San Juan!</strong>
            Las primeras 50 ofertas tienen <strong>6 meses de suscripción 100% gratis</strong> y auditoría sin cargo.
          </span>
        </div>
        <div class="hidden md:flex items-center gap-2 mx-auto md:mx-0">
          <RouterLink
            to="/register-client"
            class="inline-flex items-center gap-1 text-[11px] bg-tertiary-fixed text-on-tertiary-fixed px-2.5 py-0.5 rounded-full font-bold hover:bg-surface-bright transition-colors"
          >
            <span class="material-symbols-outlined text-[13px]">bolt</span>
            Aprovechar Cupo
          </RouterLink>
        </div>
      </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-50 bg-surface-container-lowest/95 backdrop-blur-md border-b border-surface-container shadow-xs">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-18">
          <!-- Brand Logo -->
          <div class="flex items-center gap-3">
            <RouterLink to="/" class="flex items-center gap-2.5 group cursor-pointer">
              <div class="w-9 h-9 rounded-xl bg-primary text-secondary-fixed flex items-center justify-center font-bold shadow-xs transition-transform duration-200 group-hover:scale-105">
                <span class="material-symbols-outlined text-[22px]">shield</span>
              </div>
              <div class="flex flex-col">
                <span class="font-headline-sm font-extrabold text-primary text-lg leading-tight tracking-tight">
                  Turismo Seguro <span class="text-secondary font-bold">San Juan</span>
                </span>
                <span class="text-[10px] font-bold tracking-widest uppercase text-on-surface-variant">
                  Portal Oficial Verificado
                </span>
              </div>
            </RouterLink>
            <div class="hidden xl:inline-flex">
              <BadgeVerified size="sm" />
            </div>
          </div>

          <!-- Desktop Navigation Links -->
          <nav class="hidden lg:flex items-center gap-1 text-sm font-semibold text-on-surface-variant">
            <RouterLink
              to="/"
              class="px-3.5 py-2 rounded-lg hover:text-primary hover:bg-surface-container transition-colors"
              active-class="bg-primary text-on-primary hover:bg-primary-container hover:text-on-primary"
            >
              Explorar Quintas
            </RouterLink>
            <RouterLink
              to="/alquileres"
              class="px-3.5 py-2 rounded-lg hover:text-primary hover:bg-surface-container transition-colors"
              active-class="bg-primary text-on-primary hover:bg-primary-container hover:text-on-primary"
            >
              Alquileres
            </RouterLink>
            <RouterLink
              to="/experiencias"
              class="px-3.5 py-2 rounded-lg hover:text-primary hover:bg-surface-container transition-colors"
              active-class="bg-primary text-on-primary hover:bg-primary-container hover:text-on-primary"
            >
              Experiencias &amp; Eventos
            </RouterLink>
          </nav>

          <!-- Right Action Buttons -->
          <div class="hidden sm:flex items-center gap-2.5">
            <template v-if="!isAuthenticated">
              <RouterLink
                to="/register-client"
                class="hidden md:inline-flex items-center gap-1 px-3.5 py-2 rounded-lg text-xs font-bold text-on-surface-variant hover:text-primary hover:bg-surface-container transition-colors"
              >
                <span class="material-symbols-outlined text-secondary text-[18px]">verified</span>
                <span>Contactar Validador</span>
              </RouterLink>

              <RouterLink
                to="/register-client"
                class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-on-primary bg-primary hover:bg-primary-container rounded-lg transition-colors shadow-xs"
              >
                <span class="material-symbols-outlined text-[16px]">add_home</span>
                <span>Publicar Quinta</span>
              </RouterLink>

              <RouterLink
                to="/login"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-primary bg-surface-container hover:bg-surface-dim rounded-lg transition-colors"
              >
                <span class="material-symbols-outlined text-secondary text-[18px]">login</span>
                <span>Ingresar</span>
              </RouterLink>
            </template>

            <!-- Authenticated User Menu -->
            <div v-else class="relative">
              <button
                type="button"
                class="flex items-center gap-2 px-3 py-1.5 rounded-xl border border-surface-container-high hover:border-outline-variant bg-surface-container-lowest transition-all text-left cursor-pointer"
                @click="toggleUserMenu"
              >
                <div class="w-8 h-8 rounded-lg bg-primary text-secondary-fixed flex items-center justify-center font-bold text-xs uppercase">
                  {{ user?.name?.charAt(0) || 'U' }}
                </div>
                <div class="flex flex-col">
                  <span class="text-xs font-bold text-on-surface leading-tight">{{ user?.name }}</span>
                  <span class="text-[10px] text-on-surface-variant font-medium">
                    {{ user?.role ? roleNames[user.role] : '' }}
                  </span>
                </div>
                <ChevronDown class="w-3.5 h-3.5 text-outline ml-1" />
              </button>

              <!-- Dropdown Menu -->
              <div
                v-if="isUserMenuOpen"
                class="absolute right-0 mt-2 w-56 bg-surface-container-lowest rounded-xl shadow-modal-stitch border border-surface-container py-1.5 z-50"
              >
                <div class="px-3.5 py-2 border-b border-surface-container text-xs">
                  <p class="font-medium text-on-surface-variant">Sesión iniciada</p>
                  <p class="font-bold text-on-surface truncate">{{ user?.email }}</p>
                </div>

                <RouterLink
                  v-if="user?.role === 'client' || user?.role === 'super_admin'"
                  to="/panel/cliente"
                  class="block px-3.5 py-2 text-xs font-bold text-on-surface hover:bg-surface-container transition-colors"
                  @click="isUserMenuOpen = false"
                >
                  Panel del Anfitrión
                </RouterLink>

                <button
                  type="button"
                  class="w-full flex items-center gap-2 px-3.5 py-2 text-xs font-bold text-red-600 hover:bg-red-50 transition-colors text-left cursor-pointer"
                  @click="handleLogout"
                >
                  <LogOut class="w-4 h-4" />
                  <span>Cerrar Sesión</span>
                </button>
              </div>
            </div>
          </div>

          <!-- Mobile menu trigger -->
          <div class="flex sm:hidden">
            <button
              type="button"
              class="p-2 rounded-lg text-on-surface hover:bg-surface-container cursor-pointer"
              aria-label="Abrir menú"
              @click="toggleMobileMenu"
            >
              <Menu v-if="!isMobileMenuOpen" class="w-6 h-6" />
              <X v-else class="w-6 h-6" />
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile drawer -->
      <div v-if="isMobileMenuOpen" class="sm:hidden border-t border-surface-container bg-surface-container-lowest px-4 py-3 space-y-2">
        <RouterLink
          to="/"
          class="block px-3 py-2 rounded-lg text-sm font-bold text-on-surface hover:bg-surface-container"
          @click="isMobileMenuOpen = false"
        >
          Explorar Quintas
        </RouterLink>
        <RouterLink
          to="/alquileres"
          class="block px-3 py-2 rounded-lg text-sm font-bold text-on-surface hover:bg-surface-container"
          @click="isMobileMenuOpen = false"
        >
          Catálogo Alquileres
        </RouterLink>
        <RouterLink
          to="/experiencias"
          class="block px-3 py-2 rounded-lg text-sm font-bold text-on-surface hover:bg-surface-container"
          @click="isMobileMenuOpen = false"
        >
          Experiencias &amp; Eventos
        </RouterLink>

        <template v-if="!isAuthenticated">
          <RouterLink
            to="/login"
            class="block px-3 py-2 rounded-lg text-sm font-bold text-primary bg-surface-container"
            @click="isMobileMenuOpen = false"
          >
            Iniciar Sesión
          </RouterLink>
          <RouterLink
            to="/register-client"
            class="block w-full text-center px-4 py-2.5 text-xs font-bold text-on-primary bg-primary rounded-lg shadow-xs"
            @click="isMobileMenuOpen = false"
          >
            Publicar Quinta (Anfitriones)
          </RouterLink>
        </template>
        <template v-else>
          <button
            type="button"
            class="w-full flex items-center justify-center gap-2 px-3 py-2 text-xs font-bold text-red-600 bg-red-50 rounded-lg"
            @click="handleLogout"
          >
            <LogOut class="w-4 h-4" />
            <span>Cerrar Sesión</span>
          </button>
        </template>
      </div>
    </header>
  </div>
</template>
