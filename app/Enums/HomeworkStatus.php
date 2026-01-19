<?php

namespace App\Enums;

enum HomeworkStatus:string
{
    case Delivered = "Entregado";
    case Completed = "Completado";
    case Assigned = "Asignado";
    case InChanges = "Con cambios";
    case Unassigned = "No asignado";
    case unpaid = "No pago";
}
