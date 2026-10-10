<script setup lang="ts">
import { ref } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { useAuth } from '../../composables/useAuth'
import BadgeVerified from '../common/BadgeVerified.vue'
import { Menu, X, ShieldCheck, LogOut, ChevronDown } from 'lucide-vue-next'

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
  <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <!-- Brand Logo -->
        <RouterLink to="/" class="flex items-center gap-2 group cursor-pointer">
          <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-brand-900 via-brand-700 to-brand-500 flex items-center justify-center text-white shadow-sm transition-transform duration-200 group-hover:scale-105">
            <ShieldCheck class="w-5 h-5 text-white" />
          </div>
          <div class="flex flex-col">
            <span class="font-display font-extrabold text-brand-950 text-lg leading-tight tracking-tight">
              Turismo Seguro
            </span>
            <span class="text-[10px] font-semibold tracking-widest uppercase text-brand-700">San Juan</span>
          </div>
          <BadgeVerified size="sm" class="hidden sm:inline-flex ml-1" />
        </RouterLink>

        <!-- Desktop Navigation Links -->
        <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-surface-700">
          <RouterLink to="/" class="hover:text-brand-700 transition-colors py-1">Inicio</RouterLink>
          <span class="text-surface-300">/</span>
          <span class="text-surface-400 cursor-not-allowed">Alquileres</span>
          <span class="text-surface-300">/</span>
          <span class="text-surface-400 cursor-not-allowed">Experiencias</span>
        </nav>

        <!-- Right Action Button / User Session -->
        <div class="hidden md:flex items-center gap-3">
          <template v-if="!isAuthenticated">
            <RouterLink
              to="/login"
              class="px-3.5 py-2 text-sm font-semibold text-surface-700 hover:text-brand-700 transition-colors"
            >
              Iniciar Sesión
            </RouterLink>
            <RouterLink
              to="/register-rent"
              class="px-4 py-2 text-sm font-semibold text-brand-700 bg-brand-50 hover:bg-brand-100 rounded-xl transition-all"
            >
              Registrarse
            </RouterLink>
            <RouterLink
              to="/register-client"
              class="px-4 py-2 text-sm font-semibold text-white bg-accent-500 hover:bg-accent-600 active:scale-95 rounded-xl shadow-xs transition-all duration-200"
            >
              Publicar Propiedad
            </RouterLink>
          </template>

          <!-- Authenticated User Menu -->
          <div v-else class="relative">
            <button
              type="button"
              class="flex items-center gap-2 px-3 py-1.5 rounded-xl border border-slate-200 hover:border-slate-300 bg-surface-50 hover:bg-surface-100 transition-all text-left cursor-pointer"
              @click="toggleUserMenu"
            >
              <div class="w-8 h-8 rounded-lg bg-brand-600 text-white flex items-center justify-center font-bold text-xs uppercase">
                {{ user?.name?.charAt(0) || 'U' }}
              </div>
              <div class="flex flex-col">
                <span class="text-xs font-bold text-surface-900 leading-tight">{{ user?.name }}</span>
                <span class="text-[10px] text-surface-500 font-medium">
                  {{ user?.role ? roleNames[user.role] : '' }}
                </span>
              </div>
              <ChevronDown class="w-3.5 h-3.5 text-surface-400 ml-1" />
            </button>

            <!-- Dropdown Menu -->
            <div
              v-if="isUserMenuOpen"
              class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-xl border border-slate-100 py-1.5 z-50 animate-in fade-in slide-in-from-top-1"
            >
              <div class="px-3 py-2 border-b border-slate-100 text-xs">
                <p class="font-medium text-surface-500">Sesión iniciada como</p>
                <p class="font-bold text-surface-800 truncate">{{ user?.email }}</p>
              </div>

              <button
                type="button"
                class="w-full flex items-center gap-2 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 transition-colors text-left cursor-pointer"
                @click="handleLogout"
              >
                <LogOut class="w-4 h-4" />
                <span>Cerrar Sesión</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Mobile menu button -->
        <div class="flex md:hidden">
          <button
            type="button"
            class="p-2 rounded-lg text-surface-700 hover:bg-surface-100 cursor-pointer"
            aria-label="Abrir menú"
            @click="toggleMobileMenu"
          >
            <Menu v-if="!isMobileMenuOpen" class="w-6 h-6" />
            <X v-else class="w-6 h-6" />
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile menu modal/drawer -->
    <div v-if="isMobileMenuOpen" class="md:hidden border-t border-slate-200/80 bg-white px-4 pt-2 pb-4 space-y-3">
      <RouterLink
        to="/"
        class="block px-3 py-2 rounded-lg text-base font-medium text-surface-700 hover:bg-surface-100"
        @click="isMobileMenuOpen = false"
      >
        Inicio
      </RouterLink>

      <template v-if="!isAuthenticated">
        <RouterLink
          to="/login"
          class="block px-3 py-2 rounded-lg text-base font-medium text-surface-700 hover:bg-surface-100"
          @click="isMobileMenuOpen = false"
        >
          Iniciar Sesión
        </RouterLink>
        <RouterLink
          to="/register-rent"
          class="block px-3 py-2 rounded-lg text-base font-medium text-brand-700 bg-brand-50 hover:bg-brand-100"
          @click="isMobileMenuOpen = false"
        >
          Registrarse como Huésped
        </RouterLink>
        <RouterLink
          to="/register-client"
          class="block w-full text-center px-4 py-2.5 text-sm font-semibold text-white bg-accent-500 rounded-xl shadow-xs"
          @click="isMobileMenuOpen = false"
        >
          Publicar Propiedad (Anfitriones)
        </RouterLink>
      </template>

      <template v-else>
        <div class="px-3 py-2 bg-surface-50 rounded-xl flex items-center justify-between">
          <div>
            <p class="text-sm font-bold text-surface-900">{{ user?.name }}</p>
            <p class="text-xs text-surface-500">{{ user?.email }}</p>
          </div>
          <span class="text-xs px-2 py-0.5 rounded-full bg-brand-100 text-brand-800 font-semibold">
            {{ user?.role ? roleNames[user.role] : '' }}
          </span>
        </div>
        <button
          type="button"
          class="w-full flex items-center justify-center gap-2 px-3 py-2 text-sm font-semibold text-red-600 bg-red-50 rounded-xl"
          @click="handleLogout"
        >
          <LogOut class="w-4 h-4" />
          <span>Cerrar Sesión</span>
        </button>
      </template>
    </div>
  </header>
</template>
