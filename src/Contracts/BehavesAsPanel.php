<?php

namespace Laravel\Components\Contracts;

interface BehavesAsPanel
{
    /**
     * Make current field behaves as panel.
     *
     * @return \Laravel\Components\Panel
     */
    public function asPanel();
}
