# jobCol
A LinkedIn inspired job board website designed specifically for college students, built using Laravel.

## Features

- **User Authentication** — Register with a unique username and password (min 8 characters, hashed via bcrypt). Login and logout with Laravel session-based auth.
- **Separate Login & Registration Pages** — A clean sign-in page with a link to register, and vice versa.
- **Homepage** — Simple welcome page for authenticated users.
- **Navigation Bar** — Shared header with links to Jobs, Network, and Skills sections, plus logout.
- **Placeholder Pages** — Jobs, Network, and Skills routes are wired up and currently show an "Under Construction" page.
- **Blade Templating** — Base layout with reusable header and footer partials; all pages extend a single template.
- **Tailwind CSS v4** — Styled with Tailwind via Vite.

## Tech Stack

- **Backend:** Laravel 13 (PHP 8.4)
- **Frontend:** Blade templates, Tailwind CSS v4, Vite
- **Database:** MySQL 8.0
- **Dev Environment:** Laravel Sail (Docker)

## Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)

## Getting Started

1. Clone the repo and copy the environment file:
   ```bash
   git clone <repo-url> && cd jobCol
   cp .env.example .env
   ```

2. Start the containers:
   ```bash
   docker compose up -d
   ```

3. Install dependencies and generate the app key:
   ```bash
   docker compose exec laravel.test composer install
   docker compose exec laravel.test php artisan key:generate
   ```

4. Run migrations and build frontend assets:
   ```bash
   docker compose exec laravel.test php artisan migrate
   docker compose exec laravel.test npm install
   docker compose exec laravel.test npm run build
   ```

5. Visit [http://localhost](http://localhost)

## Useful Commands

| Task | Command |
|---|---|
| Start app | `docker compose up -d` |
| Stop app | `docker compose down` |
| Run artisan | `docker compose exec laravel.test php artisan <command>` |
| Run tests | `docker compose exec laravel.test php artisan test` |
| Vite dev server | `docker compose exec laravel.test npm run dev` |
| MySQL shell | `docker compose exec mysql mysql -u jobcol -pjobcol jobcol` |

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
