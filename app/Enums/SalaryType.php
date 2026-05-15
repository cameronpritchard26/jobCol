<?php

namespace App\Enums;

enum SalaryType: string
{
    case Hourly   = 'hourly';
    case Annually = 'annually';

    public function label(): string
    {
        return match($this) {
            SalaryType::Hourly   => 'Hourly',
            SalaryType::Annually => 'Annually',
        };
    }
}
