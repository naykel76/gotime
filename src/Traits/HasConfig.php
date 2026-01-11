<?php

namespace Naykel\Gotime\Traits;

trait HasConfig
{
    protected function loadIndexConfig(string $resource)
    {
        $config = config("resources.{$resource}");

        if (!$config) {
            throw new \Exception("Config not found for resource: {$resource}");
        }

        $this->routePrefix = $config['routePrefix'];
        $this->title = $config['index']['title'];
        $this->perPage = $config['index']['perPage'];
        $this->searchableFields = $config['index']['searchableFields'];
    }
}
