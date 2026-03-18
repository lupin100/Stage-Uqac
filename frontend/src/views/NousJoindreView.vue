<template>
    <v-container class="py-10 fill-height">

        <v-row align="center">

            <v-col cols="12" md="6">
                <v-sheet class="pa-6 bg-transparent d-flex flex-column justify-center" height="100%">

                    <h2 class="text-h2 font-weight-bold mb-8">Nous joindre</h2>

                    <h3 class="text-h3 font-weight-bold mb-2">Coordonnées du département</h3>
                    <p class="text-body-1 mb-4">
                        Décanat de la recherche, de la création et de l’innovation
                    </p>

                    <p class="text-body-1 mb-4">
                        Laboratoire GRI <br>
                        Département informatique
                    </p>

                    <p class="text-body-1 mb-4">
                        Université du Québec à Chicoutimi <br>
                        555 boul. de l’Université <br>
                        Saguenay, QC, G7H 2B1
                    </p>

                    <p class="text-body-1 mb-4">
                        Local PX-XXXX
                    </p>

                    <h3 class="text-h3 font-weight-bold mt-8 mb-2">Courriel</h3>
                    <p class="text-body-1 mb-4">
                        <a href="mailto:drc_direction@uqac.ca"
                            class="text-primary text-decoration-none font-weight-bold">
                            drc_direction@uqac.ca
                        </a>
                    </p>

                </v-sheet>
            </v-col>

            <v-col cols="12" md="6">
                <v-sheet class="pa-0 bg-transparent" height="100%" min-height="400">
                    <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                        src="https://www.openstreetmap.org/export/embed.html?bbox=-71.0537%2C48.4170%2C-71.0450%2C48.4215&amp;layer=mapnik&amp;marker=48.4192%2C-71.0494"
                        style="border: 1px solid #ccc; border-radius: 8px; min-height: 400px;">
                    </iframe>
                    <br />
                    <small>
                        <a href="https://www.openstreetmap.org/?mlat=48.4192&amp;mlon=-71.0494#map=17/48.4192/-71.0494"
                            target="_blank" class="text-caption text-grey text-decoration-none">
                            Voir la carte en plus grand
                        </a>
                    </small>
                </v-sheet>
            </v-col>

        </v-row>
    </v-container>
</template>