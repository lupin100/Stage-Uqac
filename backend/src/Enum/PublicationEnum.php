<?php

namespace App\Enum;

enum PublicationEnum: string 
{
    case ARTICLE = 'Article';

    case ARTICLE_DE_CONFERENCE = 'Article de conférence';

    case CHAPITRE_DE_LIVRE = 'Chapitre de livre';

    case LIVRE = 'Livre';

    case MANUEL_USAGER = 'Manuel usager';

    case MISCELLANEOUS = 'Miscellaneous';
}