<script setup lang="ts">
import { RouterLink } from 'vue-router'
import { FileQuestion, ArrowLeft } from 'lucide-vue-next'
</script>

<template>
  <div class="min-h-[75vh] flex items-center justify-center px-4 py-16">
    <div class="max-w-md w-full text-center space-y-6 bg-white p-8 sm:p-10 rounded-2xl shadow-xl border border-slate-200/80">
      <div class="w-16 h-16 rounded-2xl bg-surface-100 text-surface-600 mx-auto flex items-center justify-center">
        <FileQuestion class="w-9 h-9" />
      </div>

      <div class="space-y-2">
        <span class="text-xs font-mono font-bold uppercase tracking-wider text-surface-600 bg-surface-100 px-2.5 py-1 rounded-full">
          Código 404 · No Encontrado
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-surface-900 font-display">
          Página no encontrada
        </h1>
        <p class="text-sm text-surface-500">
          El destino que estás buscando no existe o fue movido de lugar.
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
