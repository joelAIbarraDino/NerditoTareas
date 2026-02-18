<?php

namespace App\Enums;

enum HomeworkChange:string
{
    case NoChange = 'Sin cambios';
    case InChange = 'Realizando cambio';
    case ChangeReady = 'Cambio realizado';
    case ChangeDelivered = 'Cambio entregado';
    case ChangeRequested = 'Cambio solicitado';

    public static function options(): array
    {
        return array_map(
            fn($case) => ['value' => $case->value, 'label' => ucfirst($case->value)],
            self::cases()
        );
    }
}
