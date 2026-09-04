# VNET Customer Satisfaction Survey

A customer satisfaction survey web app built for **PT. Victory Network Indonesia (VNET)**, an ISP providing fiber internet services. Customers fill it out after installation to rate their experience with the technician and sales team, and leave open-ended feedback.

Built with Laravel, Blade, and MySQL — no frontend framework, no build step required.

## Features

- Structured survey covering two service touchpoints: **technician installation** and **sales/onboarding**
- 1–5 star overall satisfaction rating
- Open-ended feedback field for improvement suggestions
- Optional respondent identification (name, phone/customer ID) for internal follow-up
- Server-side validation with Indonesian-language error messages
- Custom, brand-aligned UI — not a generic form template
- Fully responsive, accessible (semantic grouping, visible focus states, `prefers-reduced-motion` support)

## Tech Stack

| Layer      | Technology                          |
|------------|--------------------------------------|
| Backend    | Laravel (PHP)                        |
| Templating | Blade                                |
| Database   | SQLite                                |
| Frontend   | Vanilla CSS + JS (no build tooling)  |
| Fonts      | Space Grotesk, IBM Plex Sans, IBM Plex Mono |

## Survey Structure

| Section | Topic              | Questions | Type                     |
|---------|---------------------|-----------|--------------------------|
| A       | Technician service   | 5         | Single-choice pills      |
| B       | Sales service         | 4         | Single-choice pills      |
| C       | Overall satisfaction  | 1         | 1–5 star rating          |
| D       | Open feedback          | 1         | Free text (optional)     |

Every response is stored in a single `survey_responses` table, one row per submission.

## Getting Started

### Prerequisites

- PHP 8.2+ with the `pdo_sqlite` extension enabled
- Composer

### Installation

```bash
git clone <repo-url>
cd customer-satisfaction-survey
composer install
cp .env.example .env
php artisan key:generate
```

### Configure the database

The app uses SQLite — no separate database server needed. In `.env`:

```env
DB_CONNECTION=sqlite
```

Remove/comment out `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` if present;
they're unused for SQLite. Laravel looks for the database file at `database/database.sqlite`
by default. Make sure it exists:

```bash
touch database/database.sqlite   # macOS/Linux
# or on Windows:
New-Item database\database.sqlite -ItemType File
```

### Run migrations

```bash
php artisan migrate
```

### Serve the app

```bash
php artisan serve
```

Visit `http://127.0.0.1:8000/survey`.

### Deployment notes

Deployment itself is handled separately, but whoever sets up the server needs:

- PHP with `pdo_sqlite` enabled
- `database/database.sqlite` present and writable by the web server
- `storage/` and `bootstrap/cache/` writable
- `.env` on the server: `DB_CONNECTION=sqlite`, and `APP_URL` set to the real domain (not `localhost`)
- Document root pointed at `public/`, not the project root

Nothing in the app itself is hardcoded to `localhost` — all URLs and asset paths are generated
via Laravel's `route()` and `asset()` helpers, so it works unchanged on any domain once `APP_URL`
is set correctly.

## Routes

| Method | URI                     | Name             | Description                  |
|--------|--------------------------|------------------|-------------------------------|
| GET    | `/survey`                | `survey.create`  | Show the survey form           |
| POST   | `/survey`                | `survey.store`   | Validate and store a response  |
| GET    | `/survey/terima-kasih`  | `survey.thanks`  | Confirmation page after submit |

## Project Structure

```
app/
  Http/
    Controllers/SurveyController.php
    Requests/StoreSurveyResponseRequest.php
  Models/SurveyResponse.php
database/
  database.sqlite
  migrations/..._create_survey_responses_table.php
resources/
  views/
    layouts/survey.blade.php
    survey/index.blade.php
    survey/thanks.blade.php
public/
  css/survey.css
  js/survey.js
  images/vnet-logo.png
routes/
  survey.php
```

## Roadmap

- [ ] Admin view to browse and filter submitted responses
- [ ] CSV/Excel export of results
- [ ] Basic aggregate stats (average rating, response counts per option)

## License

Internal project for PT. Victory Network Indonesia. Not licensed for external redistribution.