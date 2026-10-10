<script setup lang="ts">
import { RouterLink } from 'vue-router'
import { ShieldAlert, ArrowLeft } from 'lucide-vue-next'
</script>

<template>
  <div class="min-h-[75vh] flex items-center justify-center px-4 py-16">
    <div class="max-w-md w-full text-center space-y-6 bg-white p-8 sm:p-10 rounded-2xl shadow-xl border border-slate-200/80">
      <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 mx-auto flex items-center justify-center">
        <ShieldAlert class="w-9 h-9" />
      </div>

      <div class="space-y-2">
        <span class="text-xs font-mono font-bold uppercase tracking-wider text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full">
          Código 403 · Acceso Restringido
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-surface-900 font-display">
          Área no autorizada
        </h1>
        <p class="text-sm text-surface-500">
          No poseés los permisos de rol requeridos para visualizar esta sección del portal. Si creés que es un error, contactá a un supervisor.
        </p>
      </div>

      <div class="pt-2">
        <RouterLink
          to="/"
          class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-xl transition shadow-xs"
        >
          <ArrowLeft class="w-4 h-4" />
          <span>Volver al Inicio</span>
        </RouterLink>
      </div>
    </div>
  </div>
</template>
