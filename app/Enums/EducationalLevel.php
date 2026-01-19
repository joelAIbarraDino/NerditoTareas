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
}
