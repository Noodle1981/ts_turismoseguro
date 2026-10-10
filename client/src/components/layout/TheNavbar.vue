<script setup lang="ts">
import { ref } from 'vue'
import { RouterLink } from 'vue-router'
import BadgeVerified from '../common/BadgeVerified.vue'
import { Menu, X, ShieldCheck } from 'lucide-vue-next'

const isMobileMenuOpen = ref(false)

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
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

        <!-- Right Action Button -->
        <div class="hidden md:flex items-center gap-3">
          <button
            type="button"
            class="px-4 py-2 text-sm font-semibold text-white bg-accent-500 hover:bg-accent-600 active:scale-95 rounded-xl shadow-xs transition-all duration-200 cursor-pointer"
          >
            Publicar Propiedad
          </button>
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
    <div v-if="isMobileMenuOpen" class="md:hidden border-t border-slate-200/80 bg-white px-4 pt-2 pb-4 space-y-2">
      <RouterLink
        to="/"
        class="block px-3 py-2 rounded-lg text-base font-medium text-surface-700 hover:bg-surface-100"
        @click="isMobileMenuOpen = false"
      >
        Inicio
      </RouterLink>
      <div class="pt-2">
        <button
          type="button"
          class="w-full text-center px-4 py-2.5 text-sm font-semibold text-white bg-accent-500 rounded-xl shadow-xs"
        >
          Publicar Propiedad
        </button>
      </div>
    </div>
  </header>
</template>
