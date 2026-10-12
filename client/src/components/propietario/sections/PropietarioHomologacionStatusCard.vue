<script setup lang="ts">
import { computed, ref } from 'vue'
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

// Modales y estados interactivos para Estrategia de Seguro y Categorías
const hasOwnInsurance = ref(false)
const showInsuranceInfoModal = ref(false)
const showStandardCategoryModal = ref(false)
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
            <span class="material-symbols-outlined text-[16px]" :class="isAssigned ? 'text-secondary' : 'text-amber-600'">
              {{ isAssigned ? 'verified_user' : 'lock_clock' }}
            </span>
            <span>{{ isAssigned ? 'Token Antifraude Activo' : 'Token Antifraude' }}</span>
          </div>
          <div
            class="font-mono text-2xl font-black tracking-wider py-1 px-3 rounded-lg border"
            :class="isAssigned && token !== 'TS-PENDIENTE'
              ? 'text-primary bg-surface-container border-surface-container-high'
              : 'text-outline bg-surface-container-low border-surface-container/60 select-none'"
          >
            {{ token }}
          </div>
          <p class="text-[11px] text-on-surface-variant max-w-[200px] leading-tight">
            {{ isAssigned && token !== 'TS-PENDIENTE'
              ? 'El validador debe mencionarle este código al coordinar la visita.'
              : 'Se generará de forma segura al momento en que el supervisor asigne un validador.' }}
          </p>
          <button
            v-if="isAssigned && token !== 'TS-PENDIENTE'"
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

    <!-- SECCIÓN DIDÁCTICA: ¿QUÉ ES EL ACTA OFICIAL, PARA QUÉ SIRVE Y CUÁL ES SU FINALIDAD? -->
    <div class="bg-surface-container-lowest rounded-2xl p-5 sm:p-6 border border-surface-container shadow-xs">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-surface-container">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-primary text-on-primary flex items-center justify-center shrink-0 shadow-xs">
            <span class="material-symbols-outlined text-[24px]">assignment_turned_in</span>
          </div>
          <div class="flex flex-col">
            <div class="flex items-center gap-2 flex-wrap">
              <span class="font-label-sm text-label-sm text-secondary font-bold uppercase tracking-wider">
                Documentación Técnica Provincial
              </span>
              <span class="px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-surface-container text-primary">
                Ejemplo Canónico: Acta #849-MED/26
              </span>
            </div>
            <h3 class="font-headline-sm text-headline-sm text-primary font-bold">
              El Acta Oficial de Homologación: ¿Qué es, para qué sirve y cuál es su finalidad?
            </h3>
          </div>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-fixed/40 text-on-secondary-fixed font-label-sm text-label-sm font-bold self-start md:self-auto">
          <span class="material-symbols-outlined text-[16px] text-secondary">verified</span>
          Instrumento Público Certificado
        </span>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4">
        <!-- 1. ¿Qué es? -->
        <div class="p-4 rounded-xl bg-surface-container-low border border-surface-container flex flex-col gap-2">
          <div class="flex items-center gap-2 text-primary font-bold text-xs uppercase tracking-wider">
            <span class="material-symbols-outlined text-[18px] text-secondary">description</span>
            <span>1. ¿Qué es el Acta Oficial?</span>
          </div>
          <p class="text-xs text-on-surface-variant leading-relaxed">
            Es el <strong>instrumento público y técnico</strong> emitido y suscripto por el validador matriculado al culminar la auditoría física in situ. Su codificación oficial (por ejemplo, <code class="font-mono font-bold text-primary bg-surface-container px-1 py-0.5 rounded">Acta #849-MED/26</code>) representa el número secuencial de legajo, la zona territorial auditada (<em class="text-on-surface">MED = Médano de Oro / Rawson</em>) y el año de vigencia provincial.
          </p>
        </div>

        <!-- 2. ¿Para qué sirve? -->
        <div class="p-4 rounded-xl bg-surface-container-low border border-surface-container flex flex-col gap-2">
          <div class="flex items-center gap-2 text-primary font-bold text-xs uppercase tracking-wider">
            <span class="material-symbols-outlined text-[18px] text-secondary">fact_check</span>
            <span>2. ¿Para qué sirve?</span>
          </div>
          <p class="text-xs text-on-surface-variant leading-relaxed">
            Acredita fehacientemente ante el Estado y los inquilinos que la quinta <strong>no es una publicación fantasma ni un intento de estafa</strong>: certifica coordenadas GPS reales, titularidad del anfitrión, fotos tomadas en terreno y cumplimiento de las normas físicas (cerco de pileta, disyuntor y matafuegos).
          </p>
        </div>

        <!-- 3. ¿Cuál es su finalidad? -->
        <div class="p-4 rounded-xl bg-surface-container-low border border-surface-container flex flex-col gap-2">
          <div class="flex items-center gap-2 text-primary font-bold text-xs uppercase tracking-wider">
            <span class="material-symbols-outlined text-[18px] text-secondary">workspace_premium</span>
            <span>3. ¿Cuál es su finalidad?</span>
          </div>
          <ul class="text-xs text-on-surface-variant space-y-1.5 leading-relaxed">
            <li class="flex items-start gap-1.5">
              <span class="material-symbols-outlined text-secondary text-[14px] shrink-0 mt-0.5">check_circle</span>
              <span><strong>Emisión de la Insignia:</strong> Es el dictamen indispensable para obtener el distintivo <em>"Oferta con Seguro"</em> y la prioridad Top #1 en el buscador.</span>
            </li>
            <li class="flex items-start gap-1.5">
              <span class="material-symbols-outlined text-secondary text-[14px] shrink-0 mt-0.5">check_circle</span>
              <span><strong>Habilitación de Póliza RC:</strong> Es el documento que exige Triunfo Seguros para activar la cobertura sin adelantos.</span>
            </li>
            <li class="flex items-start gap-1.5">
              <span class="material-symbols-outlined text-secondary text-[14px] shrink-0 mt-0.5">check_circle</span>
              <span><strong>Blindaje Legal Provincial:</strong> Otorga amparo jurídico oficial frente a reclamos de inquilinos o inspecciones del Ministerio de Turismo.</span>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- SECCIÓN INFORMATIVA: REQUISITOS OFICIALES Y ESTRATEGIA DE PUBLICACIÓN -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
      
      <!-- BANNER COMPARATIVO: LAS 2 CATEGORÍAS Y EL ALGORITMO DE BÚSQUEDA (12 COLS) -->
      <div class="lg:col-span-12 bg-surface-container-lowest rounded-2xl p-5 border border-surface-container shadow-xs">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-surface-container">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary-container text-secondary-fixed flex items-center justify-center shrink-0">
              <span class="material-symbols-outlined text-[24px]">troubleshoot</span>
            </div>
            <div class="flex flex-col">
              <span class="font-label-sm text-label-sm text-secondary font-bold uppercase tracking-wider">Motor de Posicionamiento Algorítmico</span>
              <h3 class="font-headline-sm text-headline-sm text-primary font-bold">Publicación Estándar vs. Verificada (Predio Seguro)</h3>
            </div>
          </div>
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/30 text-on-secondary-container font-label-sm text-label-sm font-bold self-start md:self-auto">
            <span class="material-symbols-outlined text-[16px] text-secondary">verified</span>
            Publicar es libre · Asegurar es crecer
          </span>
        </div>

        <!-- Comparativa de las 2 Categorías -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4">
          <!-- Categoría Estándar -->
          <div class="p-4 rounded-xl bg-surface-container-low/60 border border-surface-container flex flex-col justify-between gap-3">
            <div class="flex flex-col gap-1.5">
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Categoría 1</span>
                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-surface-container text-on-surface-variant">Sin Seguro</span>
              </div>
              <h4 class="font-headline-sm text-[16px] text-on-surface font-bold">Publicación Estándar</h4>
              <p class="text-xs text-on-surface-variant leading-relaxed">
                Tu propiedad se publica de forma tradicional sin requisitos excluyentes. En la ficha pública figurará con un aviso transparente:
                <span class="text-on-surface block font-medium mt-1 bg-surface-container px-2 py-1 rounded text-[11px]">
                  «Esta propiedad no cuenta con validación de seguro activa»
                </span>
              </p>
            </div>
            <div class="pt-2 border-t border-surface-container/80 flex items-center justify-between text-[11px] text-on-surface-variant">
              <span>Posicionamiento orgánico base</span>
              <button
                type="button"
                class="font-bold text-primary hover:underline cursor-pointer"
                @click="showStandardCategoryModal = true"
              >
                Ver alcances legales &rarr;
              </button>
            </div>
          </div>

          <!-- Categoría Premium / Verificada -->
          <div class="p-4 rounded-xl bg-secondary-fixed/15 border border-secondary/30 flex flex-col justify-between gap-3 relative overflow-hidden">
            <div class="flex flex-col gap-1.5">
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-secondary uppercase tracking-wider flex items-center gap-1">
                  <span class="material-symbols-outlined text-[14px]">stars</span> Categoría 2 · Recomendada
                </span>
                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-secondary text-on-secondary">Prioridad Top #1</span>
              </div>
              <h4 class="font-headline-sm text-[16px] text-primary font-bold flex items-center gap-1.5">
                <span>Premium / Predio Seguro</span>
                <span class="material-symbols-outlined text-secondary text-[18px]">verified_user</span>
              </h4>
              <p class="text-xs text-on-surface leading-relaxed">
                Validador in situ + Póliza de Responsabilidad Civil. <strong>Prioridad absoluta en el algoritmo de búsqueda</strong>, escudo verde visible ante los inquilinos y hasta 5x más conversiones por WhatsApp.
              </p>
            </div>
            <div class="pt-2 border-t border-secondary/20 flex items-center justify-between text-[11px]">
              <span class="text-secondary font-bold">Insignia "Oferta con Seguro"</span>
              <span class="font-mono text-primary font-extrabold">+85% Confianza de Reserva</span>
            </div>
          </div>
        </div>
      </div>

      <!-- 1. Oportunidad de Negocio: Póliza Triunfo Seguros & Seguro de Eventos (6 cols) -->
      <div class="lg:col-span-6 bg-surface-container-lowest rounded-xl p-5 border border-surface-container shadow-xs flex flex-col justify-between gap-4">
        <div class="flex flex-col gap-2.5">
          <div class="flex items-center justify-between">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-secondary-fixed/50 text-on-secondary-fixed font-label-sm text-label-sm font-bold">
              <span class="material-symbols-outlined text-[16px] text-secondary">health_and_safety</span>
              Triunfo Seguros San Juan
            </span>
            <span class="text-xs font-mono font-bold text-secondary">Alianza Provincial</span>
          </div>

          <h3 class="font-headline-sm text-headline-sm text-primary font-bold">
            Seguros de Responsabilidad Civil & Eventos (Fiestas)
          </h3>
          <p class="text-xs text-on-surface-variant leading-relaxed">
            Las opciones a continuación son informativas. La contratación o emisión de estas coberturas se realiza una vez que el validador oficial constate las instalaciones físicas en terreno.
          </p>

          <!-- OPCIÓN INTERACTIVA: POSEO SEGURO CONTRATADO -->
          <div
            class="p-3.5 rounded-xl border-2 transition-all cursor-pointer flex flex-col gap-2"
            :class="hasOwnInsurance
              ? 'bg-emerald-50 dark:bg-emerald-950/40 border-secondary shadow-xs'
              : 'bg-surface-container-low border-surface-container hover:border-secondary/50'"
            @click="hasOwnInsurance = !hasOwnInsurance"
          >
            <div class="flex items-center justify-between">
              <label class="flex items-center gap-2.5 cursor-pointer pointer-events-none">
                <input
                  type="checkbox"
                  :checked="hasOwnInsurance"
                  class="w-4 h-4 rounded text-secondary focus:ring-secondary border-surface-container cursor-pointer"
                />
                <span class="font-bold text-xs text-primary">Poseo seguro contratado</span>
              </label>
              <span
                class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider"
                :class="hasOwnInsurance ? 'bg-secondary text-on-secondary' : 'bg-surface-container text-on-surface-variant'"
              >
                {{ hasOwnInsurance ? 'Póliza Propia' : 'Tildar si ya tenés' }}
              </span>
            </div>

            <p class="text-[11px] text-on-surface-variant leading-relaxed">
              Tildá aquí si tu quinta ya cuenta con póliza vigente de Responsabilidad Civil comercial o de eventos contratada con tu aseguradora habitual.
            </p>

            <!-- AVISO CLAVE AL MARCAR POSEO SEGURO CONTRATADO -->
            <div
              v-if="hasOwnInsurance"
              class="p-2.5 rounded-lg bg-surface-container-lowest border border-secondary/50 flex items-start gap-2 text-xs"
            >
              <span class="material-symbols-outlined text-secondary text-[20px] shrink-0 mt-0.5">assignment_late</span>
              <div class="text-[11px] leading-snug">
                <strong class="text-secondary font-bold block uppercase tracking-wider">Requisito obligatorio para la visita:</strong>
                <span class="text-on-surface">
                  Debe tener ese seguro en mano (póliza impresa o digital vigente en el teléfono con certificado de cobertura al día) al momento de la visita para que el validador constate titular, número de póliza y vigencia in situ.
                </span>
              </div>
            </div>
          </div>

          <!-- Opciones de Cobertura Accesibles (Catálogo Informativo tras visita) -->
          <div class="flex flex-col gap-2 pt-1">
            <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider flex items-center gap-1">
              <span class="material-symbols-outlined text-[14px] text-secondary">info</span>
              Planes Disponibles tras la Constatación del Validador:
            </span>

            <!-- Plan Mensual Temporada -->
            <div class="p-3 rounded-xl bg-surface-container-low border border-surface-container flex items-center justify-between">
              <div class="flex flex-col">
                <span class="font-bold text-xs text-primary flex items-center gap-1.5">
                  Plan Mensual Temporada
                  <span class="text-[10px] bg-secondary-container text-on-secondary-container font-extrabold px-1.5 py-0.2 rounded">Diurno</span>
                </span>
                <span class="text-[11px] text-on-surface-variant">RC Integral pileta y predio para alquileres continuos</span>
              </div>
              <span class="px-2.5 py-1 rounded-lg bg-surface-container text-primary font-mono text-xs font-extrabold shrink-0">
                $18.500 / mes
              </span>
            </div>

            <!-- Seguro de Eventos (Fiestas) -->
            <div class="p-3 rounded-xl bg-surface-container-low border border-amber-500/30 flex items-center justify-between">
              <div class="flex flex-col">
                <span class="font-bold text-xs text-amber-950 dark:text-amber-200 flex items-center gap-1.5">
                  <span class="material-symbols-outlined text-[15px] text-amber-600">celebration</span>
                  Seguro de Eventos (Fiestas)
                  <span class="text-[10px] bg-amber-500/20 text-amber-800 dark:text-amber-300 font-extrabold px-1.5 py-0.2 rounded">Especial Fiestas</span>
                </span>
                <span class="text-[11px] text-on-surface-variant">Cobertura para cumpleaños, recepciones y eventos masivos con aforo ampliado</span>
              </div>
              <span class="px-2.5 py-1 rounded-lg bg-amber-500/20 text-amber-900 dark:text-amber-200 font-mono text-xs font-extrabold shrink-0">
                Por Evento / Mes
              </span>
            </div>

            <!-- Microseguro por Jornada -->
            <div class="p-3 rounded-xl bg-surface-container-low border border-surface-container flex items-center justify-between">
              <div class="flex flex-col">
                <span class="font-bold text-xs text-primary">Microseguro por Jornada</span>
                <span class="text-[11px] text-on-surface-variant">Cobertura individual por día o fin de semana alquilado</span>
              </div>
              <span class="px-2.5 py-1 rounded-lg bg-surface-container text-primary font-mono text-xs font-extrabold shrink-0">
                $3.200 / día
              </span>
            </div>

            <!-- Plan Anual Bonificado -->
            <div class="p-3 rounded-xl bg-surface-container-low border border-surface-container flex items-center justify-between">
              <div class="flex flex-col">
                <span class="font-bold text-xs text-secondary flex items-center gap-1.5">
                  Plan Anual Bonificado <span class="material-symbols-outlined text-[14px]">stars</span>
                </span>
                <span class="text-[11px] text-on-surface-variant">365 días + Insignia permanente "Oferta con Seguro"</span>
              </div>
              <span class="px-2.5 py-1 rounded-lg bg-secondary-fixed text-on-secondary-fixed font-mono text-xs font-extrabold shrink-0">
                12 Meses
              </span>
            </div>
          </div>

          <button
            type="button"
            class="w-full mt-2 py-2.5 px-4 rounded-xl bg-surface-container hover:bg-surface-container-high text-primary font-label-md text-label-md font-bold transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40"
            @click="showInsuranceInfoModal = true"
          >
            <span class="material-symbols-outlined text-[18px] text-secondary">info</span>
            <span>Ver detalles de coberturas y Seguro de Eventos</span>
          </button>
        </div>

        <div class="pt-2 border-t border-surface-container flex items-center justify-between text-xs text-on-surface-variant">
          <span>Se contrata tras dictamen del validador</span>
          <button
            type="button"
            class="text-on-surface-variant hover:text-primary font-semibold hover:underline cursor-pointer"
            @click="showStandardCategoryModal = true"
          >
            Prefiero quedarme sin seguro
          </button>
        </div>
      </div>

      <!-- 2. Matriz de Requisitos Oficiales a Evaluar por el Validador (6 cols) -->
      <div class="lg:col-span-6 bg-surface-container-lowest rounded-xl p-5 border border-surface-container shadow-xs flex flex-col justify-between gap-4">
        <div class="flex flex-col gap-2.5">
          <div class="flex items-center justify-between pb-2 border-b border-surface-container">
            <div class="flex items-center gap-2">
              <span class="material-symbols-outlined text-primary text-[20px]">checklist_rtl</span>
              <h3 class="font-headline-sm text-headline-sm text-primary font-bold">
                Checklist Físico & Protección Legal
              </h3>
            </div>
            <span class="text-[11px] text-secondary font-bold">Condiciones Clave</span>
          </div>

          <p class="text-xs text-on-surface-variant leading-relaxed">
            Si tu quinta cumple con estas condiciones físicas, el validador aprueba el checklist y queda <strong>100% lista para ser asegurada</strong>:
          </p>

          <!-- Subsección 1: Seguridad Preventiva -->
          <div class="flex flex-col gap-1.5">
            <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider flex items-center gap-1">
              <span class="material-symbols-outlined text-[14px] text-secondary">security</span>
              1. Medidas de Seguridad Física (Para Asegurabilidad):
            </span>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
              <div class="p-2.5 rounded-lg bg-surface-container-low border border-surface-container flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px]">fence</span>
                <div class="flex flex-col">
                  <span class="text-xs font-bold text-primary">Cerco Perimetral Pileta</span>
                  <span class="text-[10px] text-on-surface-variant">Altura mín. 1.20m c/ traba infantil</span>
                </div>
              </div>

              <div class="p-2.5 rounded-lg bg-surface-container-low border border-surface-container flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px]">electric_bolt</span>
                <div class="flex flex-col">
                  <span class="text-xs font-bold text-primary">Disyuntor & Puesta a Tierra</span>
                  <span class="text-[10px] text-on-surface-variant">Tablero eléctrico estanco certificado</span>
                </div>
              </div>

              <div class="p-2.5 rounded-lg bg-surface-container-low border border-surface-container flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px]">fire_extinguisher</span>
                <div class="flex flex-col">
                  <span class="text-xs font-bold text-primary">Matafuego ABC Vigente</span>
                  <span class="text-[10px] text-on-surface-variant">Carga al día en área parrillero</span>
                </div>
              </div>

              <div class="p-2.5 rounded-lg bg-surface-container-low border border-surface-container flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px]">medical_services</span>
                <div class="flex flex-col">
                  <span class="text-xs font-bold text-primary">Botiquín de Emergencia</span>
                  <span class="text-[10px] text-on-surface-variant">Insumos y números sanitarios</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Subsección 2: Checklist de Propiedades de la Oferta -->
          <div class="flex flex-col gap-1.5 pt-2 border-t border-surface-container">
            <div class="flex items-center justify-between">
              <span class="text-[11px] font-bold text-primary uppercase tracking-wider flex items-center gap-1">
                <span class="material-symbols-outlined text-[15px] text-secondary">domain_verification</span>
                2. Checklist de Propiedades de la Oferta:
              </span>
              <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-secondary-fixed/50 text-on-secondary-fixed uppercase tracking-wider">
                Cotejo In Situ
              </span>
            </div>

            <p class="text-[11px] text-on-surface-variant leading-relaxed">
              El validador revisará en persona que todas las instalaciones y comodidades declaradas coincidan con la realidad:
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
              <div class="p-2.5 rounded-lg bg-surface-container-low border border-surface-container flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px]">bed</span>
                <div class="flex flex-col">
                  <span class="text-xs font-bold text-primary">Habitaciones y Plazas</span>
                  <span class="text-[10px] text-on-surface-variant">Cantidad de dormitorios, camas y aforo</span>
                </div>
              </div>

              <div class="p-2.5 rounded-lg bg-surface-container-low border border-surface-container flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px]">pool</span>
                <div class="flex flex-col">
                  <span class="text-xs font-bold text-primary">Pileta y Espacios Verdes</span>
                  <span class="text-[10px] text-on-surface-variant">Dimensiones, bomba y estado general</span>
                </div>
              </div>

              <div class="p-2.5 rounded-lg bg-surface-container-low border border-surface-container flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px]">outdoor_grill</span>
                <div class="flex flex-col">
                  <span class="text-xs font-bold text-primary">Parrilleros & Quincho</span>
                  <span class="text-[10px] text-on-surface-variant">Asadores, hornos, mesas y capacidad</span>
                </div>
              </div>

              <div class="p-2.5 rounded-lg bg-surface-container-low border border-surface-container flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px]">wc</span>
                <div class="flex flex-col">
                  <span class="text-xs font-bold text-primary">Sanitarios & Comodidades</span>
                  <span class="text-[10px] text-on-surface-variant">Baños, agua caliente, refrigeración</span>
                </div>
              </div>
            </div>

            <!-- Relevamiento Fotográfico Oficial -->
            <div class="mt-1 p-2.5 rounded-lg bg-secondary-fixed/20 border border-secondary/30 flex items-start gap-2.5">
              <span class="material-symbols-outlined text-secondary text-[20px] shrink-0 mt-0.5">add_a_photo</span>
              <div class="text-[11px] leading-snug">
                <strong class="text-primary font-bold block text-xs">Relevamiento Fotográfico Oficial:</strong>
                <span class="text-on-surface">
                  Durante la visita, el inspector recaudará y tomará fotografías oficiales de la fachada, pileta, quincho, dormitorios y comodidades para subirlas directamente a la plataforma una vez aprobada la inspección.
                </span>
              </div>
            </div>
          </div>

          <!-- Recuadro Legal Términos y Condiciones -->
          <div class="mt-1 p-3 rounded-xl bg-surface-container-low border border-surface-container text-xs text-on-surface-variant flex flex-col gap-1">
            <span class="font-bold text-primary flex items-center gap-1">
              <span class="material-symbols-outlined text-[16px] text-outline">policy</span>
              Marco Legal & Términos de la Plataforma
            </span>
            <p class="text-[11px] leading-relaxed">
              Para los anfitriones que elijan la Categoría Estándar sin seguro: la plataforma actúa exclusivamente como intermediario publicitario y el inquilino debe aceptar formalmente que asume el riesgo de alquilar un predio sin cobertura comercial antes de confirmar su reserva.
            </p>
          </div>
        </div>

        <div class="pt-2 border-t border-surface-container flex flex-wrap items-center justify-between gap-2 text-xs">
          <span class="text-on-surface-variant">¿Tenés dudas sobre las normas de seguridad?</span>
          <a
            href="https://turismo.sanjuan.gob.ar"
            target="_blank"
            class="text-secondary font-bold hover:underline flex items-center gap-1"
          >
            <span>Protocolo Oficial San Juan</span>
            <span class="material-symbols-outlined text-[14px]">open_in_new</span>
          </a>
        </div>
      </div>
    </div>

    <!-- MODAL INFORMATIVO: DETALLE DE COBERTURAS Y SEGURO DE EVENTOS (FIESTAS) -->
    <div
      v-if="showInsuranceInfoModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-primary/60 backdrop-blur-xs"
      role="dialog"
      aria-modal="true"
    >
      <div class="bg-surface-container-lowest max-w-lg w-full rounded-2xl p-6 border border-surface-container shadow-xl flex flex-col gap-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-surface-container">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary text-[24px]">health_and_safety</span>
            <h3 class="font-headline-sm text-headline-sm text-primary font-bold">
              Seguros de RC & Eventos (Fiestas)
            </h3>
          </div>
          <button
            type="button"
            class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface-variant hover:bg-surface-container cursor-pointer"
            @click="showInsuranceInfoModal = false"
          >
            <span class="material-symbols-outlined text-[20px]">close</span>
          </button>
        </div>

        <div class="flex flex-col gap-3 text-xs leading-relaxed text-on-surface-variant">
          <p>
            Para obtener la insignia <strong class="text-secondary font-bold">"Oferta con Seguro"</strong> y la máxima prioridad en el algoritmo de búsqueda, tu propiedad debe contar con póliza de Responsabilidad Civil verificada in situ.
          </p>

          <div class="p-3.5 rounded-xl bg-surface-container-low border border-surface-container space-y-3">
            <div class="flex flex-col gap-1">
              <span class="font-bold text-primary text-xs flex items-center gap-1.5">
                <span class="material-symbols-outlined text-secondary text-[16px]">pool</span>
                1. Responsabilidad Civil Alquiler Diurno (Pileta y Predio)
              </span>
              <p class="text-[11px] text-on-surface-variant">
                Cobertura comprensiva para estadías familiares diurnas hasta $50.000.000 (lesiones a terceros, ahogamiento, caídas, intoxicaciones e incidentes en áreas comunes).
              </p>
            </div>

            <div class="flex flex-col gap-1 pt-2 border-t border-surface-container">
              <span class="font-bold text-amber-900 dark:text-amber-200 text-xs flex items-center gap-1.5">
                <span class="material-symbols-outlined text-amber-600 text-[16px]">celebration</span>
                2. Seguro Especial para Eventos (Fiestas y Celebraciones)
              </span>
              <p class="text-[11px] text-on-surface-variant">
                Si alquilás tu quincho para cumpleaños, recepciones, bodas o fiestas nocturnas, se requiere esta póliza o endoso especial por evento para cubrir el aforo ampliado de asistentes, música y concurrencia ante imprevistos.
              </p>
            </div>
          </div>

          <!-- Si posee seguro propio -->
          <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-secondary/40 text-xs text-on-surface flex items-start gap-2">
            <span class="material-symbols-outlined text-secondary text-[18px] shrink-0 mt-0.5">verified_user</span>
            <span>
              <strong>¿Ya tenés seguro propio?</strong> Recordá tener en mano tu póliza impresa o digital con constancia de pago vigente al momento de la visita del inspector. El validador asentará el número de póliza en el acta oficial para otorgarte la insignia.
            </span>
          </div>

          <!-- Si no tiene seguro -->
          <div class="p-3 rounded-xl bg-surface-container-low border border-surface-container text-xs text-on-surface-variant flex items-start gap-2">
            <span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">handshake</span>
            <span>
              <strong>Si no tenés seguro:</strong> Podrás gestionar cualquiera de los planes homologados con Triunfo Seguros inmediatamente después de que el validador constate y apruebe el checklist físico de tu predio.
            </span>
          </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-surface-container">
          <button
            type="button"
            class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold transition-all shadow-xs cursor-pointer"
            @click="showInsuranceInfoModal = false"
          >
            Entendido, cerrar
          </button>
        </div>
      </div>
    </div>

    <!-- MODAL INFORMATIVO: PUBLICACIÓN ESTÁNDAR Y DESLINDE LEGAL -->
    <div
      v-if="showStandardCategoryModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-primary/60 backdrop-blur-xs"
      role="dialog"
      aria-modal="true"
    >
      <div class="bg-surface-container-lowest max-w-lg w-full rounded-2xl p-6 border border-surface-container shadow-xl flex flex-col gap-4">
        <div class="flex items-center justify-between pb-3 border-b border-surface-container">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[24px]">policy</span>
            <h3 class="font-headline-sm text-headline-sm text-primary font-bold">
              Publicación Estándar (Sin Seguro)
            </h3>
          </div>
          <button
            type="button"
            class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface-variant hover:bg-surface-container cursor-pointer"
            @click="showStandardCategoryModal = false"
          >
            <span class="material-symbols-outlined text-[20px]">close</span>
          </button>
        </div>

        <div class="flex flex-col gap-3 text-xs leading-relaxed text-on-surface-variant">
          <p>
            En <strong>Turismo Seguro San Juan</strong> no te excluimos ni te obligamos a tener seguro para dar a conocer tu predio. Sin embargo, para cuidar la transparencia con los turistas y la seguridad jurídica de la plataforma, aplican estas condiciones:
          </p>

          <div class="p-3.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-900 dark:text-amber-200 space-y-2">
            <div class="font-bold flex items-center gap-1.5">
              <span class="material-symbols-outlined text-[18px] text-amber-600">info</span>
              <span>Aviso visible para el huésped</span>
            </div>
            <p class="text-[11px] leading-tight">
              Tu publicación llevará una aclaración transparente: <em>«Esta propiedad no cuenta con validación de seguro activa»</em>. El huésped debe aceptar dicho descargo antes de reservar.
            </p>
          </div>

          <div class="p-3.5 rounded-xl bg-surface-container-low border border-surface-container space-y-1.5">
            <span class="font-bold text-primary">Intermediación publicitaria:</span>
            <p class="text-[11px]">
              La plataforma opera en esta categoría exclusivamente como tablón de contacto publicitario, quedando la responsabilidad civil ante eventuales incidentes bajo la esfera privada entre las partes.
            </p>
          </div>

          <p class="text-[11px] text-secondary font-bold">
            💡 Consejo: Podés publicar ahora en Estándar y, en cualquier momento, solicitar al validador y al seguro en 2 clics para subir al Top de búsquedas.
          </p>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-surface-container">
          <button
            type="button"
            class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold transition-all shadow-xs cursor-pointer"
            @click="showStandardCategoryModal = false"
          >
            Entendido, cerrar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
