<?php

namespace Backend\Controllers;

use Kernel\Backend\Http\Request;

class Controller
{
    public function index(Request $request): array
    {
        return [
            'message' => 'Hello, World!',
            'query' => $request->getQueryParams(),
        ];
    }

    public function health(): array
    {
        return [
            'status' => 'ok',
        ];
    }
}
