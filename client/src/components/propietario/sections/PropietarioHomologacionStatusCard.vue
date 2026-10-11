<script setup lang="ts">
import { computed } from 'vue'
import type { PropietarioOferta } from '../../../types/propietario'

const props = defineProps<{
  offer: PropietarioOferta
}>()

const isPending = computed(() => {
  return props.offer.publicationStatus === 'draft' ||
    props.offer.verificationStatus === 'unverified' ||
    props.offer.verificationStatus === 'pending'
})

const vr = computed(() => props.offer.verificationRequest)

const isAssigned = computed(() => {
  return vr.value?.status === 'assigned' || !!vr.value?.validator
})

const validator = computed(() => vr.value?.validator)

const token = computed(() => vr.value?.securityToken || 'TS-PENDIENTE')

const copyToken = async () => {
  if (token.value && token.value !== 'TS-PENDIENTE') {
    try {
      await navigator.clipboard.writeText(token.value)
      alert(`Token de seguridad copiado al portapapeles: ${token.value}`)
    } catch {
      // fallback
    }
  }
}
</script>

<template>
  <div v-if="isPending" class="flex flex-col gap-4">
    <!-- BANNER PRINCIPAL DE ESTADO DEL TRÁMITE -->
    <div
      class="rounded-2xl p-5 sm:p-6 border transition-all shadow-xs relative overflow-hidden"
      :class="isAssigned
        ? 'bg-gradient-to-r from-emerald-50 via-surface-container-lowest to-surface-container-lowest dark:from-emerald-950/30 border-secondary/40'
        : 'bg-gradient-to-r from-amber-50 via-surface-container-lowest to-surface-container-lowest dark:from-amber-950/30 border-amber-500/40'"
    >
      <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
        
        <!-- Estado e Info -->
        <div class="flex items-start gap-4 max-w-3xl">
          <div
            class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 shadow-xs"
            :class="isAssigned ? 'bg-secondary text-on-secondary' : 'bg-amber-500 text-amber-950'"
          >
            <span class="material-symbols-outlined text-[28px]">
              {{ isAssigned ? 'contact_phone' : 'hourglass_top' }}
            </span>
          </div>

          <div class="flex flex-col gap-1.5">
            <div class="flex flex-wrap items-center gap-2">
              <span
                class="px-2.5 py-0.5 rounded-full font-label-sm text-label-sm uppercase tracking-wider font-extrabold"
                :class="isAssigned
                  ? 'bg-secondary-fixed text-on-secondary-fixed'
                  : 'bg-amber-500/20 text-amber-800 dark:text-amber-300'"
              >
                {{ isAssigned ? 'Validador Designado' : 'Esperando asignación de validador cercano' }}
              </span>
              <span class="text-xs font-mono font-bold text-on-surface-variant">
                Trámite #{{ vr?.id ?? offer.id }}-TS/26
              </span>
            </div>

            <h2 class="font-headline-md text-headline-md text-primary font-bold">
              {{ isAssigned
                ? 'El validador matriculado se comunicará con usted'
                : 'Solicitud enviada a la Mesa de Supervisores de San Juan' }}
            </h2>

            <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
              <template v-if="isAssigned">
                Para garantizar la seguridad de su predio y de los inquilinos, la visita técnica in situ será realizada por el inspector oficial detallado abajo. <strong>Al contactarlo, el validador deberá informarle su Token de Seguridad</strong> para verificar su identidad antes del ingreso.
              </template>
              <template v-else>
                Estamos asignando el inspector oficial más cercano en el departamento <strong>{{ offer.department }}</strong>. Una vez comisionado, verá aquí su credencial oficial, matrícula y datos de contacto directo.
              </template>
            </p>
          </div>
        </div>

        <!-- Token de Seguridad Oficial Box -->
        <div class="bg-surface-container-lowest p-4 rounded-xl border border-surface-container flex flex-col items-center justify-center text-center gap-1.5 shrink-0 min-w-[220px] shadow-sm">
          <div class="flex items-center gap-1 font-label-sm text-label-sm text-on-surface-variant uppercase font-bold">
            <span class="material-symbols-outlined text-[16px] text-secondary">verified_user</span>
            <span>Token Antifraude</span>
          </div>
          <div class="font-mono text-2xl font-black tracking-wider text-primary py-1 px-3 bg-surface-container rounded-lg border border-surface-container-high">
            {{ token }}
          </div>
          <p class="text-[11px] text-on-surface-variant max-w-[200px] leading-tight">
            El validador debe mencionarle este código al coordinar la visita.
          </p>
          <button
            type="button"
            class="text-[11px] font-bold text-secondary hover:underline cursor-pointer pt-1 flex items-center gap-1"
            @click="copyToken"
          >
            <span class="material-symbols-outlined text-[14px]">content_copy</span>
            <span>Copiar Token</span>
          </button>
        </div>

      </div>

      <!-- DETALLE DEL VALIDADOR ASIGNADO SI YA ESTÁ COMISIONADO -->
      <div
        v-if="isAssigned && validator"
        class="mt-5 pt-4 border-t border-secondary/20 grid grid-cols-1 md:grid-cols-3 gap-4 bg-surface-container-lowest/80 p-4 rounded-xl border"
      >
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-sm shrink-0">
            {{ validator.name.charAt(0) }}
          </div>
          <div class="flex flex-col">
            <span class="text-[11px] uppercase tracking-wider text-on-surface-variant font-bold">Inspector Matriculado</span>
            <span class="font-headline-sm text-headline-sm text-primary font-bold">{{ validator.name }}</span>
            <span class="text-xs text-on-surface-variant">Radicado en: {{ validator.origin_city ?? offer.department }}</span>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-primary shrink-0">
            <span class="material-symbols-outlined text-[20px]">badge</span>
          </div>
          <div class="flex flex-col">
            <span class="text-[11px] uppercase tracking-wider text-on-surface-variant font-bold">Documentación & Matrícula</span>
            <span class="text-xs font-bold text-on-surface">DNI {{ validator.dni ?? 'Oficial Habilitado' }}</span>
            <span class="text-xs text-secondary font-semibold">Credencial Activa San Juan Verificado</span>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-emerald-500/15 text-emerald-700 flex items-center justify-center shrink-0">
            <span class="material-symbols-outlined text-[20px]">call</span>
          </div>
          <div class="flex flex-col">
            <span class="text-[11px] uppercase tracking-wider text-on-surface-variant font-bold">Contacto Oficial</span>
            <span class="text-xs font-bold text-primary">{{ validator.phone }}</span>
            <a
              :href="`https://wa.me/${validator.phone.replace(/[^0-9]/g, '')}?text=Hola%20${encodeURIComponent(validator.name)},%20te%20contacto%20por%20la%20verificaci%C3%B3n%20de%20${encodeURIComponent(offer.title)}`"
              target="_blank"
              class="text-xs text-secondary hover:underline font-bold flex items-center gap-1 pt-0.5"
            >
              <span class="material-symbols-outlined text-[14px]">chat</span> Enviar WhatsApp al Inspector
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- FICHA TÉCNICA DECLARADA POR EL PROPIETARIO PARA AUDITORÍA IN SITU -->
    <div class="bg-surface-container-lowest rounded-xl p-5 sm:p-6 border border-surface-container shadow-xs flex flex-col gap-4">
      <div class="flex items-center justify-between pb-3 border-b border-surface-container">
        <div class="flex items-center gap-2">
          <span class="material-symbols-outlined text-primary text-[20px]">checklist</span>
          <h3 class="font-headline-sm text-headline-sm text-primary font-bold">
            Datos Declarados para Constatación Presencial
          </h3>
        </div>
        <span class="px-2.5 py-0.5 rounded-full bg-surface-container text-on-surface-variant text-xs font-semibold">
          Aforo declarado: {{ offer.capacity ?? 40 }} personas
        </span>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Tarjeta 1: Aforo y Ubicación -->
        <div class="p-4 rounded-xl bg-surface-container-low border border-surface-container flex flex-col gap-2">
          <span class="text-[11px] uppercase tracking-wider text-on-surface-variant font-bold">Ubicación y Departamento</span>
          <span class="font-body-md text-body-md text-primary font-bold flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[18px] text-tertiary">location_on</span>
            {{ offer.location }}
          </span>
          <span class="text-xs text-on-surface-variant">Departamento: {{ offer.department }}</span>
          <span class="text-xs text-on-surface-variant">Tipo de Inmueble: {{ offer.subtitle }}</span>
        </div>

        <!-- Tarjeta 2: Fotos & Fachada -->
        <div class="p-4 rounded-xl bg-surface-container-low border border-surface-container flex flex-col gap-2">
          <span class="text-[11px] uppercase tracking-wider text-on-surface-variant font-bold">Registro Fotográfico Inicial</span>
          <div class="flex items-center gap-3">
            <img :src="offer.image" :alt="offer.title" class="w-16 h-12 rounded-lg object-cover border border-surface-container shrink-0" />
            <div class="flex flex-col">
              <span class="text-xs font-bold text-primary">Foto Principal Declarada</span>
              <span class="text-[11px] text-on-surface-variant">El inspector tomará fotos geolocalizadas durante la visita.</span>
            </div>
          </div>
        </div>

        <!-- Tarjeta 3: Seguridad Declarada -->
        <div class="p-4 rounded-xl bg-surface-container-low border border-surface-container flex flex-col gap-2">
          <span class="text-[11px] uppercase tracking-wider text-on-surface-variant font-bold">Instalaciones a Inspeccionar</span>
          <div class="flex flex-wrap gap-1.5">
            <span
              v-for="amenity in offer.amenities"
              :key="amenity.label"
              class="px-2 py-0.5 rounded-full text-[11px] font-semibold border"
              :class="amenity.isSecurityFeature
                ? 'bg-secondary-fixed/40 text-on-secondary-fixed border-secondary/30'
                : 'bg-surface-container text-on-surface border-surface-container'"
            >
              {{ amenity.label }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
