<?php

namespace Kernel\Backend\Routing;

use Closure;

class RouteConfig
{
    private string $method;

    private string $uri;

    private Closure|array $handler;

    private ?string $name = null;

    public function __construct(string $method, string $uri, Closure|array $handler)
    {
        $this->method = strtoupper($method);
        $this->uri = '/'.trim($uri, '/');
        $this->handler = $handler;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function handler(): Closure|array
    {
        return $this->handler;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
