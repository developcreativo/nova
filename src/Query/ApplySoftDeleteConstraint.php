<?php

namespace Laravel\Components\Query;

use Laravel\Components\TrashedStatus;

class ApplySoftDeleteConstraint
{
    /**
     * Apply the trashed state constraint to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Laravel\Scout\Builder  $query
     * @param  string  $withTrashed
     * @return \Illuminate\Database\Eloquent\Builder|\Laravel\Scout\Builder
     */
    public function __invoke($query, $withTrashed)
    {
        if ($withTrashed == TrashedStatus::WITH) {
            $query = $query->withTrashed();
        } elseif ($withTrashed == TrashedStatus::ONLY) {
            $query = $query->onlyTrashed();
        }

        return $query;
    }
}
