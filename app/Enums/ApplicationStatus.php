<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case Submitted = 'submitted';
    case Accepted  = 'accepted';
    case Rejected  = 'rejected';

    public function label(): string
    {
        return match($this) {
            ApplicationStatus::Submitted => 'Submitted',
            ApplicationStatus::Accepted  => 'Accepted',
            ApplicationStatus::Rejected  => 'Rejected',
        };
    }
}
