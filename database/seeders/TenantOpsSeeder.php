<?php

namespace Database\Seeders;

use App\Models\ServiceCheck;
use App\Models\TenantAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TenantOpsSeeder extends Seeder
{
    public function run(): void
    {
        if (TenantAccount::query()->exists()) {
            return;
        }

        $tenants = [
            [
                'name' => 'Northwind Workspace',
                'owner_name' => 'Elise Vermeer',
                'plan_name' => 'Enterprise',
                'status' => TenantAccount::STATUS_ATTENTION,
                'region' => 'eu-central',
                'seat_count' => 148,
                'renewal_at' => Carbon::now()->addDays(12),
                'last_incident_at' => Carbon::now()->subMinutes(40),
                'checks' => [
                    ['service_name' => 'Billing sync', 'status' => ServiceCheck::STATUS_WARN, 'message' => 'Billing sync lagging behind queue by 17 minutes.', 'checked_at' => Carbon::now()->subMinutes(40)],
                    ['service_name' => 'Webhook delivery', 'status' => ServiceCheck::STATUS_OK, 'message' => 'Delivery recovered after transient timeout spike.', 'checked_at' => Carbon::now()->subHour()],
                ],
            ],
            [
                'name' => 'Canal Health Portal',
                'owner_name' => 'Marek Jansen',
                'plan_name' => 'Growth',
                'status' => TenantAccount::STATUS_ACTIVE,
                'region' => 'eu-west',
                'seat_count' => 63,
                'renewal_at' => Carbon::now()->addDays(28),
                'last_incident_at' => Carbon::now()->subDays(3),
                'checks' => [
                    ['service_name' => 'Database latency', 'status' => ServiceCheck::STATUS_OK, 'message' => 'Normal latency profile.', 'checked_at' => Carbon::now()->subHours(2)],
                    ['service_name' => 'Nightly export', 'status' => ServiceCheck::STATUS_OK, 'message' => 'Completed successfully.', 'checked_at' => Carbon::now()->subHours(6)],
                ],
            ],
            [
                'name' => 'Studio Atlas',
                'owner_name' => 'Noor de Vries',
                'plan_name' => 'Starter',
                'status' => TenantAccount::STATUS_TRIAL,
                'region' => 'us-east',
                'seat_count' => 11,
                'renewal_at' => Carbon::now()->addDays(5),
                'last_incident_at' => null,
                'checks' => [
                    ['service_name' => 'Provisioning', 'status' => ServiceCheck::STATUS_OK, 'message' => 'Trial tenant provisioned and healthy.', 'checked_at' => Carbon::now()->subHours(8)],
                ],
            ],
            [
                'name' => 'Harbor Retail Ops',
                'owner_name' => 'Tom Bakker',
                'plan_name' => 'Scale',
                'status' => TenantAccount::STATUS_PAUSED,
                'region' => 'eu-central',
                'seat_count' => 0,
                'renewal_at' => Carbon::now()->addDays(2),
                'last_incident_at' => Carbon::now()->subDays(1),
                'checks' => [
                    ['service_name' => 'SSO handshake', 'status' => ServiceCheck::STATUS_FAIL, 'message' => 'SAML certificate expired for paused tenant.', 'checked_at' => Carbon::now()->subDay()],
                ],
            ],
        ];

        foreach ($tenants as $payload) {
            $checks = $payload['checks'];
            unset($payload['checks']);

            $tenant = TenantAccount::query()->create($payload);

            foreach ($checks as $check) {
                ServiceCheck::query()->create([
                    'tenant_account_id' => $tenant->id,
                    ...$check,
                ]);
            }
        }
    }
}
