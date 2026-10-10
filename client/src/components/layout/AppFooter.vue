<script setup lang="ts">
</script>

<template>
  <footer class="w-full bg-surface-container-low mt-space-xl border-t border-surface-container">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-space-xl">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-space-xl mb-space-xl">
        <!-- Col 1: Brand & Certification -->
        <div>
          <div class="flex items-center gap-space-xs mb-space-sm">
            <span class="material-symbols-outlined text-secondary text-[28px]" style="font-variation-settings: 'FILL' 1;">
              verified_user
            </span>
            <span class="font-headline-sm text-headline-sm text-primary font-bold">Turismo Seguro San Juan</span>
          </div>
          <p class="font-body-sm text-body-sm text-on-surface-variant mb-space-md">
            Plataforma sanjuanina de alquiler temporal para pasar el día. Eliminamos las estafas virtuales mediante auditoría física in situ en cada propiedad.
          </p>
          <div class="inline-flex items-center gap-space-xs bg-surface-container-lowest px-space-sm py-1.5 rounded-full shadow-sm">
            <span class="material-symbols-outlined text-secondary text-[18px]">gpp_good</span>
            <span class="font-label-sm text-label-sm text-on-surface font-bold">Garantía de Seña Segura</span>
          </div>
        </div>

        <!-- Col 2: Zonas -->
        <div id="zonas">
          <div class="font-headline-sm text-headline-sm text-primary font-bold mb-space-sm">Zonas de Alquiler</div>
          <ul class="space-y-2 text-sm">
            <li class="font-body-sm text-body-sm text-on-surface-variant">Pocito (Calle 11 a Calle 14, Quinto Cuartel)</li>
            <li class="font-body-sm text-body-sm text-on-surface-variant">Zonda (Calle Las Moras, Villa Basilio Nievas)</li>
            <li class="font-body-sm text-body-sm text-on-surface-variant">Ullum (Calle Hermógenes Ruiz, Dique)</li>
            <li class="font-body-sm text-body-sm text-on-surface-variant">Rawson (Médano de Oro, Calle Ramón Franco)</li>
            <li class="font-body-sm text-body-sm text-on-surface-variant">Rivadavia &amp; Santa Lucía (Zonas suburbanas)</li>
          </ul>
        </div>

        <!-- Col 3: Seguridad y Legal -->
        <div id="protocolo">
          <div class="font-headline-sm text-headline-sm text-primary font-bold mb-space-sm">Seguridad y Legal</div>
          <ul class="space-y-2 text-sm">
            <li>
              <a href="#" class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors">
                Auditoría Física y Checklist
              </a>
            </li>
            <li>
              <a href="#" class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors">
                Protocolo de Señas Bancarias
              </a>
            </li>
            <li>
              <a href="#" class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors">
                Cuentas Validadas por CUIT
              </a>
            </li>
            <li>
              <a href="#anfitriones" class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors">
                Registro Oficial de Propietarios
              </a>
            </li>
          </ul>
        </div>

        <!-- Col 4: Atención -->
        <div>
          <div class="font-headline-sm text-headline-sm text-primary font-bold mb-space-sm">Atención Sanjuanina</div>
          <p class="font-body-sm text-body-sm text-on-surface-variant mb-space-xs">Operación y soporte en San Juan Capital, Argentina.</p>
          <p class="font-body-sm text-body-sm text-on-surface-variant mb-space-xs">Lunes a Domingo: 08:00 a 22:00 hs</p>
          <div class="mt-space-sm">
            <a
              href="https://wa.me/5492644000000?text=Hola,%20necesito%20validar%20una%20propiedad%20en%20Turismo%20Seguro"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center gap-space-xs font-label-md text-label-md text-secondary hover:text-on-secondary-container transition-colors font-bold"
            >
              <span class="material-symbols-outlined text-[18px]">chat</span>
              <span>Línea Directa de Validación WhatsApp</span>
            </a>
          </div>
        </div>
      </div>

      <div class="pt-space-md border-t border-surface-container flex flex-col md:flex-row items-center justify-between gap-space-sm text-xs">
        <p class="font-body-sm text-body-sm text-on-surface-variant">
          © 2026 Turismo Seguro San Juan. Plataforma de fiscalización comunitaria para eventos y días de campo.
        </p>
        <div class="flex items-center gap-space-md font-label-sm text-label-sm text-on-surface-variant font-bold">
          <span>San Juan Seguro</span>
          <span>•</span>
          <span>Cero Estafas</span>
          <span>•</span>
          <span>Auditores In Situ</span>
        </div>
      </div>
    </div>
  </footer>
</template>
