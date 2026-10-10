<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>

<template>
  <div class="w-full">
    <!-- Top Safety Guarantee Ticker -->
    <section class="w-full bg-secondary text-on-secondary py-space-xs px-6">
      <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-space-sm text-center md:text-left">
        <div class="flex items-center gap-space-xs mx-auto md:mx-0">
          <span class="material-symbols-outlined text-secondary-fixed text-[18px]">verified_user</span>
          <span class="font-label-md text-label-md tracking-wide">
            Auditoría in situ de cada propiedad • Trato directo por WhatsApp • Sin comisiones por reserva
          </span>
        </div>
        <div class="hidden lg:flex items-center gap-space-md font-label-sm text-label-sm text-secondary-fixed">
          <span class="flex items-center gap-1">
            <span class="material-symbols-outlined text-[15px]">location_on</span>
            San Juan Capital &amp; Departamentos
          </span>
          <span class="flex items-center gap-1">
            <span class="material-symbols-outlined text-[15px]">lock</span>
            Cuentas de dueños cotejadas presencialmente
          </span>
        </div>
      </div>
    </section>

    <!-- Launch Promo Ribbon -->
    <div class="w-full bg-tertiary-container text-tertiary-fixed py-2.5 px-6 border-b border-tertiary-fixed/20 shadow-sm">
      <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-space-sm">
        <div class="flex items-center gap-space-xs mx-auto md:mx-0 font-label-md text-label-md">
          <span class="material-symbols-outlined text-tertiary-fixed text-[20px] animate-pulse">campaign</span>
          <span>
            <strong class="font-bold text-on-tertiary">¡Lanzamiento San Juan!</strong>
            Las primeras 50 ofertas tienen <strong>6 meses de suscripción 100% gratis</strong> y auditoría sin cargo.
          </span>
        </div>
        <div class="hidden md:flex items-center gap-space-sm mx-auto md:mx-0">
          <a
            href="#anfitriones"
            class="inline-flex items-center gap-1 text-label-sm font-label-sm bg-tertiary-fixed text-on-tertiary-fixed px-3 py-1 rounded-full font-bold hover:bg-surface-bright transition-colors"
          >
            <span class="material-symbols-outlined text-[15px]">bolt</span>
            Aprovechar Cupo
          </a>
        </div>
      </div>
    </div>

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
          <div class="hidden xl:flex items-center gap-space-xs bg-secondary/10 px-space-sm py-1 rounded-full">
            <span class="material-symbols-outlined text-secondary text-[16px]">verified</span>
            <span class="font-label-sm text-label-sm text-secondary uppercase font-bold">100% Verificado in situ</span>
          </div>
        </div>

        <!-- Navigation Links -->
        <nav class="hidden lg:flex items-center gap-space-md">
          <RouterLink
            to="/"
            class="px-space-sm py-2 transition-colors font-label-lg text-label-lg rounded-lg bg-primary text-on-primary font-bold"
          >
            Explorar Quintas
          </RouterLink>
          <a
            href="#protocolo"
            class="font-label-lg text-label-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container px-space-sm py-2 rounded-lg transition-colors font-semibold"
          >
            Cómo Validamos
          </a>
          <a
            href="#zonas"
            class="font-label-lg text-label-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container px-space-sm py-2 rounded-lg transition-colors font-semibold"
          >
            Zonas
          </a>
          <a
            href="#anfitriones"
            class="font-label-lg text-label-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container px-space-sm py-2 rounded-lg transition-colors font-semibold"
          >
            Solicitar Inspección
          </a>
          <a
            href="https://wa.me/5492644000000?text=Hola,%20deseo%20contactar%20a%20un%20validador"
            target="_blank"
            rel="noopener noreferrer"
            class="font-label-lg text-label-lg text-secondary font-semibold hover:text-on-secondary-container hover:bg-surface-container px-space-sm py-2 rounded-lg transition-colors flex items-center gap-1"
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
            Publicar Quinta
          </a>
          <a
            href="https://wa.me/5492644000000?text=Hola,%20deseo%20validar%20mi%20quinta"
            target="_blank"
            rel="noopener noreferrer"
            class="inline-flex items-center gap-1.5 font-label-md text-label-md px-space-md py-2.5 rounded-lg bg-surface-container hover:bg-surface-dim text-on-surface transition-colors shadow-xs font-bold"
          >
            <span class="material-symbols-outlined text-[18px] text-secondary">verified</span>
            <span>Validar</span>
          </a>
        </div>
      </div>
    </header>
  </div>
</template>
