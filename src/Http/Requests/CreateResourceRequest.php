<?php

namespace Laravel\Components\Http\Requests;

class CreateResourceRequest extends NovaRequest
{
    /**
     * Determine if this request is a create or attach request.
     *
     * @return bool
     */
    public function isCreateOrAttachRequest()
    {
        return true;
    }
}
