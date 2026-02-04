<?php

namespace Naykel\Gotime\Traits;

trait HasConfig
{
    protected function loadIndexConfig(string $resource)
    {
        $config = $this->getResourceConfig($resource, 'index');

        // Resource-level properties
        $this->setIfExists($config['resource'], 'routePrefix');

        // Index-specific properties
        $indexConfig = $config['section'];
        $this->setIfExists($indexConfig, 'title');
        $this->setIfExists($indexConfig, 'perPage');
        $this->setIfExists($indexConfig, 'searchableFields');
        $this->setIfExists($indexConfig, 'filterableFields');
        $this->setIfExists($indexConfig, 'sortColumn');
        $this->setIfExists($indexConfig, 'sortDirection');
    }

    protected function loadFormConfig(string $resource)
    {
        $config = $this->getResourceConfig($resource, 'form');

        // Resource-level properties
        $this->setIfExists($config['resource'], 'routePrefix');

        // Form-specific properties
        $formConfig = $config['section'];
        $this->setIfExists($formConfig, 'title');
    }

    protected function getResourceConfig(string $resource, string $section): array
    {
        $config = config("resources.{$resource}");

        if (! $config) {
            throw new \Exception("Config not found for resource: {$resource}");
        }

        if (! isset($config[$section])) {
            throw new \Exception("{$section} config not found for resource: {$resource}");
        }

        return [
            'resource' => $config,
            'section' => $config[$section],
        ];
    }

    protected function setIfExists(array $config, string $key): void
    {
        if (array_key_exists($key, $config)) {
            $this->{$key} = $config[$key];
        }
    }
}