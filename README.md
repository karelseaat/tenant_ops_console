# Tenant Ops Console

Laravel starter for internal tenant operations.

This first version covers the basic operating view:

- tenant accounts with status, plan, region, and renewal timing
- per-tenant service checks
- warning and failure visibility for support or platform teams
- a dashboard showing accounts that need attention

## Stack

- Laravel 13
- SQLite for local development
- Blade for the initial dashboard

## Local setup

```bash
cd /home/aat/tenant_ops_console
composer install
php artisan migrate --seed
composer test
```

## Main files

- `app/Models/TenantAccount.php`
- `app/Models/ServiceCheck.php`
- `app/Http/Controllers/TenantOpsDashboardController.php`
- `database/seeders/TenantOpsSeeder.php`
- `resources/views/dashboard.blade.php`

## Direction

This is positioned as a realistic Laravel portfolio app with space for:

- renewal workflow automation
- incident timelines
- account ownership changes
- feature entitlements
- tenant-level maintenance windows
