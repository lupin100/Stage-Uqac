<script setup>
import { ref } from 'vue'

const result = ref(null)
const error = ref(null)

const testConnection = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8001/api/ping')
    if (!response.ok) throw new Error('Erreur réseau')
    result.value = await response.json()
    error.value = null
  } catch (err) {
    error.value = err.message
    result.value = null
  }
}
</script>

<template>
  <div style="padding: 20px; text-align: center;">
    <h2>Test de communication</h2>
    <button @click="testConnection" style="padding: 10px 20px; cursor: pointer;">
      Interroger le Backend (Port 8001)
    </button>

    <div v-if="result" style="margin-top: 20px; color: green; border: 1px solid green; padding: 10px;">
      Succès ! <br>
      <strong>Message :</strong> {{ result.message }} <br>
      <strong>Heure du serveur :</strong> {{ result.date }}
    </div>

    <div v-if="error" style="margin-top: 20px; color: red; border: 1px solid red; padding: 10px;">
      Échec : {{ error }}
    </div>
  </div>
</template>