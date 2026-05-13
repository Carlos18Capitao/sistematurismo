# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Sistur Angola** — A tourism booking platform for Angola, built with Laravel 13. Users browse tours, make bookings, and submit reviews. Admins manage tours, confirm bookings, and moderate reviews.

## Commands

```bash
# First-time setup
composer install && cp .env.example .env && php artisan key:generate
php artisan migrate --seed
php artisan storage:link

# Development (runs server + queue + logs + Vite concurrently)
composer dev

# Frontend only
npm run dev       # watch mode
npm run build     # production build

# Testing
composer test                                        # all 46 tests (clears config cache first)
php artisan test --testsuite=Feature                 # feature tests only
php artisan test --testsuite=Unit                    # unit tests only
php artisan test --filter BookingServiceTest         # single class

# Code style
./vendor/bin/pint                                    # format with Laravel Pint
```

Default dev server runs on `http://localhost:8000`. Demo credentials after seeding:
- Admin: `admin@sistur.ao` / `password`
- User: `joao@exemplo.ao` / `password`

## Architecture

The app uses a layered architecture: **Controller → Service → Repository → Eloquent**.

### Layers

**Controllers** (`app/Http/Controllers/`) handle HTTP. There are three namespaces:
- Root-level (`HomeController`, `TourController`, `BookingController`, `ReviewController`) — public-facing pages
- `Auth/` — Laravel Breeze auth (login, register, password reset, Google OAuth via Socialite)
- `Admin/` — gated by `AdminMiddleware`; manages tours, bookings, review moderation

**Services** (`app/Services/`) contain all business logic:
- `TourService` — tour CRUD, image uploads to `storage/app/public/tours/`, rating recalculation
- `BookingService` — booking creation/cancellation inside DB transactions; decrements/restores `available_spots` on Tour
- `ReviewService` — enforces one-review-per-tour-per-user (only for users with a `concluido` booking), admin approval, recalculates `rating_average`/`reviews_count` on Tour

**Repositories** (`app/Repositories/`) abstract Eloquent queries behind interfaces in `Contracts/`. Currently covers `TourRepository` and `BookingRepository`. New repositories must be bound in a service provider.

### Key Models

| Model | Notable details |
|-------|----------------|
| `Tour` | Route key is `slug`. JSON columns: `images`, `includes`, `excludes`, `highlights`. Scopes: `active()`, `featured()`, `byCity()`, `byCategory()`. Soft deletes enabled. |
| `Booking` | Auto-generated reference `SIS-XXXXXXXX`. Status enum: `pendente → confirmado / cancelado / concluido`. Payment status: `pendente / pago`. Soft deletes enabled. |
| `Review` | Requires `is_approved = true` to appear publicly. Rating 1–5. Linked to a specific `booking_id`. |
| `User` | `role` enum (`admin` / `user`). `isAdmin()` helper. `google_id` for OAuth. |

### Routes

- **Public:** `/`, `/tours`, `/tours/{slug}`
- **Auth-required:** `/reservas/*`, `/avaliacoes`, `/profile`
- **Admin (prefix `/admin`, named `admin.*`):** tours CRUD, bookings confirm/cancel, review approve/destroy
- Auth routes live in `routes/auth.php`; everything else in `routes/web.php`

## Testing

Tests run against SQLite in-memory (`DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`). Cache and session use `array` driver in tests. All queue jobs run synchronously (`QUEUE_CONNECTION=sync`).

Feature tests use `RefreshDatabase`. Unit tests (e.g., `BookingServiceTest`) instantiate services directly with mocked repositories.

## Tech Stack

- **Backend:** PHP 8.3+, Laravel 13
- **Frontend:** Blade templates, Alpine.js 3.x (interactivity), Tailwind CSS 3.x, Vite 8
- **Database:** MySQL 8+ (production), SQLite in-memory (tests)
- **Auth:** Laravel Breeze (Blade flavor) + Laravel Socialite (Google OAuth)
- **Currency:** Angolan Kwanza (AOA) — formatted via `formatted_price` / `formatted_total` accessors
- **Locale:** `pt` (Portuguese)
