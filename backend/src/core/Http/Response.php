<?php

namespace Kernel\Backend\Http;

class Response
{
    public static function send(mixed $content = '', int $code = 200): void
    {
        http_response_code($code);

        echo json_encode($content, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}