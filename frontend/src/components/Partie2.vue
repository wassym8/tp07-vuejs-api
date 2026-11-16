<!-- Partie 2 : pays et villes correspondantes (API Fetch) -->
<script setup>
import { ref, onMounted } from 'vue'
import { API } from '../config'

const pays = ref([])
const villes = ref([])
const idPays = ref('')   // '' = aucun pays sélectionné
const idVille = ref('')  // '' = aucune ville sélectionnée
const erreur = ref('')

async function chargerPays() {
  try {
    const r = await fetch(`${API}/pays.php`)
    pays.value = await r.json()
  } catch (e) {
    erreur.value = 'Impossible de charger les pays.'
  }
}

async function chargerVilles() {
  idVille.value = ''
  villes.value = []
  try {
    const r = await fetch(`${API}/villes.php?id_pays=${idPays.value}`)
    villes.value = await r.json()
  } catch (e) {
    erreur.value = 'Impossible de charger les villes.'
  }
}

onMounted(chargerPays)
</script>

<template>
  <div class="card">
    <h1>Pays et villes</h1>
    <p class="hint">Sélectionnez un pays et une ville.</p>

    <label for="pays">Pays</label>
    <select id="pays" v-model="idPays" @change="chargerVilles">
      <option value="" disabled>-- Choisir un pays --</option>
      <option v-for="p in pays" :key="p.id_pays" :value="p.id_pays">{{ p.nom }}</option>
    </select>

    <label for="ville">Ville</label>
    <select id="ville" v-model="idVille" :disabled="!idPays">
      <option value="" disabled>-- Choisir une ville --</option>
      <option v-for="v in villes" :key="v.id_ville" :value="v.id_ville">{{ v.nom }}</option>
    </select>

    <div v-if="erreur" class="msg err">{{ erreur }}</div>
  </div>
</template>
