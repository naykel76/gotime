<?php

namespace Naykel\Gotime\DTO;

/**
 * This class is used to create a Data Transfer Object (DTO) for a route. It
 * takes an object as input and sets the properties of the DTO based on the
 * properties of the input object.
 *
 * This DTO is also the basis for the MenuItemDTO class, as they share many of
 * the same properties and methods.
 */
class RouteDTO
{
    /**
     * The name of the route.
     */
    public ?string $routeName;

    /**
     * Indicates whether the nav item is a parent.
     */
    public bool $isParent;

    /**
     * The children of the route, if any.
     */
    public ?array $children;

    /**
     * Prevents the route from being generated.
     *
     * @var string|null
     */
    public ?bool $excludeRoute;

    /**
     * Relative URL or path for the route and view. This is the URL that will be
     * used to generate the route. It is not be suitable for use in the menu.
     */
    public string $url;

    /**
     * The type of file to inject into a layout. blade|md|null
     *
     * This is only relevant for markdown files or when a layout is set, as
     * defining the type for a normal blade view is redundant.
     */
    public ?string $type;

    /**
     * The view, file or layout for the route.
     *
     * Can be overridden on a per route basis.
     */
    public ?string $view;

    public function __construct(object $item)
    {
        $this->routeName = $item->route_name ?? null;
        $this->url = toPath($item->route_name ?? $item->url ?? '');
        $this->excludeRoute = $item->exclude_route ?? false;
        $this->type = $item->type ?? null;
        $this->view = empty($item->view) ? $this->url : toPath($item->view);
        $this->isParent = property_exists($item, 'children');
        $this->isParent ? $this->handleChildren($item->children) : $this->children = null;
    }

    /**
     * Handles the children of a parent item.
     *
     * @param  array  $children  The children of the parent item.
     */
    protected function handleChildren(array $children): void
    {
        $this->children = array_map(function ($child) {
            return new self($child);
        }, $children);
    }
}
