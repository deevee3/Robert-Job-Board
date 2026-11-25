# Factory Laravel Starter Kit

Every factory app starts here. A Laravel 12 skeleton with **WorkOS AuthKit**
sign-in, **Stripe Cashier** billing, a **shared iMac-hosted database**, and
**per-app branding** already wired in — so a factory app starts from a working
app, not from an empty folder.

## What is in the kit

| Piece | What it does |
|---|---|
| Auth | WorkOS AuthKit sign-in (email, social, magic links, passkeys, SSO). No local passwords. `AuthController` exchanges the WorkOS code, creates/updates the local user, and starts a Laravel session. Protected routes use Laravel's `auth` middleware. |
| Billing | Laravel Cashier (Stripe). Checkout subscriptions, billing portal, cancel-at-period-end, webhook endpoint (`POST /stripe/webhook`, handled by Cashier). Billing page shows status and stays honest when Stripe keys are still placeholders. |
| Database | Shared iMac-hosted Postgres. Each app's tables live in their own schema (`DB_SCHEMA`), so apps sharing one database never collide. An env flag connects or disconnects an app at will. |
| Branding | Theme config (`config/theme.php` + env overrides). Name, logo, colors, fonts. The base pages read it, so each app looks like itself without forking the kit. |
| Pages | Landing (`/`), sign-in (`/login`), dashboard (`/dashboard`), settings (`/settings`), billing (`/billing`). Plain CSS in the layout — no frontend build step, no npm required. |
| Migrations | Users (+ `workos_id`, nullable password), sessions, cache, jobs, the app-schema creator, and Cashier's columns/tables (shipped directly — see the gotcha below). |
| Command | `php artisan factory:db-status` — shows connected/disconnected, schema, and whether the database is reachable. |

## The pipeline this kit sits in

```
PRD → preproduction branding → review → Orbi builds
```

**Branding is a preproduction step.** It happens before the build, not after
it. The handoff works like this:

1. **Preproduction branding** produces the brand package for the app:
   app name, logo file, color palette (with hex values), fonts, and
   voice/tone. Every PRD carries a Brand section with these values, and
   verification fails on a generic or copied brand — no app reaches Orbi
   without its identity settled.
2. **The theme config carries those values.** Set them in the app's `.env`
   (`APP_NAME`, `APP_LOGO`, `THEME_PRIMARY`, `THEME_ACCENT`, …) or edit
   `config/theme.php` directly. Drop the logo file at
   `public/images/logo.svg` (or set `APP_LOGO` to its path).
3. **The base pages render the app's identity.** Landing, sign-in,
   dashboard, settings, and billing all read `config/theme.php` — name,
   logo, colors, fonts — so the app looks like itself from the first run.

The kit stays **brand-neutral** until the branding step's values are applied.
No per-app code branches, no copied layouts: brand it through the config,
then build the features.

## Setup

Requirements: PHP 8.2+, Composer, and — for the shared database — network
access to the iMac-hosted Postgres. No other services are needed to start.

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan factory:db-status
php artisan serve
```

Open `http://localhost:8000`. The landing page loads without any credentials.
Sign-in and billing need the steps below first.

## WorkOS setup (sign-in)

1. In the WorkOS dashboard, create an application (or reuse the factory's)
   and copy the **Client ID** and **API Key** into `.env`
   (`WORKOS_CLIENT_ID`, `WORKOS_API_KEY`).
2. Under **Redirects** in the WorkOS dashboard, add:
   - Sign-in callback: `http://localhost:8000/authenticate` (and the
     production URL later — it must match `WORKOS_REDIRECT_URL` exactly).
   - Homepage URL: `http://localhost:8000/`
   - Logout redirect URL: `http://localhost:8000/`
3. The session is Laravel's own (see `SESSION_LIFETIME` in `.env`);
   WorkOS holds no session on the kit's behalf.

Each factory app should have its own WorkOS application (own client ID), so
sign-in, redirect URLs, and SSO settings stay per-app.

## Stripe setup (billing)

1. In the Stripe dashboard, copy the **publishable key** and **secret key**
   into `.env` (`STRIPE_KEY`, `STRIPE_SECRET`). Use **test keys**
   (`pk_test_` / `sk_test_`) until launch.
2. Create the app's products and prices in Stripe, then paste the Price IDs
   into `STRIPE_PRICE_MONTHLY` and `STRIPE_PRICE_YEARLY`.
3. Set up the webhook endpoint. Cashier handles `POST /stripe/webhook`
   itself (subscription lifecycle, invoices). Point Stripe at
   `https://your-app-domain/stripe/webhook` and paste the signing secret
   into `STRIPE_WEBHOOK_SECRET`. Locally, use the Stripe CLI:
   `stripe listen --forward-to localhost:8000/stripe/webhook`.
4. The billing page shows "Billing isn't set up yet" until real keys are
   in place — checkout stays off rather than failing.

**Gotcha: do not publish Cashier's migrations.** The kit already ships
Cashier's columns (`stripe_id`, `pm_type`, `pm_last_four`,
`trial_ends_at` on users) and tables (`subscriptions`,
`subscription_items`) as its own migrations. Running
`php artisan vendor:publish --tag="cashier-migrations"` on top of them
would try to create the same columns and tables twice. Custom webhook
handling belongs on the `WebhookReceived` event Cashier dispatches for
every payload — see `AppServiceProvider`.

## Shared database: connect / disconnect

The factory backend is **one shared database, hosted on the iMac**
(Postgres is already running there). Each app connects to it or
disconnects from it at will.

**Connect** (the normal case):

```ini
SHARED_DB_ENABLED=true
DB_CONNECTION=pgsql
DB_HOST=imac-db-host
DB_PORT=5432
DB_DATABASE=factory_shared
DB_USERNAME=factory_app
DB_PASSWORD=change-me
DB_SCHEMA=app_slug
```

