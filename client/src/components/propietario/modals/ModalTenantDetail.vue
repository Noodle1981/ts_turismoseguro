<script setup lang="ts">
import type { Tenant } from '../../../types/propietario'

defineProps<{
  modelValue: boolean
  selectedTenant: Tenant | null
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void
  (e: 'rate', payload: { tenant: Tenant; rating: 'up' | 'down' }): void
}>()

const close = () => {
  emit('update:modelValue', false)
}
</script>

<template>
  <div
    v-if="modelValue && selectedTenant"
    class="fixed inset-0 z-50 bg-primary/60 backdrop-blur-sm flex items-center justify-center p-4"
    @click.self="close"
  >
    <div
      class="bg-surface-container-lowest rounded-xl shadow-xl max-w-lg w-full p-space-lg flex flex-col gap-space-md border border-surface-container animate-in fade-in duration-200"
    >
      <div class="flex items-center justify-between pb-space-xs border-b border-surface-container">
        <div class="flex items-center gap-space-xs">
          <span class="material-symbols-outlined text-secondary text-[24px]">assignment_ind</span>
          <h3 class="font-headline-sm text-headline-sm text-primary font-bold">Ficha Integral del Inquilino</h3>
        </div>
        <button
          type="button"
          aria-label="Cerrar ficha del inquilino"
          class="w-8 h-8 rounded-lg hover:bg-surface-container text-on-surface-variant flex items-center justify-center cursor-pointer"
          @click="close"
        >
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <div
        class="p-space-md rounded-xl bg-surface-container-low flex items-center justify-between border border-surface-container"
      >
        <div>
          <h4 class="font-headline-sm text-headline-sm text-primary font-bold">{{ selectedTenant.name }}</h4>
          <span class="font-body-sm text-body-sm text-on-surface-variant">DNI {{ selectedTenant.dni }}</span>
        </div>
        <div class="text-right">
          <span
            class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full bg-secondary/15 text-secondary font-label-sm text-label-sm font-bold"
          >
            <span class="material-symbols-outlined text-[14px]">verified</span> Validado
          </span>
        </div>
      </div>

      <div class="space-y-space-xs font-body-sm text-body-sm text-on-surface">
        <div class="flex justify-between py-1 border-b border-surface-container">
          <span class="text-on-surface-variant">Fecha de ingreso:</span>
          <strong class="text-primary font-bold">{{ selectedTenant.date }}</strong>
        </div>
        <div class="flex justify-between py-1 border-b border-surface-container">
          <span class="text-on-surface-variant">Turno contratado:</span>
          <span class="text-primary">{{ selectedTenant.time }}</span>
        </div>
        <div class="flex justify-between py-1 border-b border-surface-container">
          <span class="text-on-surface-variant">Motivo del evento:</span>
          <span class="text-primary">{{ selectedTenant.eventType }}</span>
        </div>
        <div class="flex justify-between py-1 border-b border-surface-container">
          <span class="text-on-surface-variant">Cantidad asistentes:</span>
          <strong class="text-secondary font-bold">{{ selectedTenant.guestsText }}</strong>
        </div>
        <div class="flex justify-between py-1 border-b border-surface-container">
          <span class="text-on-surface-variant">Póliza asegurada:</span>
          <span class="text-secondary font-bold flex items-center gap-1">
            <span class="material-symbols-outlined text-[15px]">shield</span>
            {{ selectedTenant.insuranceBadge }}
          </span>
        </div>
        <div class="flex justify-between py-1 border-b border-surface-container">
          <span class="text-on-surface-variant">Estado de cobertura:</span>
          <span class="text-secondary font-bold flex items-center gap-1">
            <span class="material-symbols-outlined text-[15px]">verified_user</span>
            {{ selectedTenant.totalPrice ? 'Turno Finalizado & Póliza Rendida' : 'Cobertura Activa y Asistida' }}
          </span>
        </div>
      </div>

      <div class="p-space-sm rounded-lg bg-surface-container flex flex-col gap-1">
        <span class="font-label-sm text-label-sm text-on-surface font-bold">
          Historial en Red Turismo Seguro San Juan:
        </span>
        <p class="font-body-sm text-body-sm text-secondary font-semibold">
          {{ selectedTenant.history }}
        </p>
      </div>

      <!-- Criterio Anfitrión en la Ficha -->
      <div
        class="p-space-sm rounded-lg bg-surface-container-low border border-surface-container flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2"
      >
        <div>
          <span class="font-label-sm text-label-sm text-primary font-bold block">Tu Criterio como Anfitrión:</span>
          <span class="text-xs text-on-surface-variant">Calificación del inquilino para futuras reservas.</span>
        </div>
        <div class="flex items-center gap-1.5 shrink-0">
          <button
            type="button"
            @click="emit('rate', { tenant: selectedTenant, rating: 'up' })"
            class="px-2.5 py-1 rounded-lg text-xs font-bold flex items-center gap-1 transition-all cursor-pointer"
            :class="
              selectedTenant.ownerRating === 'up'
                ? 'bg-secondary text-on-secondary shadow-xs'
                : 'bg-surface-container text-on-surface-variant hover:text-secondary hover:bg-surface-container-high'
            "
          >
            <span class="material-symbols-outlined text-[16px]">thumb_up</span>
            <span>Buen Inquilino</span>
          </button>
          <button
            type="button"
            @click="emit('rate', { tenant: selectedTenant, rating: 'down' })"
            class="px-2.5 py-1 rounded-lg text-xs font-bold flex items-center gap-1 transition-all cursor-pointer"
            :class="
              selectedTenant.ownerRating === 'down'
                ? 'bg-error text-on-error shadow-xs'
                : 'bg-surface-container text-on-surface-variant hover:text-error hover:bg-surface-container-high'
            "
          >
            <span class="material-symbols-outlined text-[16px]">thumb_down</span>
            <span>No Conforme</span>
          </button>
        </div>
      </div>

      <div class="flex items-center justify-end gap-space-sm pt-space-xs">
        <button
          type="button"
          class="w-full py-space-sm rounded-lg bg-primary text-on-primary font-label-lg text-label-lg hover:bg-primary-container transition-colors font-bold cursor-pointer"
          @click="close"
        >
          Cerrar Ficha
        </button>
      </div>
    </div>
  </div>
</template>
