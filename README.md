# Eventra

Eventra is a Laravel event-management application with separate user and administrator authentication, event registration and payment tracking, QR venue passes, queued notifications, favorites, interactive Sri Lankan venue maps, and event-day weather forecasts.

## Requirements

- PHP 8.3 or later
- MySQL
- Composer
- Node.js and npm

## Installation

```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate
npm run build
```

Create the demonstration administrator when needed:

```bash
php artisan db:seed --class=AdminSeeder
```

Seed the repeatable demonstration event used to exercise maps, weather, payment, queues, and QR passes:

```bash
php artisan db:seed --class=DemoEventSeeder
```

This creates `Eventra Innovation Summit 2026` and a development attendee:

```text
Email: demo@eventra.test
Password: DemoPass123!
```

The seeded development credentials are:

```text
Email: admin@eventra.com
Password: password123
```

Change these credentials outside local development.

## Running the application

Run the web application and queue worker in separate terminals:

```bash
php artisan serve
php artisan queue:work --tries=3
```

### Email verification

The default local mailer is `log`, so verification emails are written to
`storage/logs/laravel.log`. To deliver them to an inbox, set `MAIL_MAILER=smtp`
and configure `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, and
`MAIL_FROM_ADDRESS` in `.env` using the values from your email provider. Then
run `php artisan config:clear`. If the queue is enabled for mail in your
environment, keep `php artisan queue:work --tries=3` running as well.

During development, restart workers after changing event or listener code:

```bash
php artisan queue:restart
```

## Authentication architecture

Users and administrators are stored separately:

- `users` and the `web` guard handle customer authentication.
- `admins` and the `admin` guard handle administrator authentication.
- `/admin/login` authenticates only records from `admins`.
- API users authenticate through Laravel Sanctum.

The admin migration moves legacy records marked with `is_admin` from `users` into `admins`, then removes the obsolete flag.

## Events and registration

Administrators can create, update, and delete events. Customers can browse upcoming events, view event details, register, cancel registrations, and see their registered events.

A registration stores:

- a server-generated UUID;
- registration and payment status;
- payment method and fixed server-side event amount;
- transaction reference and payment timestamp;
- an optional future check-in timestamp.

Free events are immediately confirmed. Paid events create a pending registration and redirect the customer to the payment-completion page.

## Stripe payment workflow

Eventra uses Stripe's official PHP SDK and Card Element. The checkout asks only for card number, expiry date, and CVC; postal address and phone collection are disabled. The server creates a card-only LKR PaymentIntent from the stored event amount and binds it to the registration UUID and user through metadata. Stripe securely collects payment details; Eventra never receives or stores the card values.

Configure Stripe locally:

```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
```

For localhost webhook forwarding:

```bash
stripe listen --forward-to http://127.0.0.1:8000/stripe/webhook
```

Copy the temporary `whsec_...` value printed by Stripe CLI into `.env`, then clear cached configuration with `php artisan config:clear`.

Registrations are completed only after Eventra verifies the PaymentIntent status, currency, received amount, Stripe ID, and registration UUID on the server. The signed webhook endpoint is the reliable asynchronous confirmation path; the Stripe return route independently retrieves the intent for immediate browser completion.

For production:

1. Register the HTTPS `/stripe/webhook` destination in Stripe Workbench.
2. Subscribe to `payment_intent.succeeded`.
3. Store its production webhook signing secret in the deployment environment.
4. Never expose `STRIPE_SECRET` or `STRIPE_WEBHOOK_SECRET` to browser code.
5. Keep webhook processing idempotent; repeated successful events do not complete a paid registration twice.

Example API payment request:

```http
POST /api/v1/my-registrations/{registration}/payment
Authorization: Bearer {token}
Content-Type: application/json

{
  "payment_method": "bank_transfer",
  "payment_reference": "BANK-2026-00125"
}
```

Stripe determines the eligible payment methods from the account and PaymentIntent. Free and migrated registrations use `free` and `legacy` internally.

## QR venue passes

Eventra uses the external [`endroid/qr-code`](https://github.com/endroid/qr-code) package to generate SVG venue passes.

A QR pass is available only when a registration is confirmed and paid. It contains a signed Eventra verification URL rather than raw customer or payment data. Scanning it opens a venue verification page showing pass validity, attendee, event, UUID, and payment status. Altered URLs are rejected by Laravel's `signed` middleware.

Web routes:

```text
GET  /registrations/{registration}/checkout
POST /registrations/{registration}/payment
GET  /registrations/{registration}/pass
GET  /registrations/{registration}/qr
GET  /venue-pass/{uuid}
```

API routes:

```text
POST /api/v1/events/{event}/register
POST /api/v1/my-registrations/{registration}/payment
GET  /api/v1/my-registrations/{registration}/qr
```

Authenticated registration routes enforce ownership. The public venue verification route requires a valid signature.

## Queues and domain events

Registration completion uses a custom Laravel domain event and queued listener:

```text
Payment completed or event is free
    → RegistrationCompleted event
    → CreateRegistrationNotification listener
    → database queue
    → in-app notification
