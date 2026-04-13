<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { authStore } from '../../store/authStore'

const router = useRouter()

// États du formulaire
const username = ref('')
const password = ref('')
const isVisible = ref(false) // Pour afficher/masquer le mdp
const isLoading = ref(false)
const errorMessage = ref(null)

// Simulation de connexion
const handleLogin = async () => {
    errorMessage.value = null
    
    // VERIFICATION ADMIN
    if (username.value === 'admin' && password.value === 'admin!1234') {
        isLoading.value = true
        await new Promise(resolve => setTimeout(resolve, 1000)) // Simulation
        
        authStore.login()
        router.push('/admin/dashboard') // Redirection vers ta page de test
    } else {
        errorMessage.value = "Identifiants administrateur incorrects."
    }
}
</script>

<template>
    <v-container class="login-container fill-height" fluid>
        <v-row align="center" justify="center" no-gutters>
            <v-col cols="12" sm="8" md="5" lg="4">
                
                <v-card class="elevation-12 pa-6" rounded="xl">
                    <v-card-item class="text-center mb-10">
                        <v-icon color="primary" size="64" class="mb-2">mdi-lock-shield</v-icon>
                        <v-card-title class="text-h5 font-weight-bold">
                            Accès Administration
                        </v-card-title>
                    </v-card-item>

                    <v-card-text>
                        <v-form @submit.prevent="handleLogin">
                            <v-text-field
                                v-model="username"
                                label="Nom d'utilisateur"
                                prepend-inner-icon="mdi-account"
                                variant="outlined"
                                color="primary"
                                class="mb-2"
                                required
                            ></v-text-field>

                            <v-text-field
                                v-model="password"
                                label="Mot de passe"
                                prepend-inner-icon="mdi-lock"
                                :append-inner-icon="isVisible ? 'mdi-eye-off' : 'mdi-eye'"
                                :type="isVisible ? 'text' : 'password'"
                                variant="outlined"
                                color="primary"
                                @click:append-inner="isVisible = !isVisible"
                                required
                            ></v-text-field>

                            <v-alert
                                v-if="errorMessage"
                                type="error"
                                variant="tonal"
                                density="compact"
                                class="mt-2"
                            >
                                {{ errorMessage }}
                            </v-alert>

                            <v-btn
                                type="submit"
                                color="primary"
                                block
                                size="large"
                                class="mt-6 font-weight-bold"
                                :loading="isLoading"
                                rounded="pill"
                            >
                                Se connecter
                            </v-btn>
                        </v-form>
                    </v-card-text>
                    
                    <v-card-actions class="justify-center">
                        <v-btn variant="text" size="small" to="/" color="grey-darken-1">
                            Retour au site
                        </v-btn>
                    </v-card-actions>
                </v-card>

            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped>
.login-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    display: flex;
    align-items: center;
}
</style>