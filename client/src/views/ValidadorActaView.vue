<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../composables/useAuth'

const router = useRouter()
const { user, logout } = useAuth()

// Checklist reactivo de inspección técnica
const checklist = ref({
  poolFence: true,
  poolWaterClean: true,
  poolDepthMarked: true,
  electricalDisjunctor: true,
  fireExtinguisher: true,
  perimeterWalls: true,
  firstAidKit: true,
  cbuMatchesDni: true,
})

const auditorNotes = ref(
  'Predio en óptimas condiciones. Cerco perimetral de 1.20m con traba de seguridad operativa. Clorado de agua en 1.5 ppm, pH 7.4. CBU de Banco San Juan verificado contra DNI exhibido.'
)

const isApproved = ref(false)

const handleApprove = () => {
  isApproved.value = true
  setTimeout(() => {
    alert('¡Acta aprobada y Certificado QR generado con éxito!')
    router.push('/')
  }, 1000)
}

const handleLogout = async () => {
  await logout()
  router.push('/staff/login')
}
</script>

<template>
  <main class="w-full bg-background py-space-md min-h-screen">
    <!-- Top Progress Bar -->
    <div class="w-full bg-surface-container-high h-1.5 overflow-hidden">
      <div class="bg-secondary h-full transition-all duration-500 ease-out" style="width: 100%;"></div>
    </div>

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-12 py-8 flex flex-col gap-8">
      <!-- HEADER HERO / AUDIT META -->
      <div class="bg-surface-container-lowest rounded-xl p-6 sm:p-8 shadow-sm flex flex-col gap-6 border border-surface-container">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
          <div class="flex flex-col gap-2">
            <div class="flex flex-wrap items-center gap-2">
              <RouterLink
                to="/validador/bandeja"
                class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-surface-container hover:bg-surface-dim text-primary font-label-sm text-label-sm font-bold transition-colors"
              >
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                Volver a Bandeja
              </RouterLink>
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-fixed text-on-secondary-fixed font-label-sm text-label-sm uppercase tracking-wider font-bold">
                <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                Inspección Presencial en Terreno
              </span>
              <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-surface-container text-primary font-label-sm text-label-sm font-semibold">
                <span class="material-symbols-outlined text-[16px]">pin_drop</span>
                Rawson, El Medanito (GPS Live: -31.5941, -68.4983)
              </span>
            </div>
            <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight font-extrabold">
              Acta de Inspección Técnica In Situ #849-MED/26
            </h1>
            <p class="font-body-md text-body-md text-on-surface-variant">
              Relevamiento técnico oficial previo a publicación con Sello Anti-Estafas en <span class="font-semibold text-primary">Quinta Los Olivos (ID #Q-302)</span>
            </p>
          </div>

          <!-- Inspector Badge Details -->
          <div class="flex items-center justify-between sm:justify-start gap-4 p-4 rounded-xl bg-surface-container-low min-w-[320px] border border-surface-container">
            <div class="flex items-center gap-3 min-w-0">
              <div class="relative w-12 h-12 rounded-full bg-primary flex items-center justify-center text-on-primary shrink-0">
                <span class="material-symbols-outlined text-[26px]">engineering</span>
                <span class="absolute bottom-0 right-0 w-3.5 h-3.5 rounded-full bg-secondary"></span>
              </div>
              <div class="flex flex-col min-w-0">
                <span class="font-label-lg text-label-lg text-primary truncate font-bold">
                  {{ user?.name ?? 'Matías Fernández (Validador Matriculado)' }}
                </span>
                <span class="font-label-md text-label-md text-secondary font-semibold">
                  {{ user?.email ?? 'validador@turismoseguro.com.ar' }}
                </span>
                <span class="font-label-sm text-label-sm text-on-surface-variant flex items-center gap-1 mt-0.5">
                  <span class="material-symbols-outlined text-[14px]">verified</span>
                  Sesión Staff Activa
                </span>
              </div>
            </div>

            <button
              type="button"
              class="p-2 rounded-lg text-on-surface-variant hover:text-error hover:bg-surface-container transition-colors ml-auto cursor-pointer"
              title="Cerrar sesión"
              @click="handleLogout"
            >
              <span class="material-symbols-outlined text-[20px]">logout</span>
            </button>
          </div>
        </div>
      </div>

      <!-- MAIN TWO COLUMN WORKSPACE -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- AUDIT FORMS & CHECKLISTS (8 COLS) -->
        <div class="lg:col-span-8 flex flex-col gap-6">
          <!-- SECTION A: Titularidad & WhatsApp Oficial -->
          <section class="bg-surface-container-lowest rounded-xl p-6 sm:p-7 shadow-sm flex flex-col gap-5 border border-surface-container">
            <div class="flex items-center justify-between pb-3 border-b border-surface-container">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-surface-container-high text-primary flex items-center justify-center">
                  <span class="material-symbols-outlined text-[24px]">badge</span>
                </div>
                <div>
                  <h2 class="font-headline-md text-headline-md text-primary font-bold">A. Verificación de Titularidad y WhatsApp</h2>
                  <p class="font-body-sm text-body-sm text-on-surface-variant">Datos del titular presencial cotejados contra DNI físico</p>
                </div>
              </div>
              <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-secondary/10 text-secondary font-label-sm text-label-sm font-bold">
                <span class="material-symbols-outlined text-[16px]">verified</span> Validado In Situ
              </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="p-3.5 rounded-lg bg-surface-container-low flex flex-col gap-1 border border-surface-container">
                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase font-bold">Propietario / Responsable</span>
                <span class="font-headline-sm text-headline-sm text-primary font-bold">Carlos Eduardo Manrique</span>
                <span class="font-label-md text-label-md text-on-surface-variant">DNI: 24.891.034 (Constancia física exhibida)</span>
              </div>
              <div class="p-3.5 rounded-lg bg-surface-container-low flex flex-col gap-1 border border-surface-container">
                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase font-bold">WhatsApp Oficial de Reservas</span>
                <span class="font-headline-sm text-headline-sm text-secondary flex items-center gap-1.5 font-bold">
                  <span class="material-symbols-outlined text-[20px]">chat</span>
                  +54 9 264 458-9211
                </span>
                <span class="font-label-md text-label-md text-on-secondary-container">Llamada de prueba atendida en el predio</span>
              </div>
            </div>
          </section>

          <!-- SECTION B: Checklist de Seguridad Física (32 Puntos) -->
          <section class="bg-surface-container-lowest rounded-xl p-6 sm:p-7 shadow-sm flex flex-col gap-6 border border-surface-container">
            <div class="flex items-center justify-between pb-2 border-b border-surface-container">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-secondary/15 text-secondary flex items-center justify-center">
                  <span class="material-symbols-outlined text-[24px]">gpp_good</span>
                </div>
                <div>
                  <h2 class="font-headline-md text-headline-md text-primary font-bold">B. Checklist de Seguridad Física</h2>
                  <p class="font-body-sm text-body-sm text-on-surface-variant">Protocolo obligatorio TS de resguardo para familias</p>
                </div>
              </div>
              <span class="px-2.5 py-1 rounded bg-secondary-fixed/30 text-secondary font-label-md text-label-md font-bold">
                8 / 8 Verificados
              </span>
            </div>

            <!-- Items del Checklist -->
            <div class="space-y-3">
              <label class="flex items-center justify-between p-3.5 bg-surface-container-low rounded-xl border border-surface-container cursor-pointer">
                <div class="flex items-center gap-3">
                  <input v-model="checklist.poolFence" type="checkbox" class="w-5 h-5 accent-secondary" />
                  <span class="font-label-lg text-label-lg text-on-surface font-semibold">Cerco perimetral de piscina homologado (1.20m altura)</span>
                </div>
                <span class="material-symbols-outlined text-secondary text-[20px]">fence</span>
              </label>

              <label class="flex items-center justify-between p-3.5 bg-surface-container-low rounded-xl border border-surface-container cursor-pointer">
                <div class="flex items-center gap-3">
                  <input v-model="checklist.poolWaterClean" type="checkbox" class="w-5 h-5 accent-secondary" />
                  <span class="font-label-lg text-label-lg text-on-surface font-semibold">Agua de piscina cristalina, filtrada y clorada</span>
                </div>
                <span class="material-symbols-outlined text-secondary text-[20px]">pool</span>
              </label>

              <label class="flex items-center justify-between p-3.5 bg-surface-container-low rounded-xl border border-surface-container cursor-pointer">
                <div class="flex items-center gap-3">
                  <input v-model="checklist.electricalDisjunctor" type="checkbox" class="w-5 h-5 accent-secondary" />
                  <span class="font-label-lg text-label-lg text-on-surface font-semibold">Disyuntor diferencial y jabalina a tierra certificados</span>
                </div>
                <span class="material-symbols-outlined text-secondary text-[20px]">bolt</span>
              </label>

              <label class="flex items-center justify-between p-3.5 bg-surface-container-low rounded-xl border border-surface-container cursor-pointer">
                <div class="flex items-center gap-3">
                  <input v-model="checklist.fireExtinguisher" type="checkbox" class="w-5 h-5 accent-secondary" />
                  <span class="font-label-lg text-label-lg text-on-surface font-semibold">Matafuego reglamentario ABC con carga vigente en quincho</span>
                </div>
                <span class="material-symbols-outlined text-secondary text-[20px]">fire_extinguisher</span>
              </label>

              <label class="flex items-center justify-between p-3.5 bg-surface-container-low rounded-xl border border-surface-container cursor-pointer">
                <div class="flex items-center gap-3">
                  <input v-model="checklist.cbuMatchesDni" type="checkbox" class="w-5 h-5 accent-secondary" />
                  <span class="font-label-lg text-label-lg text-on-surface font-semibold">CBU bancario y Alias cotejados contra DNI del titular en mano</span>
                </div>
                <span class="material-symbols-outlined text-secondary text-[20px]">account_balance</span>
              </label>
            </div>

            <!-- Notas del Auditor -->
            <div>
              <label class="font-label-lg text-label-lg text-primary font-bold block mb-1.5">
                Dictamen y Observaciones del Auditor Técnico:
              </label>
              <textarea
                v-model="auditorNotes"
                rows="3"
                class="w-full p-3.5 rounded-xl bg-surface-container-low text-on-surface font-body-sm text-body-sm border border-surface-container focus:outline-none focus:ring-2 focus:ring-primary"
              ></textarea>
            </div>
          </section>
        </div>

        <!-- CERTIFICATE PREVIEW & APPROVE SIDEBAR (4 COLS) -->
        <aside class="lg:col-span-4 sticky top-24 flex flex-col gap-6">
          <div class="bg-surface-container-lowest p-6 rounded-2xl shadow-xl border border-surface-container flex flex-col gap-5">
            <div class="flex items-center justify-between pb-3 border-b border-surface-container">
              <span class="font-label-md text-label-md text-secondary font-bold flex items-center gap-1">
                <span class="material-symbols-outlined text-[18px]">qr_code_2</span>
                Certificado Oficial TS
              </span>
              <span class="px-2 py-0.5 rounded-full bg-secondary-fixed/40 text-on-secondary-fixed text-[11px] font-bold">
                Hash SHA-256
              </span>
            </div>

            <!-- Preview Card -->
            <div class="p-4 rounded-xl bg-primary text-on-primary flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <span class="text-[11px] text-secondary-fixed font-bold uppercase tracking-wider">SELLO IN SITU</span>
                <span class="text-[11px] text-surface-tint">SJ-RAW-2026</span>
              </div>
              <h3 class="font-headline-sm text-headline-sm text-on-primary font-bold leading-tight">
                Quinta Los Olivos
              </h3>
              <p class="text-xs text-on-primary/80">
                Apta para alquiler diurno hasta 25 personas. Titularidad bancaria validada presencialmente.
              </p>
              <div class="pt-2 border-t border-white/10 flex items-center justify-between text-[11px]">
                <span class="text-secondary-fixed font-semibold">Estado: Aprobado</span>
                <span class="font-mono text-white/60">a8f9c...31e4</span>
              </div>
            </div>

            <!-- Approve Button -->
            <button
              type="button"
              class="w-full py-3.5 px-4 rounded-xl bg-secondary text-on-secondary font-label-lg text-label-lg hover:bg-on-secondary-container transition-all shadow-md font-bold cursor-pointer flex items-center justify-center gap-2"
              @click="handleApprove"
            >
              <span class="material-symbols-outlined text-[20px]">verified</span>
              <span>Aprobar y Emitir Certificado</span>
            </button>
          </div>
        </aside>
      </div>
    </div>
  </main>
</template>