`DB_SCHEMA` is the per-app namespace: one unique schema per app, use the
app slug (e.g. `app05`, `brain-shield`). The first migration creates the
schema if it does not exist. All of the app's tables — users, sessions,
subscriptions, and the app's own tables — live in that schema.

**Disconnect** (local dev, offline work, or an app that keeps its own data):

```ini
SHARED_DB_ENABLED=false
```

The app then runs on local SQLite (`database/database.sqlite`). Nothing
else changes — same code, same migrations, same pages. Flip the flag back
to reconnect.

Check the current state any time:

```bash
php artisan factory:db-status
```

The kit **only connects** to the database. It never installs, starts, or
manages the database server — that is a separate setup step, already done
on the iMac.

## Branding an app (preproduction handoff)

1. Take the brand package from the preproduction branding step: app name,
   logo file, palette (hex), fonts, voice/tone.
2. Set the values in the app's `.env` (or `config/theme.php`):
   `APP_NAME`, `APP_TAGLINE`, `APP_LOGO`, `THEME_PRIMARY`,
   `THEME_ACCENT`, `THEME_BACKGROUND`, `THEME_SURFACE`, `THEME_TEXT`,
   `THEME_MUTED`, `THEME_FONT_SANS`, `THEME_FONT_DISPLAY`.
3. Drop the logo file at `public/images/logo.svg` (or point `APP_LOGO`
   at its path). The file shipped here is a neutral placeholder — replace
   it, don't ship it.
4. Done. The base pages pick the brand up automatically.

## How a factory app starts from the kit

1. Copy this `starter-kit/` directory into the app's project folder
   (`projects/NN/`), keeping the structure as-is.
2. Set the app's identity: `APP_NAME`, `APP_SLUG` (the project folder
   name), `DB_SCHEMA` (same slug), and the theme values from the
   preproduction branding step. Drop in the logo.
3. Set up the app's WorkOS application and Stripe products/prices (see
   above) and fill in `.env`. Never commit a filled-in `.env`.
4. Add the app's features per its PRD: routes in `routes/web.php`,
   controllers in `app/Http/Controllers`, pages in `resources/views`,
   tables in `database/migrations`, models in `app/Models`.
5. Run the checks: `php artisan migrate`, `php artisan factory:db-status`,
   `php artisan route:list`, then sign in and subscribe in test mode
   before calling the app done.

## What a factory app still needs to add per app

- Its features, per its PRD (pages, routes, controllers, models,
  migrations). The kit is the foundation, not the product.
- Its own WorkOS application (client ID, redirect URLs for its domain)
  and its own Stripe products/prices (Price IDs in `.env`).
- Its production host: any PHP 8.2+ host that can reach the shared
  database (or run disconnected). Set `APP_DEBUG=false`,
  `SESSION_SECURE_COOKIE=true`, and a real `APP_KEY` in production.
  `php artisan serve` is for development only.
- Any per-app webhook handling beyond Cashier's defaults, via the
  `WebhookReceived` event.

## Verification notes (honest)

Verified live on the build machine (PHP 8.3.6, Composer 2.11.2,
Nov 21, 2026):

- `composer validate` passes; `composer install` resolves and installs
  Laravel 12.73.0, Cashier 15.8.0, workos-php 4.32.0
  (`composer.lock` is committed).
- `php -l` passes on every PHP file in the kit.
- `php artisan migrate` runs all 6 migrations on SQLite in
  disconnected mode (`SHARED_DB_ENABLED=false`); the app-schema
  migration correctly no-ops on non-Postgres. A bug found during
  this check: the sqlite connection read `DB_DATABASE` (the shared
  Postgres database name) as its file path and created a stray
  `factory_shared` file in the app root. Fixed so disconnected mode
  always uses `database/database.sqlite`; re-verified by a fresh
  migrate — all 12 tables land in that file, no stray file.
- `php artisan route:list` shows all 14 app routes plus Cashier's
  `stripe/webhook` and `stripe/payment/{id}` routes.
- `php artisan factory:db-status` reports DISCONNECTED / sqlite /
  users table present.
- Live boot (`php artisan serve`): `/` renders 200 with the theme
  name, `/login` renders 200, `/dashboard` and `/billing` redirect
  guests to `/login` (302), `/auth/start` redirects to the correct
  WorkOS authorize URL built by the installed SDK
  (`api.workos.com/user_management/authorize?...&provider=authkit`),
  `/authenticate` without a code redirects back to `/login`.
- The WorkOS call shapes in `AuthController` were checked against the
  installed workos-php 4.32.0 source (not a blog post): `WorkOS` is a
  static config holder, `UserManagement` is instantiated directly,
  `getAuthorizationUrl($redirectUri, $state, $provider)` and
  `authenticateWithCode($clientId, $code)` are positional, and the
  user resource carries `id`, `email`, `firstName`, `lastName`. The
  earlier draft used the blog's named-argument form, which the
  installed SDK does not accept — fixed and re-verified by the
  `/auth/start` redirect above.

Not yet verified (needs real credentials, none exist in this repo):

- A real WorkOS sign-in end to end (real WorkOS application, real
  client ID / API key, real callback). The code exchange path is
  written against the installed SDK, but no live WorkOS account was
  exercised.
- A real Stripe Checkout subscription and webhook delivery with test
  keys (Cashier routes and controller calls are verified against the
  installed Cashier 15.8.0; no Stripe account was exercised).
- The shared-DB path against the iMac Postgres (needs the real host,
  database, and credentials). Only the disconnected SQLite path and
  the schema migration's Postgres guard were exercised here.
