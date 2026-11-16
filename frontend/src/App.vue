<script setup>
import { ref } from 'vue'
import Partie1 from './components/Partie1.vue'
import Partie2 from './components/Partie2.vue'
import Partie3 from './components/Partie3.vue'

const parties = [
  { id: 1, label: 'Partie 1', composant: Partie1 },
  { id: 2, label: 'Partie 2', composant: Partie2 },
  { id: 3, label: 'Partie 3', composant: Partie3 },
]
const active = ref(1)
</script>

<template>
  <nav>
    <span class="brand">Vue.js &nbsp; Pays et villes</span>
    <div class="links">
      <a
        v-for="p in parties"
        :key="p.id"
        href="#"
        :class="{ active: active === p.id }"
        @click.prevent="active = p.id"
      >{{ p.label }}</a>
    </div>
  </nav>

  <!-- :key force Vue à recréer le composant à chaque changement de partie -->
  <component :is="parties.find(p => p.id === active).composant" :key="active" />
</template>
