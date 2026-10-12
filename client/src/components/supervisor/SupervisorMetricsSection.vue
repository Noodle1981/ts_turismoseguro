<script setup lang="ts">
import type { DocketMetrics } from '../../composables/supervisor/useSupervisorDictamenes'

defineProps<{
  metrics: DocketMetrics
}>()
</script>

<template>
  <section class="w-full px-4 sm:px-6 lg:px-8 py-5">
    <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <!-- KPI 1: Pendientes de Dictamen -->
      <div class="p-5 rounded-2xl bg-surface-container-lowest border border-surface-container shadow-xs flex flex-col justify-between">
        <div class="flex items-start justify-between">
          <div class="flex flex-col">
            <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider font-bold">
              Pendientes de Dictamen
            </span>
            <div class="flex items-baseline gap-2 mt-2">
              <span class="font-headline-xl text-headline-xl text-primary font-extrabold leading-none">
                {{ metrics.pending }}
              </span>
              <span
                v-if="metrics.pending > 0"
                class="font-label-sm text-label-sm text-amber-800 dark:text-amber-300 bg-amber-500/20 px-2 py-0.5 rounded-full font-bold"
              >
                En proceso
              </span>
            </div>
          </div>
          <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-primary">
            <span class="material-symbols-outlined text-[22px]">pending_actions</span>
          </div>
        </div>
        <div class="mt-4 flex items-center gap-1.5 font-label-sm text-label-sm text-on-surface-variant">
          <span class="material-symbols-outlined text-[15px] text-secondary">schedule</span>
          <span>Calculado en vivo desde base de datos</span>
        </div>
      </div>

      <!-- KPI 2: Aprobadas & Publicadas -->
      <div class="p-5 rounded-2xl bg-surface-container-lowest border border-surface-container shadow-xs flex flex-col justify-between">
        <div class="flex items-start justify-between">
          <div class="flex flex-col">
            <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider font-bold">
              Aprobadas &amp; Publicadas
            </span>
            <div class="flex items-baseline gap-2 mt-2">
              <span class="font-headline-xl text-headline-xl text-secondary font-extrabold leading-none">
                {{ metrics.approved }}
              </span>
              <span class="font-label-sm text-label-sm text-secondary bg-secondary-container/40 px-2 py-0.5 rounded-full font-bold">
                Con Sello QR
              </span>
            </div>
          </div>
          <div class="w-10 h-10 rounded-xl bg-secondary-container/30 flex items-center justify-center text-secondary">
            <span class="material-symbols-outlined text-[22px]">verified</span>
          </div>
        </div>
        <div class="mt-4 flex items-center gap-1.5 font-label-sm text-label-sm text-on-surface-variant">
          <span class="material-symbols-outlined text-[15px] text-secondary">account_balance</span>
          <span>Propiedades activas en catálogo</span>
        </div>
      </div>

      <!-- KPI 3: En Subsanación / Modificaciones -->
      <div class="p-5 rounded-2xl bg-surface-container-lowest border border-surface-container shadow-xs flex flex-col justify-between">
        <div class="flex items-start justify-between">
          <div class="flex flex-col">
            <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider font-bold">
              En Subsanación
            </span>
            <div class="flex items-baseline gap-2 mt-2">
              <span class="font-headline-xl text-headline-xl text-on-tertiary-container font-extrabold leading-none">
                {{ metrics.inProgress }}
              </span>
              <span class="font-label-sm text-label-sm text-on-tertiary-container bg-tertiary-fixed/60 px-2 py-0.5 rounded-full font-bold">
                Plazo activo
              </span>
            </div>
          </div>
          <div class="w-10 h-10 rounded-xl bg-tertiary-fixed/40 flex items-center justify-center text-on-tertiary-container">
            <span class="material-symbols-outlined text-[22px]">rule_folder</span>
          </div>
        </div>
        <div class="mt-4 flex items-center gap-1.5 font-label-sm text-label-sm text-on-surface-variant">
          <span class="material-symbols-outlined text-[15px] text-on-tertiary-container">hourglass_top</span>
          <span>Plazo reglamentario: 48 / 72 hs</span>
        </div>
      </div>

      <!-- KPI 4: Rechazos Ratificados -->
      <div class="p-5 rounded-2xl bg-surface-container-lowest border border-surface-container shadow-xs flex flex-col justify-between">
        <div class="flex items-start justify-between">
          <div class="flex flex-col">
            <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider font-bold">
              Rechazos Ratificados
            </span>
            <div class="flex items-baseline gap-2 mt-2">
              <span class="font-headline-xl text-headline-xl text-error font-extrabold leading-none">
                {{ metrics.rejected }}
              </span>
              <span class="font-label-sm text-label-sm text-error bg-error-container/50 px-2 py-0.5 rounded-full font-bold">
                Cierre definitivo
              </span>
            </div>
          </div>
          <div class="w-10 h-10 rounded-xl bg-error-container/30 flex items-center justify-center text-error">
            <span class="material-symbols-outlined text-[22px]">gavel</span>
          </div>
        </div>
        <div class="mt-4 flex items-center gap-1.5 font-label-sm text-label-sm text-on-surface-variant">
          <span class="material-symbols-outlined text-[15px] text-error">block</span>
          <span>Inconsistencia o riesgo crítico</span>
        </div>
      </div>
    </div>
  </section>
</template>
