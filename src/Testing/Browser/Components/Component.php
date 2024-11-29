<?php

namespace Laravel\Components\Testing\Browser\Components;

use Laravel\Dusk\Component as BaseComponent;
use Laravel\Components\Testing\Browser\Concerns\InteractsWithElements;

abstract class Component extends BaseComponent
{
    use InteractsWithElements;
}
