<?php

require_once "routes.php";

function __autoload($class_name)
{
    autoloadFile("includes/classes/$class_name.php");
    autoloadFile("includes/controllers/$class_name.php");
    autoloadFile("includes/models/$class_name.php");
}

function autoloadFile($path)
{
    if (file_exists($path)) {
        require_once $path;
    }
}