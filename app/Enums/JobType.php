<?php

namespace App\Enums;

enum JobType: string
{
    case FullTime   = 'full_time';
    case PartTime   = 'part_time';
    case Internship = 'internship';
    case Contract   = 'contract';

    public function label(): string
    {
        return match($this) {
            JobType::FullTime   => 'Full Time',
            JobType::PartTime   => 'Part Time',
            JobType::Internship => 'Internship',
            JobType::Contract   => 'Contract',
        };
    }
}
