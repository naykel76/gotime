<?php

namespace Naykel\Gotime\DTO;

use Illuminate\Support\Collection;

class MenuDTO
{
    /**
     * MenuDTO constructor.
     *
     * @param  Collection  $menuItems  Collection of menu items.
     * @param  string  $menuName  The name of the menu.
     */
    public function __construct(public Collection $menuItems, private string $menuName)
    {
        $this->menuItems = collect($menuItems->get('links'))
            ->map(function ($item) {
                // remove properties not needed for menu items to reduce clutter
                unset($item->exclude_route);
                unset($item->create_child_routes);

                return new MenuItemDTO($item, $this->menuName);
            });
    }
}
