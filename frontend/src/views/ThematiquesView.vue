<template>
    <v-container class="py-10" max-width="1400">

        <v-row justify="center">
            <v-col cols="12">

                <h2 class="text-h2 mb-8">
                    Thématiques
                </h2>

                <h3 class="text-h3 mb-8 bold">
                    Le génie informatique au GRI
                </h3>

                <p class="text-body-1 mb-4">
                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque
                    laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
                    architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas
                    sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione
                    voluptatem sequi nesciunt.
                </p>

                <v-list density="compact" class="bg-transparent">
                    <v-list-item>
                        <template #prepend>
                            <div class="square-bullet"></div>
                        </template>
                        <v-list-item-title>Mise à profit des technologies existantes et émergentes</v-list-item-title>
                    </v-list-item>

                    <v-list-item>
                        <template #prepend>
                            <div class="square-bullet"></div>
                        </template>
                        <v-list-item-title>Développement, la co-construction et l’avancement des
                            connaissances</v-list-item-title>
                    </v-list-item>

                    <v-list-item>
                        <template #prepend>
                            <div class="square-bullet"></div>
                        </template>
                        <v-list-item-title>Technologies socialement utiles</v-list-item-title>
                    </v-list-item>

                </v-list>

                <h4 class="text-h4 mb-8 bold">
                    Axes thématiques de recherche
                </h4>

                <p class="text-body-1 mb-4">
                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque
                    laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
                    architecto beatae vitae dicta sunt explicabo.
                </p>

                <v-list density="compact" class="bg-transparent">
                    <v-list-item>
                        <template #prepend>
                            <div class="square-bullet"></div>
                        </template>
                        <v-list-item-title>Architecture logicielle</v-list-item-title>
                    </v-list-item>

                    <v-list-item>
                        <template #prepend>
                            <div class="square-bullet"></div>
                        </template>
                        <v-list-item-title>Réseaux de télécommunication</v-list-item-title>
                    </v-list-item>

                    <v-list-item>
                        <template #prepend>
                            <div class="square-bullet"></div>
                        </template>
                        <v-list-item-title>Intelligence artificielle</v-list-item-title>
                    </v-list-item>

                    <v-list-item>
                        <template #prepend>
                            <div class="square-bullet"></div>
                        </template>
                        <v-list-item-title>Gestion de l’expérience utilisateur</v-list-item-title>
                    </v-list-item>

                    <v-list-item>
                        <template #prepend>
                            <div class="square-bullet"></div>
                        </template>
                        <v-list-item-title>Médecine comportementale</v-list-item-title>
                    </v-list-item>

                </v-list>

                <h4 class="text-h4 mb-8 bold">
                    Approches
                </h4>

                <p class="text-body-1 mb-4">
                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque
                    laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
                    architecto beatae vitae dicta sunt explicabo :
                </p>

                <v-list density="compact" class="bg-transparent">
                    
                    <v-list-item class="mb-4">
                        <v-list-item-title class="font-weight-bold text-wrap text-h6">
                            Conception fonctionnelle (Functional Design)
                        </v-list-item-title>

                        <v-list density="compact" class="bg-transparent pl-6 mt-2">
                            <v-list-item>
                                <template #prepend>
                                    <div class="square-bullet"></div>
                                </template>
                                <div class="text-body-1">
                                    C’est-à-dire, la définition des fonctions du système indépendamment de son implémentation technique.
                                </div>
                            </v-list-item>
                        </v-list>
                    </v-list-item>

                    <v-list-item class="mb-4">
                        <v-list-item-title class="font-weight-bold text-wrap text-h6">
                            Conception de l’expérience utilisateur (User Experience Design, UX Design)
                        </v-list-item-title>

                        <v-list density="compact" class="bg-transparent pl-6 mt-2">
                            <v-list-item>
                                <template #prepend>
                                    <div class="square-bullet"></div>
                                </template>
                                <div class="text-body-1">
                                    C’est-à-dire, les technologies, l’IA et les données sont mis à profit pour rendre l’expérience utilisateur plus efficace et plus personnalisée.
                                </div>
                            </v-list-item>
                        </v-list>
                    </v-list-item>

                    <v-list-item class="mb-4">
                        <v-list-item-title class="font-weight-bold text-wrap text-h6">
                            Conception d’ingénierie informatique (Computer Engineering Design)
                        </v-list-item-title>

                        <v-list density="compact" class="bg-transparent pl-6 mt-2">
                            <v-list-item>
                                <template #prepend>
                                    <div class="square-bullet"></div>
                                </template>
                                <div class="text-body-1">
                                    C’est-à-dire, les exigences du système sont analysées, implémentées en architectures matérielles et/ou logicielles et validées afin de réaliser un écosystème informatique performant et optimisé.
                                </div>
                            </v-list-item>
                        </v-list>
                    </v-list-item>

                </v-list>
            </v-col>

        </v-row>
    </v-container>
</template>

<style scoped>
.square-bullet {
    width: 8px;
    height: 8px;
    background-color: #9e9e9e;
    margin-right: 12px;
}
</style>