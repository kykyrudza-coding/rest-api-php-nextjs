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
            $code = $e->getCode();
            $statusCode = $code >= 400 && $code < 600 ? $code : 500;

            $this->response->send(['error' => $e->getMessage()], $statusCode);
        }
        ob_end_flush();
    }

    private function getRoutes(): array
    {
        return require_once APP_ROOT.'/config/web.php';
    }
}
