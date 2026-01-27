<?php

namespace Naykel\Gotime\Traits;

trait HasConfig
{
    protected function loadIndexConfig(string $resource)
    {
        $config = config("resources.{$resource}");

        if (! $config) {
            throw new \Exception("Config not found for resource: {$resource}");
        }

        if (! isset($config['index'])) {
            throw new \Exception("Index config not found for resource: {$resource}");
        }

        // Resource-level properties (shared across index/form/show)
        $this->setIfExists($config, 'routePrefix');

        // Index-specific properties
        $indexConfig = $config['index'];
        $this->setIfExists($indexConfig, 'title');
        $this->setIfExists($indexConfig, 'perPage');
        $this->setIfExists($indexConfig, 'searchableFields');
        $this->setIfExists($indexConfig, 'filterableFields');
        $this->setIfExists($indexConfig, 'sortColumn');
        $this->setIfExists($indexConfig, 'sortDirection');
    }

    protected function setIfExists(array $config, string $key): void
    {
        if (array_key_exists($key, $config)) {
            $this->{$key} = $config[$key];
        }
    }
}
