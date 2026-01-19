<?php

namespace App\Enums;

enum HomeworkChange:string
{
    case NoChange = 'Sin cambios';
    case InChange = 'Realizando cambio';
    case ChangeReady = 'Cambio realizado';
    case ChangeDelivered = 'Cambio entregado';
    case ChangeRequested = 'Cambio solicitado';
}
