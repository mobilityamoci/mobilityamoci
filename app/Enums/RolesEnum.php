<?php

namespace App\Enums;

enum RolesEnum: string
{
    case ADMIN = 'Admin';
    case MM_PROVINCIALE = 'MMProvinciale';
    case MM_SCOLASTICO = 'MMScolastico';
    case INSEGNANTE = 'Insegnante';
    case STUDENTE = 'Utente Base';
}
