<?php

namespace Laravel\Components\Fields\Attachments;

use Illuminate\Http\Request;

class DetachAttachment
{
    /**
     * The attachment model.
     *
     * @var class-string<\Laravel\Components\Fields\Attachments\Attachment>
     */
    public static $model = Attachment::class;

    /**
     * Delete an attachment from the field.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __invoke(Request $request)
    {
        static::$model::where('url', $request->attachmentUrl)
                    ->get()
                    ->each
                    ->purge();
    }
}
