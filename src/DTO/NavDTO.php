<?php

namespace Naykel\Gotime\DTO;

use Illuminate\Support\Collection;

class NavDTO
{
    /**
     * NavDTO constructor.
     *
     * @param  Collection  $menuItems  Collection of navigation items.
     * @param  string  $menuName  The name of the navigation menu.
     */
    public function __construct(public Collection $menuItems, private string $menuName)
    {
        $this->menuItems = collect($menuItems->get('links'))
            ->map(function ($item) {
                // remove properties not needed for navigation items to reduce clutter
                unset($item->exclude_route);
                unset($item->create_child_routes);

                return new NavItemDTO($item, $this->menuName);
            });
    }
}
