<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class TenantAccount extends Model
{
    public const STATUS_TRIAL = 'trial';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_ATTENTION = 'attention';
    public const STATUS_PAUSED = 'paused';

    protected $fillable = [
        'name',
        'owner_name',
        'plan_name',
        'status',
        'region',
        'seat_count',
        'renewal_at',
        'last_incident_at',
    ];

    protected function casts(): array
    {
        return [
            'renewal_at' => 'datetime',
            'last_incident_at' => 'datetime',
        ];
    }

    /**
     * @return list<string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_TRIAL,
            self::STATUS_ACTIVE,
            self::STATUS_ATTENTION,
            self::STATUS_PAUSED,
        ];
    }

    public function serviceChecks(): HasMany
    {
        return $this->hasMany(ServiceCheck::class)->latest('checked_at');
    }
}
