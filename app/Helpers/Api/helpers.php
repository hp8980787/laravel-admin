<?php
if (!function_exists('array_first')) {
    function array_first(array $array, callable $callback = null, $default = null)
    {
        return Arr::first($array, $callback, $default);
    }
}

/**
 *@note把路由对应的控制器转化成控制器名称加方法的字符串
 *  例如 \App\Http\Controllers\Api\OrderController 变成 Order.index
 **/

if (!function_exists('translateRouteToMethod')) {
    function translateRouteToMethod(string $method): string
    {
        $nameArray = explode('\\', $method);
        $name = array_pop($nameArray);
        $name = strtr($name, ['@' => '']);

        return implode('.', explode('Controller', $name));
    }

}
