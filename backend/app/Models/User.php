<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'password', 'phone',
        'api_key', 'api_secret', 'plan_id',
        'sms_limit', 'sms_used', 'is_active',
        'webhook_url', 'webhook_secret',
    ];

    protected $hidden = [
        'password', 'remember_token', 'api_secret',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (empty($user->api_key)) {
                $user->api_key = 'sk_' . Str::random(40);
            }
            if (empty($user->api_secret)) {
                $user->api_secret = Str::random(48);
            }
        });
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function contactGroups(): HasMany
    {
        return $this->hasMany(ContactGroup::class);
    }

    public function templates(): HasMany
    {
        return $this->hasMany(Template::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->subscriptions()->where('status', 'active')->latest()->first();
    }

    public function hasReachedSmsLimit(): bool
    {
        return $this->sms_used >= $this->sms_limit;
    }

    public function incrementSmsUsed(int $count = 1): void
    {
        $this->increment('sms_used', $count);
    }
}
