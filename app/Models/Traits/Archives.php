<?php

namespace App\Models\Traits;

use App\Models\Scopes\ArchivingScope;

trait Archives
{
    /**
     * Boot the archiving trait for a model.
     */
    public static function bootArchives(): void
    {
        static::addGlobalScope(new ArchivingScope);
    }

    /**
     * Initialize the archiving trait for an instance.
     */
    public function initializeArchives(): void
    {
        if (! isset($this->casts['archived_at'])) {
            $this->casts['archived_at'] = 'datetime';
        }
    }

    /**
     * Archive a model instance.
     */
    public function archive(): bool
    {
        $this->archived_at = $this->freshTimestamp();

        $result = $this->save();

        return $result;
    }

    /**
     * Restore an archived model instance.
     */
    public function unarchive(): bool
    {
        $this->archived_at = null;

        $result = $this->save();

        return $result;
    }

    /**
     * Determine if the model instance has been archived.
     */
    public function archived(): bool
    {
        return ! is_null($this->archived_at);
    }
}
