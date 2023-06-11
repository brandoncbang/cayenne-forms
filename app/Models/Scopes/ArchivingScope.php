<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ArchivingScope implements Scope
{
    /**
     * All of the extensions to be added to the builder.
     *
     * @var string[]
     */
    protected $extensions = ['Archive', 'Unarchive', 'WithArchived', 'WithoutArchived', 'OnlyArchived'];

    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->whereNull('archived_at');
    }

    /**
     * Extend the query builder with the needed functions.
     */
    public function extend(Builder $builder): void
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }
    }

    /**
     * Add the unarchive extension to the builder.
     */
    public function addArchive(Builder $builder): void
    {
        $builder->macro('archive', function (Builder $builder) {
            $builder->withArchived();

            return $builder->update([
                'archived_at' => $builder->getModel()->freshTimestampString(),
            ]);
        });
    }

    /**
     * Add the unarchive extension to the builder.
     */
    public function addUnarchive(Builder $builder): void
    {
        $builder->macro('unarchive', function (Builder $builder) {
            $builder->withArchived();

            return $builder->update(['archived_at' => null]);
        });
    }

    /**
     * Add the with-archived extension to the builder.
     */
    protected function addWithArchived(Builder $builder): void
    {
        $builder->macro('withArchived', function (Builder $builder, $withArchived = true) {
            if (! $withArchived) {
                return $builder->withoutArchived();
            }

            return $builder->withoutGlobalScope($this);
        });
    }

    /**
     * Add the without-archived extension to the builder.
     */
    protected function addWithoutArchived(Builder $builder): void
    {
        $builder->macro('withoutArchived', function (Builder $builder) {
            $builder->withoutGlobalScope($this)->whereNull('archived_at');

            return $builder;
        });
    }

    /**
     * Add the only-archived extension to the builder.
     */
    protected function addOnlyArchived(Builder $builder): void
    {
        $builder->macro('onlyArchived', function (Builder $builder) {
            $builder->withoutGlobalScope($this)->whereNotNull('archived_at');

            return $builder;
        });
    }
}
