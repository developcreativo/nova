<?php

namespace Laravel\Components\Fields\Attachments;

use Illuminate\Support\Facades\Artisan;

class PruneStaleAttachments
{
    /**
     * The pending attachment model.
     *
     * @var class-string<\Laravel\Components\Fields\Attachments\PendingAttachment>
     */
    public static $model = PendingAttachment::class;

    /**
     * Prune the stale attachments from the system.
     *
     * @return void
     */
    public function __invoke()
    {
        Artisan::call('model:prune', [
            '--model' => static::$model,
            '--chunk' => 100,
        ]);
    }
}
