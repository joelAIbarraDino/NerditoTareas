<?php

namespace App\Enums;

enum EducationalLevel:string
{
    case ElementarySchool = 'Primaria';
    case MiddleSchool = 'Secundaria';
    case HighSchool = 'Preparatoria';
    case University = 'Universidad';
    case MastersDegree = 'Maestria';
    case Doctorate = 'Doctorado';

    public static function options(): array
    {
        return array_map(
            fn($case) => ['value' => $case->value, 'label' => ucfirst($case->value)],
            self::cases()
        );
    }
}
