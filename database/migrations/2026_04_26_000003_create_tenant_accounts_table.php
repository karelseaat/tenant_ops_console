<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('owner_name');
            $table->string('plan_name');
            $table->string('status')->index();
            $table->string('region');
            $table->unsignedInteger('seat_count')->default(0);
            $table->timestamp('renewal_at')->nullable()->index();
            $table->timestamp('last_incident_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_accounts');
    }
};
