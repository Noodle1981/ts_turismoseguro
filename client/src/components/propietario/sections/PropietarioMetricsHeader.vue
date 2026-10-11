<script setup lang="ts">
import type { PropietarioOferta, PropietarioMetrics, ProvincialEvent } from '../../../types/propietario'

defineProps<{
  selectedOffer: PropietarioOferta
  currentMetrics: PropietarioMetrics
  currentMonthEvents: ProvincialEvent[]
}>()

const emit = defineEmits<{
  (e: 'openSpecialEvents'): void
}>()
</script>

<template>
  <section
    id="resumen-general"
    class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm flex flex-col gap-space-lg border border-surface-container scroll-mt-20"
  >
    <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-space-md">
      <div class="flex flex-col gap-space-xs">
        <div class="flex items-center gap-space-sm flex-wrap">
          <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight font-extrabold">
            {{ selectedOffer.title }}
          </h1>
          <span
            class="px-space-xs py-0.5 rounded-md bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-mono font-bold"
          >
            {{ selectedOffer.legajo }}
          </span>
          <span
            class="inline-flex items-center gap-1 px-space-sm py-0.5 rounded-full bg-surface-container-high text-on-surface font-label-sm text-label-sm font-semibold"
          >
            <span class="material-symbols-outlined text-[15px] text-secondary">health_and_safety</span>
            {{ selectedOffer.poliza }}
          </span>
        </div>

        <div class="flex items-center gap-space-md text-on-surface-variant font-body-sm text-body-sm flex-wrap">
          <span class="inline-flex items-center gap-1">
            <span class="material-symbols-outlined text-[18px] text-tertiary">location_on</span>
            {{ selectedOffer.location }}
          </span>
          <span class="inline-flex items-center gap-1">
            <span class="material-symbols-outlined text-[18px] text-secondary">verified_user</span>
            Última validación presencial: {{ selectedOffer.actaFecha }}
          </span>
        </div>

      </div>

      <div class="flex items-center gap-space-sm flex-wrap">
        <!-- Botón Especial Eventos Provinciales -->
        <button
          v-if="currentMonthEvents.length > 0"
          type="button"
          class="inline-flex items-center gap-space-xs px-space-lg py-space-sm rounded-lg bg-purple-700 hover:bg-purple-800 text-white font-label-lg text-label-lg transition-all shadow-sm font-bold cursor-pointer animate-in fade-in duration-150"
          title="Configurar tarifas especiales y pernocte para la Fiesta Nacional del Sol, Villicum o eventos masivos"
          @click="emit('openSpecialEvents')"
        >
          <span class="material-symbols-outlined text-[20px]">festival</span>
          Reservar Eventos Especiales
        </button>

        <a
          href="#almanaque"
          class="inline-flex items-center gap-space-xs px-space-md py-space-sm rounded-lg bg-surface-container-low text-primary font-label-lg text-label-lg hover:bg-surface-container transition-colors font-bold border border-surface-container"
        >
          <span class="material-symbols-outlined text-[20px]">date_range</span>
          Ver Calendario
        </a>
      </div>
    </div>

    <!-- KEY METRICS GRID (3 CARDS) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-space-md">
      <!-- Metric 1: Alquileres -->
      <div
        class="bg-surface-container-low rounded-xl p-space-md flex flex-col justify-between border border-surface-container"
      >
        <div class="flex items-center justify-between">
          <span class="font-label-md text-label-md text-on-surface-variant font-bold">Jornadas Este Mes</span>
          <span
            class="w-8 h-8 rounded-lg bg-surface-container-highest flex items-center justify-center text-primary"
          >
            <span class="material-symbols-outlined text-[20px]">event_available</span>
          </span>
        </div>
        <div class="mt-space-sm">
          <span class="font-headline-lg text-headline-lg text-primary font-bold">{{ currentMetrics.jornadas }}</span>
          <span class="font-body-sm text-body-sm text-on-surface-variant ml-1">jornadas confirmadas</span>
        </div>
        <div class="mt-space-xs flex items-center gap-1 text-secondary font-label-sm text-label-sm font-bold">
          <span class="material-symbols-outlined text-[16px]">trending_up</span>
          <span>{{ currentMetrics.jornadasDiff }}</span>
        </div>
      </div>

      <!-- Metric 2: Ocupación -->
      <div
        class="bg-surface-container-low rounded-xl p-space-md flex flex-col justify-between border border-surface-container"
      >
        <div class="flex items-center justify-between">
          <span class="font-label-md text-label-md text-on-surface-variant font-bold">Tasa de Ocupación</span>
          <span
            class="w-8 h-8 rounded-lg bg-surface-container-highest flex items-center justify-center text-primary"
          >
            <span class="material-symbols-outlined text-[20px]">pie_chart</span>
          </span>
        </div>
        <div class="mt-space-sm flex items-baseline gap-2">
          <span class="font-headline-lg text-headline-lg text-primary font-bold">{{ currentMetrics.ocupacion }}%</span>
          <span class="font-body-sm text-body-sm text-on-surface-variant">de fines de semana</span>
        </div>
        <div class="mt-space-xs w-full bg-surface-container-highest h-2 rounded-full overflow-hidden">
          <div
            class="bg-secondary h-full rounded-full transition-all duration-500"
            :style="{ width: `${currentMetrics.ocupacion}%` }"
          ></div>
        </div>
      </div>

      <!-- Metric 3: Calificación -->
      <div
        class="bg-surface-container-low rounded-xl p-space-md flex flex-col justify-between border border-surface-container"
      >
        <div class="flex items-center justify-between">
          <span class="font-label-md text-label-md text-on-surface-variant font-bold">Reputación Verificada</span>
          <span
            class="w-8 h-8 rounded-lg bg-tertiary-fixed flex items-center justify-center text-tertiary-container"
          >
            <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1">star</span>
          </span>
        </div>
        <div class="mt-space-sm flex items-baseline gap-1">
          <span class="font-headline-lg text-headline-lg text-primary font-bold">
            {{ currentMetrics.resenasCount > 0 ? currentMetrics.reputacion : '0.0' }}
          </span>
          <span class="font-body-sm text-body-sm text-on-surface-variant">/ 5.0</span>
        </div>
        <div class="mt-space-xs text-on-surface-variant font-label-sm text-label-sm">
          <span v-if="currentMetrics.resenasCount > 0">
            Basado en <span class="font-bold text-primary">{{ currentMetrics.resenasCount }} reseñas</span> con DNI
            validado
          </span>
          <span v-else class="text-on-surface-variant italic">0 reseñas registradas en la base de datos</span>
        </div>
      </div>
    </div>
  </section>
</template>
