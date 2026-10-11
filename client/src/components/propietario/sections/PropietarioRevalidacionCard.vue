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
    class="lg:col-span-4 bg-surface-container-low rounded-xl p-space-md shadow-xs flex flex-col justify-between border border-surface-container scroll-mt-20"
  >
    <div class="flex flex-col gap-space-sm">
      <div class="flex items-center gap-space-xs">
        <div class="w-8 h-8 rounded-lg bg-secondary/20 flex items-center justify-center shrink-0">
          <span class="material-symbols-outlined text-secondary text-[20px]">fact_check</span>
        </div>
        <div>
          <h2 class="font-headline-sm text-headline-sm text-primary font-bold">Re-Validación In Situ</h2>
          <span class="font-label-sm text-label-sm text-on-surface-variant">Validación Oficial</span>
        </div>
      </div>

      <p class="font-body-sm text-body-sm text-on-surface-variant text-[13px] leading-snug">
        ¿Realizó ampliaciones, nuevo cerco o renovó el quincho? Solicite la visita presencial del
        validador para recertificar.
      </p>

      <form class="flex flex-col gap-2" @submit.prevent="emit('submit')">
        <div>
          <label class="block font-label-sm text-label-sm text-on-surface mb-0.5 font-semibold">
            Motivo principal de la visita
          </label>
          <select
            v-model="revalidacionForm.motivo"
            class="w-full px-2.5 py-1.5 rounded-lg bg-surface-container-lowest text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container-highest border border-surface-container text-xs"
          >
            <option value="fotos">Actualización de fotos oficiales (Pintura / Quincho)</option>
            <option value="pileta">Nuevo cerco perimetral de pileta reglamentario</option>
            <option value="sanitarios">Ampliación de baños / Nuevos sanitarios</option>
            <option value="electrico">Recertificación de disyuntores y seguridad eléctrica</option>
            <option value="aforo">Solicitud de aumento de aforo homologado</option>
          </select>
        </div>

        <div>
          <label class="block font-label-sm text-label-sm text-on-surface mb-0.5 font-semibold">
            Fecha tentativa preferida
          </label>
          <input
            v-model="revalidacionForm.fecha"
            type="date"
            class="w-full px-2.5 py-1.5 rounded-lg bg-surface-container-lowest text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container-highest border border-surface-container text-xs"
          />
        </div>

        <div>
          <label class="block font-label-sm text-label-sm text-on-surface mb-0.5 font-semibold">
            Notas para el Validador Asignado
          </label>
          <textarea
            v-model="revalidacionForm.notas"
            rows="2"
            placeholder="Ej: Se instaló traba magnética en portón de pileta y cerámicas atérmicas..."
            class="w-full p-2 rounded-lg bg-surface-container-lowest text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container-highest resize-none border border-surface-container text-xs"
          ></textarea>
        </div>

        <button
          type="submit"
          class="w-full mt-1 inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg bg-secondary text-on-secondary font-label-md text-label-md hover:bg-on-secondary-container transition-all shadow-xs font-bold cursor-pointer"
        >
          <span class="material-symbols-outlined text-[17px]">assignment_turned_in</span>
          Solicitar Turno con Validador
        </button>
      </form>
    </div>

    <div
      class="mt-2 pt-2 text-on-surface-variant font-label-sm text-label-sm flex items-center gap-1.5 border-t border-surface-container text-[11px]"
    >
      <span class="material-symbols-outlined text-secondary text-[15px]">verified</span>
      Sin costo adicional para propietarios con Validación Activa.
    </div>
  </section>
</template>
