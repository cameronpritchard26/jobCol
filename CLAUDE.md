# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Environment

This project runs via **Laravel Sail** (Docker). All `artisan`, `composer`, and `npm` commands must be run inside the container:

```bash
# Start containers
docker compose up -d

# Run dev server (Laravel + Vite HMR concurrently)
docker compose exec laravel.test composer run dev

# Or run Vite separately
docker compose exec laravel.test npm run dev
```

First-time setup:
```bash
docker compose up -d
docker compose exec laravel.test composer run setup
```

## Common Commands

```bash
# Tests
docker compose exec laravel.test php artisan test
docker compose exec laravel.test php artisan test tests/Feature/ExampleTest.php
docker compose exec laravel.test php artisan test --filter=testMethodName

# Linting (Laravel Pint)
docker compose exec laravel.test composer exec pint

# Production asset build
docker compose exec laravel.test npm run build

# Artisan
docker compose exec laravel.test php artisan migrate
docker compose exec laravel.test php artisan tinker
```

## Architecture Overview

**jobCol** is a LinkedIn-style job board for college students. Built with Laravel 13, Blade + Tailwind CSS v4, Vite, and MySQL via Sail.

### Authentication & Account Types

Laravel session-based auth. The `User` model has an `account_type` column using the `AccountType` enum (`student` / `employer` / `admin`). Login uses `username` (not email).

The custom `EnsureAccountType` middleware restricts routes to a specific account type (e.g., `middleware('account_type:student')`).

### Data Model

```
User (1) ──── (1) StudentProfile ──── (many) EducationEntry
                                  ──── (many) ExperienceEntry
                                  ──── (many) Connection (sender_id / receiver_id, status: pending|accepted)

User (1) ──── (1) EmployerProfile ──── (many) Job
```

`StudentProfile::connections()` returns a merged collection of accepted connections in both directions (sender or receiver).

`EducationEntry` is ordered by `end_year DESC, start_year DESC`. `ExperienceEntry` is ordered by `end_year DESC, start_year DESC`.

### Routing Structure

| Group | Middleware | Description |
|---|---|---|
| Guest routes | `guest` | Login, register |
| Authenticated routes | `auth` | Home, profile CRUD, picture upload, network search |
| Student routes | `auth` + `account_type:student` | Education/experience CRUD, connection actions |

Profile routing is dispatched through a single `ProfileController` that delegates to student or employer logic based on the authenticated user's account type.

### Controllers

- `AuthController` — login, register, logout
- `HomeController` — dashboard and stub pages
- `ProfileController` — dispatches to student/employer profile views and CRUD
- `ProfilePictureController` — upload, status poll, delete
- `NetworkController` — user search, public student/employer profile views
- `ConnectionController` — send, accept, reject, remove connections
- `EducationController` / `ExperienceController` — student profile entries
- `JobController` — job listings (in progress)

### Frontend

Blade templates with Tailwind CSS v4 (no config file — uses CSS-first config). Vite handles asset bundling with HMR in development. No JS framework; Alpine.js or vanilla JS is used for interactivity where needed.

### Testing

Pest PHP v4 with the Laravel plugin. Test environment uses an in-memory SQLite DB (`DB_DATABASE=testing`), array cache/session, and `BCRYPT_ROUNDS=4`. Tests live in `tests/Feature` and `tests/Unit`.
