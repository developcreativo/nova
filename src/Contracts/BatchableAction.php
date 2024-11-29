<?php

namespace Laravel\Components\Contracts;

use Illuminate\Bus\PendingBatch;
use Laravel\Components\Fields\ActionFields;

interface BatchableAction
{
    /**
     * Register `then`, `catch`, and `finally` callbacks on the pending batch.
     *
     * @param  \Laravel\Components\Fields\ActionFields  $fields
     * @param  \Illuminate\Bus\PendingBatch  $batch
     * @return void
     */
    public function withBatch(ActionFields $fields, PendingBatch $batch);

    /**
     * Set the batch ID on the job.
     *
     * @param  string  $batchId
     * @return $this
     */
    public function withBatchId(string $batchId);
}
