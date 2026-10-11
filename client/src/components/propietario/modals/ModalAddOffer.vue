<script setup lang="ts">
import type { NewOfferPayload } from '../../../types/propietario'

defineProps<{
  modelValue: boolean
  newOffer: NewOfferPayload
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void
  (e: 'submit'): void
}>()

const close = () => {
  emit('update:modelValue', false)
}
</script>

<template>
  <div
    v-if="modelValue"
    class="fixed inset-0 z-50 bg-primary/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto"
    @click.self="close"
  >
    <div
      class="bg-surface-container-lowest rounded-xl shadow-xl max-w-xl w-full p-space-lg flex flex-col gap-space-md my-8 border border-surface-container animate-in fade-in duration-200"
    >
      <div class="flex items-center justify-between pb-space-sm border-b border-surface-container">
        <div class="flex items-center gap-space-xs">
          <div class="w-10 h-10 rounded-lg bg-primary text-on-primary flex items-center justify-center">
            <span class="material-symbols-outlined text-secondary-fixed">add_business</span>
          </div>
          <div>
            <h3 class="font-headline-sm text-headline-sm text-primary font-bold">Agregar Nueva Oferta</h3>
            <p class="text-xs text-on-surface-variant">Publicá tu quinta o cabaña con inspección in situ oficial</p>
          </div>
        </div>
        <button
          type="button"
          aria-label="Cerrar modal de nueva oferta"
          class="w-8 h-8 rounded-lg hover:bg-surface-container text-on-surface-variant flex items-center justify-center cursor-pointer"
          @click="close"
        >
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <form class="flex flex-col gap-space-md" @submit.prevent="emit('submit')">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-sm">
          <div class="sm:col-span-2">
            <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">
              Nombre del Predio / Quinta
            </label>
            <input
              v-model="newOffer.title"
              type="text"
              required
              placeholder="Ej: Quinta Los Olivos del Médano"
              class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container"
            />
          </div>

          <div>
            <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">Departamento</label>
            <select
              v-model="newOffer.department"
              class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container"
            >
              <option value="Rawson, San Juan">Rawson (Médano de Oro)</option>
              <option value="Pocito, San Juan">Pocito (Quinto Cuartel)</option>
              <option value="Zonda, San Juan">Zonda (Sierras Azules)</option>
              <option value="Ullum, San Juan">Ullum (Lago)</option>
              <option value="Rivadavia, San Juan">Rivadavia</option>
            </select>
          </div>

          <div>
            <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">Capacidad Aforo</label>
            <input
              v-model.number="newOffer.capacity"
              type="number"
              min="5"
              max="120"
              class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container"
            />
          </div>

          <div class="sm:col-span-2">
            <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">
              Dirección / Callejón
            </label>
            <input
              v-model="newOffer.location"
              type="text"
              required
              placeholder="Ej: Calle 11 s/n entre Ruta 40 y Vidart"
              class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container"
            />
          </div>

          <div class="sm:col-span-2">
            <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">
              Observaciones / Detalle del lugar
            </label>
            <textarea
              v-model="newOffer.observations"
              rows="3"
              placeholder="Detalles sobre las instalaciones, comodidades, accesos o recomendaciones del predio..."
              class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container resize-none"
            ></textarea>
          </div>
        </div>

        <div
          class="p-space-sm rounded-lg bg-secondary-container/40 border border-secondary/20 flex items-center gap-space-xs text-xs text-on-surface"
        >
          <span class="material-symbols-outlined text-secondary text-[18px]">verified</span>
          <span>Al publicar se coordinará automáticamente la visita técnica in situ para homologar el predio.</span>
        </div>

        <div class="flex items-center justify-end gap-space-sm pt-space-xs border-t border-surface-container">
          <button
            type="button"
            class="px-space-md py-space-sm rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-lg text-label-lg transition-colors cursor-pointer font-semibold"
            @click="close"
          >
            Cancelar
          </button>
          <button
            type="submit"
            class="inline-flex items-center gap-space-xs px-space-lg py-space-sm rounded-lg bg-secondary text-on-secondary font-label-lg text-label-lg hover:bg-on-secondary-container transition-all shadow-sm font-bold cursor-pointer"
          >
            <span class="material-symbols-outlined text-[18px]">add_circle</span>
            Guardar y Solicitar Inspección
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
