<?php

namespace Tests\Unit;

use App\Models\TenantAccount;
use PHPUnit\Framework\TestCase;

class TenantAccountTest extends TestCase
{
    public function test_it_exposes_supported_tenant_statuses(): void
    {
        $this->assertSame(
            ['trial', 'active', 'attention', 'paused'],
            TenantAccount::statuses(),
        );
    }
}
