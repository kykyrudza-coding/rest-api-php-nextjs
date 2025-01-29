<?php

namespace Kernel\Backend\Http;

use Exception;
use Kernel\Backend\Routing\Router;

readonly class Kernel
{
    public function __construct(
        private Request $request,
        private Response $response,
    ) {}

    public function run(): void
    {
        ob_start();
        try {
            $routes = $this->getRoutes();

            $requestInfo = $this->request->getRequestInfo();

            $router = new Router;
            $router->dispatch($routes, $requestInfo, $this->request, $this->response);

        } catch (Exception $e) {
            $this->response->send($e->getMessage(), $e->getCode());
        }
        ob_end_flush();
    }

    private function getRoutes(): array
    {
        return require_once APP_ROOT.'/config/web.php';
    }
}
