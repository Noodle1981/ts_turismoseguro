<script setup lang="ts">
import { RouterLink, useRoute } from 'vue-router'

defineProps<{
  userName?: string
}>()

const emit = defineEmits<{
  (e: 'logout'): void
}>()

const route = useRoute()
</script>

<template>
  <header
    class="fixed top-0 left-0 right-0 h-16 bg-surface/85 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)] z-40 flex items-center justify-between px-space-md lg:px-space-lg border-b border-surface-container transition-all duration-200"
  >
    <div class="flex items-center gap-space-sm lg:gap-space-md">
      <!-- Logo y sello técnico de San Juan -->
      <RouterLink to="/" class="flex items-center gap-space-xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 rounded-lg">
        <div class="w-9 h-9 rounded-xl bg-primary flex items-center justify-center text-on-primary shrink-0 shadow-2xs">
          <span class="material-symbols-outlined text-secondary-fixed text-[20px]">verified_user</span>
        </div>
        <div class="flex flex-col">
          <span class="font-headline-sm text-[16px] text-primary font-black tracking-tight leading-none">
            TURISMO SEGURO
          </span>
          <span class="font-label-sm text-[10px] text-on-surface-variant tracking-wider uppercase leading-none mt-0.5 font-bold">
            Mesa de Supervisión
          </span>
        </div>
      </RouterLink>

      <!-- Indicador de Jurisdicción Provincial -->
      <div class="hidden sm:flex items-center gap-space-xs text-on-surface-variant font-label-sm text-label-sm pl-2 border-l border-surface-container">
        <span class="material-symbols-outlined text-[16px] text-tertiary" aria-hidden="true">pin_drop</span>
        <span class="font-semibold text-primary">San Juan Capital • Mesa Técnica</span>
      </div>
    </div>

    <div class="flex items-center gap-space-sm lg:gap-space-md">
      <!-- Links de navegación con estilo píldora activa idéntico al panel de propietario -->
      <nav class="hidden md:flex items-center gap-1.5 bg-surface-container-low px-2 py-1 rounded-xl border border-surface-container">
        <RouterLink
          to="/supervisor/mesa"
          class="px-3 py-1 rounded-lg font-label-sm text-label-sm font-bold flex items-center gap-1.5 transition-all"
          :class="route.path.startsWith('/supervisor')
            ? 'bg-primary text-on-primary shadow-2xs'
            : 'text-on-surface-variant hover:text-primary hover:bg-surface-container'"
        >
          <span class="material-symbols-outlined text-[16px]">gavel</span>
          <span>Mesa Dictámenes</span>
        </RouterLink>
        <RouterLink
          to="/validador/bandeja"
          class="px-3 py-1 rounded-lg font-label-sm text-label-sm font-semibold flex items-center gap-1.5 transition-all text-on-surface-variant hover:text-primary hover:bg-surface-container"
        >
          <span class="material-symbols-outlined text-[16px]">inbox</span>
          <span>Bandeja Terreno</span>
        </RouterLink>
        <RouterLink
          to="/validador/mapa"
          class="px-3 py-1 rounded-lg font-label-sm text-label-sm font-semibold flex items-center gap-1.5 transition-all text-on-surface-variant hover:text-primary hover:bg-surface-container"
        >
          <span class="material-symbols-outlined text-[16px]">map</span>
          <span>Mapa GPS</span>
        </RouterLink>
      </nav>

      <!-- Botón Ver Portal (idéntico al de propietario) -->
      <RouterLink
        to="/"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-surface-container-low hover:bg-surface-container-high text-primary font-label-sm text-label-sm font-bold border border-surface-container transition-all shadow-2xs cursor-pointer"
        title="Ver el buscador público de inquilinos"
      >
        <span class="material-symbols-outlined text-[16px] text-secondary">visibility</span>
        <span class="hidden sm:inline">Ver Portal</span>
      </RouterLink>

      <!-- Usuario y Botón Cerrar Sesión (Idéntico a PropietarioTopHeader) -->
      <div class="flex items-center gap-1.5 pl-1">
        <div class="flex items-center gap-2 p-1 sm:px-2.5 sm:py-1 rounded-xl bg-surface-container-low border border-surface-container">
          <div class="w-7 h-7 rounded-full bg-primary flex items-center justify-center text-on-primary shrink-0">
            <span class="text-xs font-bold">{{ userName ? userName.split(' ').map((n: string) => n[0]).slice(0, 2).join('') : 'SP' }}</span>
          </div>
          <span class="font-label-sm text-label-sm text-primary font-bold hidden md:inline truncate max-w-[120px]">
            {{ userName ? userName.split(' ')[0] : 'Supervisor' }}
          </span>
        </div>

        <button
          type="button"
          class="w-9 h-9 rounded-xl flex items-center justify-center text-on-surface-variant hover:text-error hover:bg-surface-container border border-surface-container transition-colors cursor-pointer"
          title="Cerrar sesión de supervisor"
          aria-label="Cerrar sesión de supervisor"
          @click="emit('logout')"
        >
          <span class="material-symbols-outlined text-[18px]">logout</span>
        </button>
      </div>
    </div>
  </header>
</template>
