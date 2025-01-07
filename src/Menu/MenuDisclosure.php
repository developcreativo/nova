<?php

namespace Laravel\Nova\Menu;

use Laravel\Nova\Menu\MenuSection as Section;
class MenuDisclosure extends Section
{

    /**
     * The icon height in pixel.
     *
     * @var int
     */
    protected int $iconHeight = 15;

    public function __construct($name, $items = [], $icon = 'collection')
    {
        $this->icons['height'] = $this->iconHeight;

        parent::__construct($name, $items, $icon);
    }

    /**
     * Prepare the menu for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'icons' => array_merge($this->icons, [
                'classes' => $this->classes,
            ]),
            'classes' => $this->classes,
            'component' => 'menu-disclosure',
            'isCustom' => true,
        ]);
    }
}