```

`RegistrationCompleted` implements `ShouldDispatchAfterCommit`, preventing notifications before a surrounding database transaction commits. `CreateRegistrationNotification` implements `ShouldQueue`, retries three times with backoffs of 5, 30, and 60 seconds, and uses `updateOrCreate` to avoid duplicate notifications.

The standard `jobs`, `job_batches`, and `failed_jobs` tables are included. Queue configuration uses:

```env
QUEUE_CONNECTION=database
```

Inspect and retry failures with:

```bash
php artisan queue:failed
php artisan queue:retry all
```

## Favorites and notifications

Authenticated users can save or remove favorite events through the Livewire favorite button and view saved events at `/favorites`.

In-app notifications are shown through the Livewire notification bell. Users can view unread counts, mark notifications as read, mark all as read, and delete notifications. Registration-pass-ready notifications are created asynchronously by the queue worker.

Relevant tables:

- `favorites` has a unique `(user_id, event_id)` pair.
- `notifications` stores type, title, message, display icon/color, and `read_at`.

## Venue maps

`config/venues.php` contains 50 curated locations across Sri Lanka with WGS84 coordinates. Administrators select one when creating or editing an event. The server—not hidden browser fields—resolves the display name and coordinates.

The admin form immediately previews the selection in an OpenStreetMap iframe. Public event pages include the venue map, marker, contributor attribution, and a link to the full map.

Main components:

- `App\Support\VenueLocations`
- `location_key`, `latitude`, and `longitude` event fields
- `resources/views/admin/events/partials/location-map-script.blade.php`

## Weather API integration

Eventra integrates the free [Open-Meteo forecast API](https://open-meteo.com/en/docs) without exposing an API key. Public event pages show conditions, minimum and maximum temperature, rain probability, and maximum wind speed when the event is within Open-Meteo's 16-day forecast window.

The integration:

- caches forecasts per rounded coordinate for 30 minutes;
- uses a five-second timeout and two retries;
- logs provider failures without breaking the event page;
- uses the `Asia/Colombo` timezone;
- returns `null` outside the forecast window or when unavailable.

Configure the endpoint with:

```env
OPEN_METEO_URL=https://api.open-meteo.com
```

`GET /api/v1/events/{event}` includes `coordinates`, `map_embed_url`, and `weather`.

## API overview

Public endpoints include authentication, event listing, event details, search, upcoming events, and summary statistics under `/api/v1`.

Sanctum-protected endpoints include:

- current-user profile and logout;
- registration listing and details;
- event registration and cancellation;
- payment completion;
- QR pass generation.

See [routes/api.php](routes/api.php) for the authoritative route list.

## Testing

Run the complete suite:

```bash
php artisan test
```

Focused feature suites:

```bash
php artisan test --filter=AdminAuthenticationTest
php artisan test --filter=QueuedRegistrationNotificationTest
php artisan test --filter=RegistrationVenuePassTest
php artisan test --filter=LocationWeatherIntegrationTest
php artisan test --filter=StripePaymentTest
```

Weather tests fake HTTP responses, so automated tests do not depend on Open-Meteo availability. Queue tests independently verify domain-event dispatch, queued-listener creation, and notification persistence.

## External services and libraries

- [Laravel](https://laravel.com/)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Laravel Jetstream](https://jetstream.laravel.com/)
- [Livewire](https://livewire.laravel.com/)
- [endroid/qr-code](https://github.com/endroid/qr-code)
- [OpenStreetMap](https://www.openstreetmap.org/)
- [Open-Meteo](https://open-meteo.com/en/docs)

## Security notes

- Never commit `.env` or real credentials.
- Replace seeded passwords in non-development environments.
- Use HTTPS in production so QR verification URLs cannot be intercepted.
- Use a real payment gateway and verified webhooks before accepting payments.
- Run queue workers under a process supervisor in production.
- Keep registration ownership and signed-URL middleware in place.
