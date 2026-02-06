<?php

namespace App\Enums;

enum ConversionOrigin:string
{
    case Client = "Ya era cliente";
    case Facebook = "Facebook";
    case NewFacebook = "Facebook nuevo";
    case Messenger = "Messenger";
    case Google = "Google";
    case NewGoogle = "Google nuevo";
    case TikTok = "TikTok";
    case Email = "Email";

    public static function options(): array
    {
        return array_map(
            fn($case) => ['value' => $case->value, 'label' => ucfirst($case->value)],
            self::cases()
        );
    }
}
