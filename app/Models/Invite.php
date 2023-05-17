<?php

namespace App\Models;

use App\Mail\InviteEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Mail;

class Invite extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'email',
        'code',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnused(Builder $query): void
    {
        $query->whereNull('user_id');
    }

    public function hasBeenUsed(): bool
    {
        return ! is_null($this->user_id);
    }

    public function send()
    {
        Mail::to($this->email)->send(new InviteEmail($this));
    }
}
