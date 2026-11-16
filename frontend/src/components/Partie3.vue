<!-- Partie 3 : température de la ville (Axios + Open-Meteo via le backend) -->
<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { API } from '../config'

const pays = ref([])
const villes = ref([])
const idPays = ref('')
const idVille = ref('')
const meteo = ref(null)
const erreur = ref('')
const chargement = ref(false)

async function chargerPays() {
  try {
    const { data } = await axios.get(`${API}/partie3/pays.php`)
    pays.value = data
  } catch (e) {
    erreur.value = 'Impossible de charger les pays.'
  }
}

async function chargerVilles() {
  idVille.value = ''
  villes.value = []
  meteo.value = null
  erreur.value = ''
  try {
    const { data } = await axios.get(`${API}/partie3/villes.php`, {
      params: { id_pays: idPays.value },
    })
    villes.value = data
  } catch (e) {
    erreur.value = 'Impossible de charger les villes.'
  }
}

async function chargerMeteo() {
  meteo.value = null
  erreur.value = ''
  chargement.value = true
  try {
    const { data } = await axios.get(`${API}/partie3/meteo.php`, {
      params: { id_ville: idVille.value },
    })
    meteo.value = data
  } catch (e) {
    erreur.value = e.response?.data?.message || 'Impossible de récupérer la météo.'
  } finally {
    chargement.value = false
  }
}

onMounted(chargerPays)
</script>

<template>
  <div class="card">
    <h1>Température de la ville</h1>

    <label for="pays">Pays</label>
    <select id="pays" v-model="idPays" @change="chargerVilles">
      <option value="" disabled>-- Choisir un pays --</option>
      <option v-for="p in pays" :key="p.id_pays" :value="p.id_pays">{{ p.nom }}</option>
    </select>

    <label for="ville">Ville</label>
    <select id="ville" v-model="idVille" :disabled="!idPays" @change="chargerMeteo">
      <option value="" disabled>-- Choisir une ville --</option>
      <option v-for="v in villes" :key="v.id_ville" :value="v.id_ville">{{ v.nom }}</option>
    </select>

    <div v-if="chargement" class="msg ok">Chargement de la météo...</div>
    <div v-if="erreur" class="msg err">{{ erreur }}</div>

    <div v-if="meteo" class="meteo">
      <div>{{ meteo.ville }} ({{ meteo.pays }})</div>
      <div class="temp">{{ meteo.temperature }} °C</div>
      <small>
        Ressenti : {{ meteo.ressenti }} °C &bull; Min : {{ meteo.min }} °C &bull;
        Max : {{ meteo.max }} °C &bull; Vent : {{ meteo.vent }} km/h
      </small>
      <small>Coordonnées : {{ meteo.latitude }}, {{ meteo.longitude }}</small>
    </div>
  </div>
</template>
