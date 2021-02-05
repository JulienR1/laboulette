<?php

class Route
{
    public static $validRoutes = array();

    public static function set($route, $callback)
    {
        self::$validRoutes[] = $route;

        if ($_GET["url"] == $route) {
            $callback->__invoke();
        }
    }
}