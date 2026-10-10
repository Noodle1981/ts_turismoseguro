<script setup lang="ts">
import TheNavbar from './components/layout/TheNavbar.vue'
import TheFooter from './components/layout/TheFooter.vue'
</script>

<template>
  <div class="min-h-screen flex flex-col bg-surface-50 text-surface-900">
    <TheNavbar />
    <RouterView />
    <TheFooter />
  </div>
</template>
