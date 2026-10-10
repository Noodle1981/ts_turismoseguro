<script setup lang="ts">
import { RouterView } from 'vue-router'
import TheNavbar from './components/layout/TheNavbar.vue'
import TheFooter from './components/layout/TheFooter.vue'
</script>

<template>
  <div class="min-h-screen flex flex-col bg-background text-on-surface antialiased">
    <TheNavbar />
    <RouterView />
    <TheFooter />
  </div>
</template>
