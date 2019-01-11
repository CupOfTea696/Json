<?php

// PHP 7.3 polyfills
if (! defined('JSON_THROW_ON_ERROR')) {
    define('JSON_THROW_ON_ERROR', 1 << 22);
}

if (! class_exists('JsonException')) {
    class JsonException extends Exception
    {
    }
}