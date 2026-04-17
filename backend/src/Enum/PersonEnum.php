<?php

namespace App\Enum;

enum PersonEnum: string 
{
    case MEMBRE_REGULIER = 'Membre régulier';

    case MEMBRE_REGULIER_COMMITE = 'Membre régulier du comité exécutif';

    case MEMBRE_ASSOCIE = 'Membre associé';

    case MEMBRE_EMERITE = 'Membre émérite';

    case COLLABORATEUR = 'Collaborateur';

    case ETUDIANT = 'Etudiant';

    case ANCIEN_ETUDIANT = 'Ancien étudiant';
}