<?php

namespace App\Enums;

enum PaymentStatus:string
{
    case Pending = 'Pendiente';
    case Reject = 'Rechazado';
    case Approved = 'Aprobado';
}
