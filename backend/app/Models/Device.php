<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Device extends Model
{
    protected $fillable = [
        'user_id', 'name', 'device_id', 'token',
        'phone_number', 'operator', 'sim_slots',
        'status', 'last_seen_at', 'battery_level',
        'signal_strength', 'model', 'android_version', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sim_slots' => 'array',
            'last_seen_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Device $device) {
            if (empty($device->token)) {
                $device->token = 'dev_' . Str::random(60);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function isOnline(): bool
    {
        return $this->status === 'online'
            && $this->last_seen_at
            && $this->last_seen_at->diffInMinutes(now()) < 2;
    }

    public function markOnline(): void
    {
        $this->update([
            'status' => 'online',
            'last_seen_at' => now(),
        ]);
    }

    public function markOffline(): void
    {
        $this->update(['status' => 'offline']);
    }
}
