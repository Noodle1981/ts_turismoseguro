<script setup lang="ts">
import type { RevalidacionPayload } from '../../../types/propietario'

defineProps<{
  revalidacionForm: RevalidacionPayload
}>()

const emit = defineEmits<{
  (e: 'submit'): void
}>()
</script>

<template>
  <section
    id="revalidacion-in-situ"
    class="lg:col-span-4 bg-surface-container-low rounded-xl p-space-lg shadow-sm flex flex-col justify-between border border-surface-container scroll-mt-20"
  >
    <div class="flex flex-col gap-space-md">
      <div class="flex items-center gap-space-xs">
        <div class="w-10 h-10 rounded-lg bg-secondary/20 flex items-center justify-center shrink-0">
          <span class="material-symbols-outlined text-secondary">fact_check</span>
        </div>
        <div>
          <h2 class="font-headline-sm text-headline-sm text-primary font-bold">Re-Validación In Situ</h2>
          <span class="font-label-sm text-label-sm text-on-surface-variant">Validación Oficial</span>
        </div>
      </div>

      <p class="font-body-sm text-body-sm text-on-surface-variant">
        ¿Realizó ampliaciones, instaló nuevo cerco en pileta o pintó el quincho? Solicite la visita presencial del
        validador para certificar y renovar las fotos oficiales en la plataforma.
      </p>

      <form class="flex flex-col gap-space-sm" @submit.prevent="emit('submit')">
        <div>
          <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">
            Motivo principal de la visita
          </label>
          <select
            v-model="revalidacionForm.motivo"
            class="w-full px-3 py-2 rounded-lg bg-surface-container-lowest text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container-highest border border-surface-container"
          >
            <option value="fotos">Actualización de fotos oficiales (Pintura / Quincho)</option>
            <option value="pileta">Nuevo cerco perimetral de pileta reglamentario</option>
            <option value="sanitarios">Ampliación de baños / Nuevos sanitarios</option>
            <option value="electrico">Recertificación de disyuntores y seguridad eléctrica</option>
            <option value="aforo">Solicitud de aumento de aforo homologado</option>
          </select>
        </div>

        <div>
          <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">
            Fecha tentativa preferida
          </label>
          <input
            v-model="revalidacionForm.fecha"
            type="date"
            class="w-full px-3 py-2 rounded-lg bg-surface-container-lowest text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container-highest border border-surface-container"
          />
        </div>

        <div>
          <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">
            Notas para el Validador Asignado
          </label>
          <textarea
            v-model="revalidacionForm.notas"
            rows="3"
            placeholder="Ej: Se instaló traba magnética en portón de pileta y cerámicas atérmicas nuevas..."
            class="w-full p-2.5 rounded-lg bg-surface-container-lowest text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container-highest resize-none border border-surface-container"
          ></textarea>
        </div>

        <button
          type="submit"
          class="w-full mt-space-xs inline-flex items-center justify-center gap-space-xs px-space-md py-space-sm rounded-lg bg-secondary text-on-secondary font-label-lg text-label-lg hover:bg-on-secondary-container transition-all shadow-sm font-bold cursor-pointer"
        >
          <span class="material-symbols-outlined text-[20px]">assignment_turned_in</span>
          Solicitar Turno con Validador
        </button>
      </form>
    </div>

    <div
      class="mt-space-md pt-space-sm text-on-surface-variant font-label-sm text-label-sm flex items-center gap-1.5 border-t border-surface-container"
    >
      <span class="material-symbols-outlined text-secondary text-[16px]">verified</span>
      Sin costo adicional para propietarios con Póliza Activa.
    </div>
  </section>
</template>
