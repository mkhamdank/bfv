# API quota

## Setup

The quota migration creates `quota_policies` and individual-call `quota_usage` logs on the named `quota` connection. It does not change the application's default database. Configure `DB_QUOTA_HOST`, `DB_QUOTA_DATABASE`, `DB_QUOTA_USERNAME`, and `DB_QUOTA_PASSWORD` when quota storage is separate; omitted values fall back to the matching `DB_*` setting. The connection session and quota timestamps use UTC.

Run `php artisan migrate`, then run these seeders once:

```text
php artisan db:seed --class="Database\Seeders\QuotaPolicySeeder"
php artisan db:seed --class="Database\Seeders\QuotaPermissionSeeder"
```

The policy seeder inserts the PDF default only when absent, so later runs do not reset an admin-edited limit. Grant the `manage quotas` permission to authorized admins through the application's permission management flow. Quota admin endpoints use the existing web authentication and this permission.

Quota database failures are fail-open by default. Set `QUOTA_FAIL_CLOSED=true` to reject a request with HTTP 503 when the pre-check cannot read quota data. Usage logging errors are always warnings and do not replace a successful endpoint response.

## Integrated routes

Only the active `POST /api/ocr/etoll` route is pre-checked, using the explicit `ocr/etoll` slug, and logged after OCR output normalization succeeds. `POST /api/ocr/parking` is not quota-integrated because it is not among the specified source endpoints. Source endpoints `validate-quotation/kepada`, `ocr/dokumen-bc`, `ocr/plate`, `ocr/lot-number`, `pdf/split-by-po`, `pdf/table-extraction`, `generate`, `ocr`, and `ocr/invoice` are not registered in this project and were not added. The `pdf/table-extraction` default policy is present but inactive until a matching route is implemented.

## Admin API

All routes are under `/admin/api-quotas` and require authentication plus `manage quotas`:

- `GET /policies`, `POST /policies`, `PUT /policies`, `DELETE /policies`
- `GET /status?endpoint_slug=...`
- `GET /usage` with optional `endpoint_slug`, `employee`, `from`, `to`, and `per_page`
- `GET /summaries/daily`, `/summaries/employees`, `/summaries/hourly`
- `GET /recent-calls`

Summary queries sum `call_count`, so a legacy aggregate row contributes its count once rather than being counted as one call. `data: null` from status means the policy is absent or quota status could not be read; it does not mean an unlimited or empty quota. Recent-call intervals are returned when the connected database supports window functions.

The middleware uses check-then-record: concurrent requests can pass the same remaining quota before either successful call is recorded. This implementation does not claim a strict atomic cap, and it records only after successful endpoint work to avoid charging validation/provider failures.

## Privacy

The OCR integration does not send employee, token, or metadata values, so those fields are stored as `NULL`/`0`. `recordUsage` accepts optional explicit metadata for future callers, but it never captures prompts, uploaded images, provider payloads, API keys, or secrets automatically. Callers should pass only small analytics fields that are safe to retain.