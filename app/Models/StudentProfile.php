<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'school',
        'major',
        'grad_year',
        'about',
        'profile_picture',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function educationEntries(): HasMany
    {
        return $this->hasMany(EducationEntry::class)
            ->orderByDesc('end_year')
            ->orderByDesc('start_year');
    }

    public function experienceEntries(): HasMany
    {
        return $this->hasMany(ExperienceEntry::class)
            ->orderByRaw('end_year IS NOT NULL, end_year DESC')
            ->orderByRaw('end_month IS NOT NULL, end_month DESC')
            ->orderByDesc('start_year')
            ->orderByDesc('start_month');
    }

    public function sentConnectionRequests(): HasMany
    {
        return $this->hasMany(Connection::class, 'sender_id');
    }

    public function receivedConnectionRequests(): HasMany
    {
        return $this->hasMany(Connection::class, 'receiver_id');
    }

    public function connections(): Collection
    {
        $sent = Connection::where('sender_id', $this->id)
            ->where('status', 'accepted')
            ->with('receiver')
            ->get()
            ->map(fn (Connection $c) => $c->receiver);

        $received = Connection::where('receiver_id', $this->id)
            ->where('status', 'accepted')
            ->with('sender')
            ->get()
            ->map(fn (Connection $c) => $c->sender);

        return $sent->merge($received);
    }
}
