<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>

<template>
  <main class="w-full bg-surface min-h-[calc(100vh-80px)] py-8 px-4 sm:px-6 lg:px-12 flex flex-col gap-6">
    <div class="max-w-7xl mx-auto w-full flex flex-col gap-6">
      <!-- Header del Módulo -->
      <header class="bg-surface-container-lowest rounded-2xl p-6 sm:p-8 shadow-sm border border-surface-container flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex flex-col gap-2">
          <div class="flex items-center gap-2">
            <RouterLink
              to="/validador/bandeja"
              class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-surface-container hover:bg-surface-dim text-primary font-label-sm text-label-sm font-bold transition-colors"
            >
              <span class="material-symbols-outlined text-[16px]">arrow_back</span>
              Volver a Bandeja
            </RouterLink>
            <span class="px-2.5 py-0.5 rounded-full bg-primary text-on-primary font-label-sm text-label-sm uppercase tracking-wider font-bold">
              Geolocalización In Situ
            </span>
          </div>

          <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight font-extrabold">
            Mapa Territorial de Inspecciones
          </h1>
          <p class="font-body-md text-body-md text-on-surface-variant">
            Visualización geoespacial de propiedades asignadas, rutas de inspección por departamento y cobertura técnica en San Juan.
          </p>
        </div>

        <div class="flex items-center gap-2">
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-secondary-fixed/50 text-secondary font-label-md text-label-md font-bold">
            <span class="w-2.5 h-2.5 rounded-full bg-secondary animate-pulse"></span>
            GPS Terreno Activo
          </span>
        </div>
      </header>

      <!-- Placeholder amigable para el desarrollo posterior del mapa interactivo -->
      <section class="bg-surface-container-lowest rounded-2xl p-10 shadow-sm border border-surface-container flex flex-col items-center justify-center text-center gap-4 min-h-[460px] relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(#e5eeff_1px,transparent_1px)] [background-size:16px_16px] opacity-60 pointer-events-none"></div>

        <div class="w-16 h-16 rounded-2xl bg-surface-container-high text-primary flex items-center justify-center shadow-xs z-10">
          <span class="material-symbols-outlined text-[36px] text-secondary">explore</span>
        </div>

        <div class="max-w-md flex flex-col gap-2 z-10">
          <h2 class="font-headline-md text-headline-md text-primary font-bold">
            Módulo de Cartografía &amp; Ruteo GPS
          </h2>
          <p class="font-body-md text-body-md text-on-surface-variant">
            Aquí se integrará la vista satelital y mapa de calor con los 19 departamentos (Pocito, Zonda, Ullum, Rawson, Rivadavia), pines de solicitudes pendientes y cálculo de itinerarios en terreno.
          </p>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3 pt-2 z-10">
          <RouterLink
            to="/validador/bandeja"
            class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold hover:bg-primary-container transition-all shadow-sm"
          >
            Ir a Bandeja Operativa
          </RouterLink>
        </div>
      </section>
    </div>
  </main>
</template>
