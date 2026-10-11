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
    class="bg-surface-container-lowest rounded-xl p-space-md shadow-xs flex flex-col gap-space-sm border border-surface-container scroll-mt-20"
  >
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-space-sm">
      <div class="flex flex-col gap-0.5">
        <div class="flex items-center gap-space-sm flex-wrap">
          <h1 class="font-headline-md text-headline-md text-primary tracking-tight font-extrabold">
            {{ selectedOffer.title }}
          </h1>
          <span
            class="px-2 py-0.5 rounded-md bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-mono font-bold"
          >
            {{ selectedOffer.legajo }}
          </span>
          <span
            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-surface-container-high text-on-surface font-label-sm text-label-sm font-semibold"
          >
            <span class="material-symbols-outlined text-[14px] text-secondary">health_and_safety</span>
            {{ selectedOffer.poliza }}
          </span>
        </div>

        <div class="flex items-center gap-space-md text-on-surface-variant font-body-sm text-body-sm flex-wrap">
          <span class="inline-flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px] text-tertiary">location_on</span>
            {{ selectedOffer.location }}
          </span>
          <span class="inline-flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px] text-secondary">verified_user</span>
            Última validación presencial: {{ selectedOffer.actaFecha }}
          </span>
        </div>
      </div>

      <div class="flex items-center gap-space-xs flex-wrap">
        <!-- Botón Especial Eventos Provinciales -->
        <button
          v-if="currentMonthEvents.length > 0"
          type="button"
          class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-purple-700 hover:bg-purple-800 text-white font-label-md text-label-md transition-all shadow-xs font-bold cursor-pointer"
          title="Configurar tarifas especiales y pernocte para la Fiesta Nacional del Sol, Villicum o eventos masivos"
          @click="emit('openSpecialEvents')"
        >
          <span class="material-symbols-outlined text-[18px]">festival</span>
          Reservar Eventos Especiales
        </button>

        <a
          href="#calendario"
          class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-surface-container-low text-primary font-label-md text-label-md hover:bg-surface-container transition-colors font-bold border border-surface-container"
        >
          <span class="material-symbols-outlined text-[18px]">calendar_month</span>
          Ver Calendario
        </a>
      </div>
    </div>

    <!-- KEY METRICS COMPACT GRID (3 CARDS) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-space-sm">
      <!-- Metric 1: Alquileres -->
      <div
        class="bg-surface-container-low rounded-lg p-space-sm px-space-md flex flex-col justify-between border border-surface-container"
      >
        <div class="flex items-center justify-between">
          <span class="font-label-sm text-label-sm text-on-surface-variant font-bold">Jornadas Este Mes</span>
          <span
            class="w-7 h-7 rounded-md bg-surface-container-highest flex items-center justify-center text-primary"
          >
            <span class="material-symbols-outlined text-[17px]">event_available</span>
          </span>
        </div>
        <div class="my-1 flex items-baseline gap-1">
          <span class="font-headline-md text-headline-md text-primary font-bold">{{ currentMetrics.jornadas }}</span>
          <span class="font-body-sm text-body-sm text-on-surface-variant">confirmadas</span>
        </div>
        <div class="flex items-center gap-1 text-secondary font-label-sm text-label-sm font-bold">
          <span class="material-symbols-outlined text-[14px]">trending_up</span>
          <span>{{ currentMetrics.jornadasDiff }}</span>
        </div>
      </div>

      <!-- Metric 2: Ocupación -->
      <div
        class="bg-surface-container-low rounded-lg p-space-sm px-space-md flex flex-col justify-between border border-surface-container"
      >
        <div class="flex items-center justify-between">
          <span class="font-label-sm text-label-sm text-on-surface-variant font-bold">Tasa de Ocupación</span>
          <span
            class="w-7 h-7 rounded-md bg-surface-container-highest flex items-center justify-center text-primary"
          >
            <span class="material-symbols-outlined text-[17px]">pie_chart</span>
          </span>
        </div>
        <div class="my-1 flex items-baseline gap-1">
          <span class="font-headline-md text-headline-md text-primary font-bold">{{ currentMetrics.ocupacion }}%</span>
          <span class="font-body-sm text-body-sm text-on-surface-variant">fines de semana</span>
        </div>
        <div class="w-full bg-surface-container-highest h-1.5 rounded-full overflow-hidden">
          <div
            class="bg-secondary h-full rounded-full transition-all duration-500"
            :style="{ width: `${currentMetrics.ocupacion}%` }"
          ></div>
        </div>
      </div>

      <!-- Metric 3: Calificación -->
      <div
        class="bg-surface-container-low rounded-lg p-space-sm px-space-md flex flex-col justify-between border border-surface-container"
      >
        <div class="flex items-center justify-between">
          <span class="font-label-sm text-label-sm text-on-surface-variant font-bold">Reputación Verificada</span>
          <span
            class="w-7 h-7 rounded-md bg-tertiary-fixed flex items-center justify-center text-tertiary-container"
          >
            <span class="material-symbols-outlined text-[17px]" style="font-variation-settings: 'FILL' 1">star</span>
          </span>
        </div>
        <div class="my-1 flex items-baseline gap-1">
          <span class="font-headline-md text-headline-md text-primary font-bold">
            {{ currentMetrics.resenasCount > 0 ? currentMetrics.reputacion : '0.0' }}
          </span>
          <span class="font-body-sm text-body-sm text-on-surface-variant">/ 5.0</span>
        </div>
        <div class="text-on-surface-variant font-label-sm text-label-sm truncate">
          <span v-if="currentMetrics.resenasCount > 0">
            <span class="font-bold text-primary">{{ currentMetrics.resenasCount }} reseñas</span> con DNI validado
          </span>
          <span v-else class="text-on-surface-variant italic">0 reseñas registradas</span>
        </div>
      </div>
    </div>
  </section>
</template>
