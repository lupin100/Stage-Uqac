<script setup>
// On remonte d'un dossier (../) pour aller chercher dans src/assets
import imageOrganigramme from '../assets/exemple-organigramme-pyramidal.png'
</script>

<template>
    <v-container class="py-10" max-width="1400">

        <v-row justify="center">
            <v-col cols="12">

                <h2 class="text-h2 mb-8">
                    À propos
                </h2>

                <p class="text-body-1 mb-4">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                    aliquip ex ea commodo consequat.
                </p>

                <p class="text-body-1 mb-4">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                    aliquip ex ea commodo consequat.
                </p>

                <p class="text-body-1 mb-4">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                    aliquip ex ea commodo consequat.
                </p>

                <p class="text-body-1 mb-4">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                    aliquip ex ea commodo consequat.
                </p>

            </v-col>

            

            <v-col cols="12">
                <h3 class="text-h3 mb-8 text-left">Composition actuelle : </h3>
                <v-img :src="imageOrganigramme" alt="Organigramme" max-width="1400" class="mx-auto"></v-img>
            </v-col>
            

        </v-row>
    </v-container>
</template>