<?php

namespace App\Enum;

enum EventEnum: string 
{
    case SEMINAIRE = 'Séminaire';
    case CONGRES = 'Congrès';
    case ATELIER = 'Atelier';
}