<?php

namespace Kernel\Backend\Http;

class Request
{
    private array $getData;

    private array $postParam;

    private array $files;

    private array $server;

    private array $queryParams = [];

    public function __construct(array $getData = [], array $postParam = [], array $files = [], array $server = [])
    {
        $this->getData = $getData;
        $this->postParam = $postParam;
        $this->files = $files;
        $this->server = $server;
        $this->queryParams = $this->parseQueryParams();
    }

    public static function createFromGlobals(): Request
    {
        return new self($_GET, $_POST, $_FILES, $_SERVER);
    }

    public function getRequestInfo(): array
    {
        return [
            'uri' => $this->uri(),
            'method' => $this->method(),
        ];
    }

    public function uri(): string
    {
        return $this->server['REQUEST_URI'] ?? '';
    }

    public function path(): string
    {
        $path = parse_url($this->uri(), PHP_URL_PATH) ?: '/';
        $path = '/'.trim($path, '/');

        return $path === '/' ? '/' : rtrim($path, '/');
    }

    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function merge(array $params): void
    {
        $this->queryParams = array_merge($this->queryParams, $params);
    }

    private function parseQueryParams(): array
    {
        $query = $this->server['QUERY_STRING'] ?? '';
        parse_str($query, $queryParams);

        return $queryParams;
    }
}
