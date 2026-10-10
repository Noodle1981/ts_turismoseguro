<script setup lang="ts">
defineProps<{
  auditorName: string
  auditorCode: string
  inspectionDate: string
}>()
</script>

<template>
  <div class="bg-primary text-on-primary p-space-lg rounded-2xl shadow-xl relative overflow-hidden">
    <!-- Watermark shield icon -->
    <div class="absolute -right-8 -bottom-8 opacity-10 pointer-events-none">
      <span class="material-symbols-outlined text-[200px]">verified</span>
    </div>

    <div class="relative z-10">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-space-sm mb-space-md pb-space-sm border-b border-on-primary/10">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-xl bg-secondary flex items-center justify-center text-on-secondary shadow-md">
            <span class="material-symbols-outlined text-[28px]">policy</span>
          </div>
          <div>
            <span class="font-label-sm text-label-sm text-secondary-fixed uppercase tracking-wider block font-bold">
              Auditoría Presencial Oficial
            </span>
            <h3 class="font-headline-sm text-headline-sm text-on-primary font-bold">
              Informe del Auditor TS San Juan
            </h3>
          </div>
        </div>

        <div class="bg-primary-container px-3 py-1.5 rounded-lg text-left sm:text-right">
          <span class="font-label-sm text-label-sm text-on-primary-container block font-medium">Auditor Matriculado</span>
          <span class="font-label-md text-label-md text-on-primary font-bold">{{ auditorName }} ({{ auditorCode }})</span>
        </div>
      </div>

      <p class="font-body-sm text-body-sm text-on-primary/80 mb-space-md font-medium">
        Esta propiedad fue inspeccionada físicamente por nuestro equipo técnico presencial (Inspección: {{ inspectionDate }}). Se cotejó la titularidad real de la tierra, la veracidad de las instalaciones y los datos bancarios donde se transfieren las señas.
      </p>

      <!-- Checklist Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-sm mb-space-md">
        <!-- Item A -->
        <div class="flex items-start gap-3 p-3 rounded-xl bg-primary-container/60 backdrop-blur-sm">
          <div class="w-7 h-7 rounded-full bg-secondary-fixed flex items-center justify-center text-on-secondary-fixed shrink-0 mt-0.5">
            <span class="material-symbols-outlined text-[16px] font-bold">check</span>
          </div>
          <div>
            <span class="font-label-md text-label-md text-on-primary font-bold block">Agua de Pileta Inspeccionada</span>
            <span class="font-body-sm text-body-sm text-on-primary/70 text-xs">Clorado correcto, filtros en funcionamiento diario, fondo limpio.</span>
          </div>
        </div>

        <!-- Item B -->
        <div class="flex items-start gap-3 p-3 rounded-xl bg-primary-container/60 backdrop-blur-sm">
          <div class="w-7 h-7 rounded-full bg-secondary-fixed flex items-center justify-center text-on-secondary-fixed shrink-0 mt-0.5">
            <span class="material-symbols-outlined text-[16px] font-bold">check</span>
          </div>
          <div>
            <span class="font-label-md text-label-md text-on-primary font-bold block">Perímetro Cerrado y Seguro</span>
            <span class="font-body-sm text-body-sm text-on-primary/70 text-xs">Muros medianeros sin puntos ciegos, portón con cerrojo de seguridad.</span>
          </div>
        </div>

        <!-- Item C -->
        <div class="flex items-start gap-3 p-3 rounded-xl bg-primary-container/60 backdrop-blur-sm">
          <div class="w-7 h-7 rounded-full bg-secondary-fixed flex items-center justify-center text-on-secondary-fixed shrink-0 mt-0.5">
            <span class="material-symbols-outlined text-[16px] font-bold">check</span>
          </div>
          <div>
            <span class="font-label-md text-label-md text-on-primary font-bold block">CBU y Alias del Dueño Auditados</span>
            <span class="font-body-sm text-body-sm text-on-primary/70 text-xs">Cotejado con DNI original del titular para evitar triangulación de estafas.</span>
          </div>
        </div>

        <!-- Item D -->
        <div class="flex items-start gap-3 p-3 rounded-xl bg-primary-container/60 backdrop-blur-sm">
          <div class="w-7 h-7 rounded-full bg-secondary-fixed flex items-center justify-center text-on-secondary-fixed shrink-0 mt-0.5">
            <span class="material-symbols-outlined text-[16px] font-bold">check</span>
          </div>
          <div>
            <span class="font-label-md text-label-md text-on-primary font-bold block">Capacidad Eléctrica &amp; Matafuegos</span>
            <span class="font-body-sm text-body-sm text-on-primary/70 text-xs">Disyuntor diferencial operativo, matafuego reglamentario ABC vigente.</span>
          </div>
        </div>
      </div>

      <div class="p-3 rounded-xl bg-secondary text-on-secondary flex items-center gap-space-sm">
        <span class="material-symbols-outlined text-[24px]">verified</span>
        <span class="font-label-md text-label-md font-bold">
          Certificado Oficial TS expedido con hash de autenticación criptográfica QR.
        </span>
      </div>
    </div>
  </div>
</template>
