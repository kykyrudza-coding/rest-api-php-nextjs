<?php

namespace Kernel\Backend\Http;

use RuntimeException;

/**
 * Class Redirect
 *
 * Provides a method to redirect the HTTP request to another URL.
 */
class Redirect
{
    /**
     * Redirects the user to a specified URL.
     *
     * This method sends a 302 redirect response to the client. It ensures that no output has been sent before
     * redirecting, and throws an exception if headers are already sent.
     *
     * @param  string  $url  The URL to redirect the request to.
     *
     * @throws RuntimeException If headers have already been sent, a RuntimeException is thrown.
     */
    public static function redirect(string $url): void
    {
        session_write_close();
        if (! headers_sent()) {
            header('Location: '.$url, true, 302);
            exit;
        } else {
            throw new RuntimeException('Headers already sent. Cannot redirect to: '.$url);
        }
    }
}
