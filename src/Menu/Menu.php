<?php

namespace Laravel\Components\Menu;

use JsonSerializable;
use Laravel\Components\Http\Requests\NovaRequest;
use Laravel\Components\Makeable;

/**
 * @phpstan-type TMenu \Laravel\Components\Menu\MenuGroup|\Laravel\Components\Menu\MenuItem|\Laravel\Components\Menu\MenuList|\Laravel\Components\Menu\MenuSection
 *
 * @method static static make(array|iterable $items = [])
 */
class Menu implements JsonSerializable
{
    use Makeable;

    /**
     * The items for the menu.
     *
     * @var \Illuminate\Support\Collection|array
     */
    public $items = [];

    /**
     * Create a new Menu instance.
     *
     * @param  array|iterable  $items
     */
    public function __construct($items = [])
    {
        $this->items = collect($items);
    }

    /**
     * Wrap the given menu if not already wrapped.
     *
     * @param  \Laravel\Components\Menu\Menu|array|iterable  $menu
     * @return \Laravel\Components\Menu\Menu
     */
    public static function wrap($menu)
    {
        return $menu instanceof self
            ? $menu
            : self::make($menu);
    }

    /**
     * Push items into the menu.
     *
     * @param  \JsonSerializable|array|iterable  $items
     * @return $this
     *
     * @phpstan-param TMenu|array|iterable $items
     */
    public function push($items = [])
    {
        return $this->append($items);
    }

    /**
     * Append items into the menu.
     *
     * @param  \JsonSerializable|array|iterable  $items
     * @return $this
     *
     * @phpstan-param TMenu|array|iterable $items
     */
    public function append($items = [])
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Prepend items to the menu.
     *
     * @param  TMenu|array|iterable  $items
     * @return $this
     */
    public function prepend($items = [])
    {
        $this->items->prepend($items);

        return $this;
    }

    /**
     * Prepare the menu for JSON serialization.
     *
     * @return array<array-key, mixed>
     */
    public function jsonSerialize(): array
    {
        $request = app(NovaRequest::class);

        return $this->items->flatten()
                ->reject(function ($item) use ($request) {
                    return method_exists($item, 'authorizedToSee') && ! $item->authorizedToSee($request);
                })
                ->values()
                ->jsonSerialize();
    }
}
