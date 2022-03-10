<?php
if (!function_exists('array_first')) {
    function array_first(array $array, callable $callback = null, $default = null)
    {
        return Arr::first($array, $callback, $default);
    }
}
