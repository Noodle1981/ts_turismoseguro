<script setup lang="ts">
import { RouterLink } from 'vue-router'
import type { PropietarioOferta } from '../../../types/propietario'

defineProps<{
  myOffers: PropietarioOferta[]
  selectedOfferId: number
  activeNav: string
  userName?: string
}>()

const emit = defineEmits<{
  (e: 'selectOffer', id: number): void
  (e: 'updateNav', nav: 'general' | 'ofertas' | 'calendario' | 'inquilinos' | 'revalidacion'): void
  (e: 'openAddOffer'): void
  (e: 'logout'): void
}>()
</script>

<template>
  <aside
    class="fixed left-0 top-0 h-full w-72 bg-surface-container-lowest shadow-[0_1px_8px_rgba(0,0,0,0.04)] z-50 flex flex-col justify-between py-space-lg"
  >
    <div class="flex flex-col gap-space-lg">
      <!-- Brand Header -->
      <RouterLink to="/" class="px-space-lg flex items-center gap-space-sm">
        <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center">
          <span class="material-symbols-outlined text-on-primary">verified_user</span>
        </div>
        <div class="flex flex-col">
          <span class="font-headline-sm text-headline-sm text-primary tracking-tight">Turismo Seguro</span>
          <span class="font-label-sm text-label-sm text-secondary uppercase tracking-wider">Vista Propietarios</span>
        </div>
      </RouterLink>

      <!-- Píldoras si tiene múltiples ofertas -->
      <div v-if="myOffers.length > 1" class="mx-space-lg flex flex-col gap-1.5">
        <div class="flex items-center justify-between">
          <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">
            Ofertas Registradas:
          </span>
          <button
            type="button"
            class="text-[11px] font-bold text-secondary hover:underline cursor-pointer flex items-center gap-0.5"
            @click="emit('openAddOffer')"
          >
            <span class="material-symbols-outlined text-[13px]">add</span> Agregar
          </button>
        </div>
        <div class="flex flex-col gap-1.5">
          <button
            v-for="(offer, idx) in myOffers"
            :key="offer.id"
            type="button"
            class="w-full text-left px-3 py-2 rounded-lg text-xs font-bold transition-all cursor-pointer border flex items-center justify-between gap-2"
            :class="
              selectedOfferId === offer.id
                ? 'bg-primary text-on-primary border-primary shadow-xs'
                : 'bg-surface-container-low text-on-surface hover:bg-surface-container hover:text-primary border-surface-container'
            "
            @click="emit('selectOffer', offer.id)"
          >
            <div class="flex items-center gap-1.5 truncate">
              <span
                class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-mono shrink-0"
                :class="
                  selectedOfferId === offer.id
                    ? 'bg-on-primary text-primary'
                    : 'bg-surface-container-highest text-primary font-bold'
                "
              >
                {{ idx + 1 }}
              </span>
              <span class="truncate">{{ offer.title }}</span>
            </div>
            <span
              v-if="selectedOfferId === offer.id"
              class="material-symbols-outlined text-[15px] shrink-0 text-secondary-fixed"
            >
              check_circle
            </span>
          </button>
        </div>
      </div>

      <!-- Navigation Items -->
      <nav class="px-space-md flex flex-col gap-space-xs">
        <a
          href="#mis-ofertas-y-tarifas"
          class="flex items-center gap-space-sm px-space-md py-space-sm transition-all rounded-xl font-label-lg text-label-lg"
          :class="
            activeNav === 'ofertas'
              ? 'bg-primary-container text-on-primary shadow-[0_1px_3px_0_rgba(15,41,66,0.04)] font-bold'
              : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface'
          "
          @click="emit('updateNav', 'ofertas')"
        >
          <span class="material-symbols-outlined">sell</span>
          <span>Mis Ofertas y Tarifas</span>
        </a>

        <a
          href="#almanaque"
          class="flex items-center gap-space-sm px-space-md py-space-sm transition-all rounded-xl font-label-lg text-label-lg"
          :class="
            activeNav === 'calendario'
              ? 'bg-primary-container text-on-primary shadow-[0_1px_3px_0_rgba(15,41,66,0.04)] font-bold'
              : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface'
          "
          @click="emit('updateNav', 'calendario')"
        >
          <span class="material-symbols-outlined">calendar_month</span>
          <span>Calendario y Reservas</span>
        </a>

        <a
          href="#registro-inquilinos"
          class="flex items-center gap-space-sm px-space-md py-space-sm transition-all rounded-xl font-label-lg text-label-lg"
          :class="
            activeNav === 'inquilinos'
              ? 'bg-primary-container text-on-primary shadow-[0_1px_3px_0_rgba(15,41,66,0.04)] font-bold'
              : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface'
          "
          @click="emit('updateNav', 'inquilinos')"
        >
          <span class="material-symbols-outlined">assignment_ind</span>
          <span>Registro de Inquilinos</span>
        </a>

        <!-- Botón de Agregar Oferta directo en barra -->
        <button
          type="button"
          class="mt-2 flex items-center gap-space-sm px-space-md py-space-sm rounded-xl text-secondary hover:bg-secondary/10 transition-all font-label-lg text-label-lg font-bold cursor-pointer text-left"
          @click="emit('openAddOffer')"
        >
          <span class="material-symbols-outlined text-secondary">add_circle</span>
          <span>Agregar Oferta</span>
        </button>
      </nav>
    </div>

    <!-- Footer del Sidebar -->
    <div class="px-space-lg flex flex-col gap-space-sm">
      <div
        class="p-space-sm bg-secondary-container/40 rounded-xl flex items-center justify-between border border-secondary/20"
      >
        <div class="flex items-center gap-space-xs">
          <span class="material-symbols-outlined text-secondary text-[20px]">gshield</span>
          <div class="flex flex-col">
            <span class="font-label-sm text-label-sm text-on-surface font-bold">Trazabilidad Seguro</span>
            <span class="font-label-sm text-label-sm text-secondary">Vence: Nov 2025</span>
          </div>
        </div>
      </div>

      <div class="flex items-center justify-between pt-space-sm">
        <div class="flex items-center gap-space-sm min-w-0">
          <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center shrink-0">
            <span class="text-xs font-bold text-on-primary">
              {{ userName ? userName.split(' ').map((n: string) => n[0]).slice(0, 2).join('') : 'EM' }}
            </span>
          </div>
          <div class="flex flex-col min-w-0">
            <span class="font-label-sm text-label-sm text-on-surface font-bold truncate">
              {{ userName ? userName.split(' (')[0] : 'Elena Morales' }}
            </span>
            <span class="font-label-sm text-label-sm text-on-surface-variant">Anfitriona</span>
          </div>
        </div>
        <button
          type="button"
          class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-surface-container-high text-on-surface-variant hover:text-on-surface transition-all cursor-pointer"
          title="Cerrar sesión"
          aria-label="Cerrar sesión"
          @click="emit('logout')"
        >
          <span class="material-symbols-outlined text-[20px]">logout</span>
        </button>
      </div>
    </div>
  </aside>
</template>
