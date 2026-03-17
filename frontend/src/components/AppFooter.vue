<template>
    <v-footer color="black" class="py-4" app absolute>
        <v-row no-gutters justify="space-between" align="center" class="w-100 px-4">

            <v-col cols="12" md="auto" class="text-center text-md-left mb-2 mb-md-0">
                <span class="text-white">
                    <a href="https://www.uqac.ca/" target="_blank" class="footer-link">
                        UQAC - Université du Québec à Chicoutimi
                    </a>

                    |

                    <router-link to="/" class="footer-link">
                        Groupe de Recherche en Informatique (GRI)
                    </router-link>
                </span>
            </v-col>

            <v-col cols="12" md="auto" class="text-center text-md-right">
                <span class="text-white">
                    <a href="https://www.uqac.ca/confidentialite/" target="_blank" class="footer-link">Conditions
                        d'utilisation</a>
                    -
                    <a href="https://www.uqac.ca/mgestion/chapitre-2/reglement-sur-lorganisation-institutionnelle-et-la-regie-interne/politique-de-confidentialite-concernant-les-renseignements-personnels-recueillis-par-moyens-technologiques/"
                        target="_blank" class="footer-link">Politique de confidentialité</a>
                    -
                    <a href="" target="_blank" class="footer-link">Paramètres des témoins</a>
                </span>
            </v-col>

        </v-row>
    </v-footer>
</template>

<style scoped>
/* Un petit style pour rendre les liens propres sur fond noir */
.footer-link {
    color: white;
    /* Garde le texte blanc */
    text-decoration: none;
    /* Enlève le vilain soulignement bleu par défaut */
    transition: color 0.2s;
    /* Petite animation douce */
}

/* Effet quand on passe la souris dessus */
.footer-link:hover {
    color: #6B8915;
}
</style>