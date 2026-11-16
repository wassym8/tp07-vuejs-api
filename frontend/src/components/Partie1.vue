<!-- Partie 1 : rechercher un pays par son identifiant (API Fetch) -->
<script setup>
import { ref } from 'vue'
import { API } from '../config'

const idSaisi = ref('')
const resultat = ref('')
const erreur = ref('')
const chargement = ref(false)

async function rechercher() {
  resultat.value = ''
  erreur.value = ''
  const valeur = idSaisi.value.trim()

  // Vérifications côté frontend : la requête n'est pas envoyée si invalide
  if (valeur === '') {
    erreur.value = "Veuillez saisir l'identifiant du pays."
    return
  }
  if (!/^-?\d+$/.test(valeur)) {
    erreur.value = "L'identifiant doit être un nombre entier."
    return
  }

  chargement.value = true
  try {
    const reponse = await fetch(`${API}/partie1_pays_par_id.php?id=${encodeURIComponent(valeur)}`)
    const data = await reponse.json()

    if (data.success) {
      resultat.value = data.nom
    } else {
      erreur.value = "Le pays n'a pas été trouvé."
    }
  } catch (e) {
    erreur.value = 'Erreur de communication avec le serveur.'
  } finally {
    chargement.value = false
  }
}
</script>

<template>
  <div class="card">
    <h1>Rechercher un pays par son identifiant</h1>

    <form @submit.prevent="rechercher" novalidate>
      <label for="id">Identifiant du pays</label>
      <input id="id" v-model="idSaisi" placeholder="Ex : 1" />
      <button type="submit" :disabled="chargement">Rechercher</button>
    </form>

    <div v-if="erreur" class="msg err">{{ erreur }}</div>
    <div v-if="resultat" class="msg ok">Pays trouvé : <strong>{{ resultat }}</strong></div>
  </div>
</template>
