# Registration and Login Flow

## Overview

This document outlines the registration and login flow for the JobCol application.

## Topics
- [Account Types](#account-types)
- [User Registration Flow](#registration-flow)
- [User Login Flow](#login-flow)

## Relevant Files
- [User.php (User account model)](../app/Models/User.php)
- [AuthController.php (Authentication controller)](../app/Http/Controllers/AuthController.php)
- [AccountType.php (Account type enum)](../app/Enums/AccountType.php)

## User Account Model
- username (string)
- email (string, currently unused)
- password (string)
- account_type (AccountType)

## Account Types
The account types are defined in the [AccountType.php](../app/Enums/AccountType.php) enum. The app currently supports three types of users:
- Students: Users who are looking for jobs and networking opportunities
- Employer: Users who are looking to post jobs and find candidates
- Admin: Users who can manage application content and user accounts

## Registration Flow
The registration flow is handled by the [AuthController.php](../app/Http/Controllers/AuthController.php) controller. The flow is as follows:
1. User navigates to the home login page
2. User clicks the "Register" link on the bottom of the page, which redirects them to the registration page
3. User selects if they are a student or an employer (admin registration is handled separately)
4. User is redirected to the registration page form
5. User enters their username and password, then confirms their password
6. User submits the form
7. The controller takes the request data and validates it
    - Username must be unique and less than 255 characters long
    - Password must be at least 8 characters long
    - Password must be confirmed
8. If the validation fails, the user is redirected back to the registration page with the errors
9. If the validation passes, the user account is created, stored, and the user is logged in and taken to the primary home page

## Login Flow
The login flow is handled by the [AuthController.php](../app/Http/Controllers/AuthController.php) controller. The flow is as follows:
1. User navigates to the login page
2. User enters their username and password
3. User submits the form
4. The controller validates the input and authenticates the user
5. If successful, the user is redirected to the home page
6. If unsuccessful, the user is redirected back to the login page with an error message