<?php

namespace App\Models;

use App\Models\Traits\Archives;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Stringable;

class Entry extends Model
{
    use Archives, HasFactory, HasUuids, SoftDeletes;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['title', 'excerpt'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = ['id', 'form_id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'ip_address',
        'user_agent',
        'data',
        'archived_at',
        'deleted_at',
    ];

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this
            ->where($field ?? 'id', $value)
            ->withArchived()
            ->withTrashed()
            ->first();
    }

    /**
     * Get the columns that should receive a unique identifier.
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function scopeFilter(Builder $query, string|null $filter): void
    {
        $query
            ->when($filter === 'archived', fn ($query) => $query->onlyArchived())
            ->when($filter === 'trashed', fn ($query) => $query->onlyTrashed());
    }

    /**
     * Choose a field to use as a title.
     */
    protected function title(): Attribute
    {
        return new Attribute(
            get: function () {
                $keys = collect($this->data)->keys()->mapInto(Stringable::class);

                $candidates = collect([
                    ...$keys->filter(fn ($key) => $key->lower()->endsWith('email'))->sortBy->length(),
                    ...$keys->filter(fn ($key) => $key->lower()->endsWith('subject'))->sortBy->length(),
                    ...$keys->filter(fn ($key) => $key->lower()->endsWith('title'))->sortBy->length(),
                ])->map->toString();

                return $this->data[$candidates->first()] ?? '(Untitled)';
            },
        );
    }

    /**
     * Choose a field to use as an excerpt.
     */
    protected function excerpt(): Attribute
    {
        return new Attribute(
            get: function () {
                $keys = collect($this->data)->keys()->mapInto(Stringable::class);

                $candidates = collect([
                    ...$keys->filter(fn ($key) => $key->lower()->endsWith('message'))->sortBy->length(),
                    ...$keys->filter(fn ($key) => $key->lower()->endsWith('description'))->sortBy->length(),
                ])->map->toString();

                return $this->data[$candidates->first()] ?? null;
            }
        );
    }
}
