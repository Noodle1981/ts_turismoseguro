<script setup lang="ts">
import type { PropietarioOferta } from '../../../types/propietario'

defineProps<{
  selectedOffer: PropietarioOferta
  selectedMonth: number
  selectedYear: number
  availableMonths: Array<{ value: number; label: string }>
  availableYears: number[]
}>()

const emit = defineEmits<{
  (e: 'update:selectedMonth', value: number): void
  (e: 'update:selectedYear', value: number): void
}>()
</script>

<template>
  <header
    class="fixed top-0 left-72 right-0 h-16 bg-surface/85 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)] z-40 flex items-center justify-between px-space-lg border-b border-surface-container"
  >
    <div class="flex items-center gap-space-md">
      <div class="flex items-center gap-space-xs text-on-surface-variant font-label-sm text-label-sm">
        <span class="material-symbols-outlined text-[16px] text-tertiary">pin_drop</span>
        <span class="font-semibold text-primary">{{ selectedOffer.department }}</span>
      </div>
    </div>

    <div class="flex items-center gap-space-md">
      <!-- Filtro de Mes y Año Rápido -->
      <div
        class="hidden md:flex items-center gap-1.5 bg-surface-container-low px-2.5 py-1 rounded-lg border border-surface-container"
      >
        <span class="material-symbols-outlined text-primary text-[16px]">event</span>
        <select
          :value="selectedMonth"
          @change="emit('update:selectedMonth', Number(($event.target as HTMLSelectElement).value))"
          aria-label="Seleccionar mes"
          class="bg-transparent text-primary text-xs font-bold focus:outline-none cursor-pointer"
        >
          <option v-for="m in availableMonths" :key="m.value" :value="m.value">{{ m.label }}</option>
        </select>
        <select
          :value="selectedYear"
          @change="emit('update:selectedYear', Number(($event.target as HTMLSelectElement).value))"
          aria-label="Seleccionar año"
          class="bg-transparent text-primary text-xs font-bold focus:outline-none cursor-pointer"
        >
          <option v-for="y in availableYears" :key="y" :value="y">{{ y }}</option>
        </select>
      </div>


      <button
        type="button"
        class="w-9 h-9 rounded-lg flex items-center justify-center hover:bg-surface-container-high text-on-surface-variant hover:text-on-surface transition-all cursor-pointer relative"
        title="Notificaciones"
        aria-label="Notificaciones"
      >
        <span class="material-symbols-outlined text-[20px]">notifications</span>
        <span class="w-2 h-2 rounded-full bg-secondary absolute top-2 right-2"></span>
      </button>

      <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-on-primary">
        <span class="material-symbols-outlined text-[18px]">person</span>
      </div>
    </div>
  </header>
</template>
