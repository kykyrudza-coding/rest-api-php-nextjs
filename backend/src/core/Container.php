<?php

namespace Kernel\Backend;

use Exception;

class Container
{
    protected array $services = [];

    public function __construct(array $services = [])
    {
        foreach ($services as $name => $service) {
            $this->set($name, $service);
        }
    }

    public function get(string $name): mixed
    {
        if (! isset($this->services[$name])) {
            throw new Exception(
                'Service not found: '.$name,
                404
            );
        }

        $service = $this->services[$name];

        return is_callable($service) ? $service() : $service;
    }

    public function set(string $name, callable|object $service): void
    {
        $this->services[$name] = $service;
    }
}