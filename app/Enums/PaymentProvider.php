<?php

namespace App\Enums;

enum PaymentProvider:string
{
    case MercadoPago = "Mercado pago";
    case PayPal = "PayPal";
    case Stripe = "Stripe";
}
