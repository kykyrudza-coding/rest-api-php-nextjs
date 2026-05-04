<?php

namespace Kernel\Backend\Http;

class Response
{
    public function send(mixed $content = '', int $code = 200): void
    {
        http_response_code($code);

        if (! headers_sent()) {
            header('Content-Type: application/json; charset=UTF-8');
        }

        if ($code === 204 || $content === null) {
            return;
        }

        echo json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}
