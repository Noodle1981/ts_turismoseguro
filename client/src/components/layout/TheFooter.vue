<script setup lang="ts">
import { ShieldCheck, MapPin, Heart } from 'lucide-vue-next'
</script>

<template>
  <footer class="bg-surface-900 text-surface-200 mt-auto border-t border-surface-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Col 1: Brand & Certification -->
        <div class="md:col-span-2 space-y-4">
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-brand-500 flex items-center justify-center text-white">
              <ShieldCheck class="w-5 h-5 text-white" />
            </div>
            <span class="font-display font-extrabold text-white text-lg tracking-tight">
              Turismo Seguro San Juan
            </span>
          </div>
          <p class="text-sm text-surface-300 max-w-md leading-relaxed">
            Plataforma oficial de certificación in situ para alquileres temporarios y experiencias en la provincia de San Juan. Garantía de existencia física, titularidad autenticada y cero estafas.
          </p>
          <div class="flex items-center gap-2 text-xs text-brand-200">
            <MapPin class="w-4 h-4 text-accent-500" />
            <span>San Juan, Argentina — Cordillera de los Andes</span>
          </div>
        </div>

        <!-- Col 2: Enlaces rápidos -->
        <div>
          <h3 class="text-sm font-semibold uppercase tracking-wider text-white mb-3">Navegación</h3>
          <ul class="space-y-2 text-sm text-surface-300">
            <li><a href="#" class="hover:text-white transition-colors">Inicio</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Alojamientos Verificados</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Experiencias Cuyanas</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Cómo auditamos</a></li>
          </ul>
        </div>

        <!-- Col 3: Legal y Soporte -->
        <div>
          <h3 class="text-sm font-semibold uppercase tracking-wider text-white mb-3">Seguridad</h3>
          <ul class="space-y-2 text-sm text-surface-300">
            <li><a href="#" class="hover:text-white transition-colors">Protocolo Antifraude</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Términos del Servicio</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Soporte Técnico</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Portal de Validadores</a></li>
          </ul>
        </div>
      </div>

      <div class="border-t border-surface-800 mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-surface-400 gap-3">
        <p>© 2026 Turismo Seguro San Juan. Todos los derechos reservados.</p>
        <p class="flex items-center gap-1">
          Desarrollado con compromiso por la seguridad turística sanjuanina
          <Heart class="w-3.5 h-3.5 text-accent-500 fill-accent-500" />
        </p>
      </div>
    </div>
  </footer>
</template>
