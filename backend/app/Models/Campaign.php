<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    protected $fillable = [
        'user_id', 'name', 'template_id', 'contact_group_id', 'device_id',
        'status', 'total_messages', 'sent_count', 'delivered_count', 'failed_count',
        'scheduled_at', 'started_at', 'completed_at', 'rate_limit',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function contactGroup(): BelongsTo
    {
        return $this->belongsTo(ContactGroup::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function progress(): float
    {
        if ($this->total_messages === 0) return 0;
        return round(($this->sent_count + $this->failed_count) / $this->total_messages * 100, 1);
    }
}
