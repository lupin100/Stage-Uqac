<?php

namespace App\Enum;

enum StudentDegreeEnum: string 
{
    case LICENCE = 'Licence';
    case MASTER = 'Master';
    case DOCTORAT = 'Doctorat';
    case POSTDOCTORAT = 'Postdoctorat';
    case STAGIAIRE = 'Stagiaire';
}