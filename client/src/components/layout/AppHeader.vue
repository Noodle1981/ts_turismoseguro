<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>

<template>
  <div class="w-full">
    <!-- Main Stitch Header -->
    <header class="w-full bg-surface-container-lowest/90 backdrop-blur-md shadow-[0_1px_8px_rgba(15,41,66,0.05)] border-b border-surface-container">
      <div class="h-20 max-w-7xl mx-auto px-6 lg:px-12 flex items-center justify-between gap-space-md">
        <!-- Logo & Brand -->
        <div class="flex items-center gap-space-md">
          <RouterLink to="/" class="flex items-center gap-space-sm">
            <div class="w-9 h-9 rounded-xl bg-primary text-secondary-fixed flex items-center justify-center font-bold shadow-xs">
              <span class="material-symbols-outlined text-[24px]">shield</span>
            </div>
            <span class="font-headline-sm text-headline-sm text-primary tracking-tight hidden sm:inline-block">
              Turismo Seguro <span class="font-headline-sm text-headline-sm text-secondary">San Juan</span>
            </span>
          </RouterLink>
        </div>

        <!-- Navigation Links -->
        <nav class="hidden lg:flex items-center gap-space-md">
          <RouterLink
            to="/explorar"
            class="px-space-sm py-2 transition-colors font-label-lg text-label-lg text-on-surface-variant hover:text-primary font-semibold"
            active-class="text-primary font-bold"
          >
            Explorar Quintas y Cabañas
          </RouterLink>
          <RouterLink
            to="/eventos"
            class="px-space-sm py-2 transition-colors font-label-lg text-label-lg text-on-surface-variant hover:text-primary font-semibold"
            active-class="text-primary font-bold"
          >
            Eventos &amp; Experiencias
          </RouterLink>
          <a
            href="#protocolo"
            class="font-label-lg text-label-lg text-on-surface-variant hover:text-primary px-space-sm py-2 transition-colors font-semibold"
          >
            Cómo Validamos
          </a>
          <a
            href="https://wa.me/5492644000000?text=Hola,%20deseo%20contactar%20a%20un%20validador"
            target="_blank"
            rel="noopener noreferrer"
            class="font-label-lg text-label-lg text-secondary font-semibold hover:text-on-secondary-container px-space-sm py-2 transition-colors flex items-center gap-1"
          >
            <span class="material-symbols-outlined text-[18px]">support_agent</span>
            Contactar Validador
          </a>
        </nav>

        <!-- Right Buttons -->
        <div class="flex items-center gap-space-sm">
          <a
            href="#anfitriones"
            class="hidden sm:inline-flex items-center justify-center font-label-md text-label-md px-space-md py-2.5 rounded-lg bg-primary text-on-primary hover:bg-primary-container transition-colors font-bold shadow-xs"
          >
            Publicar Oferta
          </a>
          <RouterLink
            to="/login"
            class="inline-flex items-center gap-1.5 font-label-md text-label-md px-space-md py-2.5 rounded-lg bg-surface-container hover:bg-surface-dim text-on-surface transition-colors shadow-xs font-bold"
          >
            <span class="material-symbols-outlined text-[18px] text-primary">login</span>
            <span>Iniciar Sesión</span>
          </RouterLink>
        </div>
      </div>
    </header>
  </div>
</template>
