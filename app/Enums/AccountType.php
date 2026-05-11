<?php

namespace App\Enums;

enum AccountType: string
{
    case Student = 'student';
    case Employer = 'employer';
    case Admin = 'admin';
}
