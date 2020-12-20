<?php

class Controller
{
    public static $title;
    public static $cssFiles = array();
    public static $jsFiles = array();
    public static $importFileVersion = 0;

    public static function CreateView($viewName)
    {
        require_once "includes/views/header.php";
        require_once "includes/views/$viewName.php";
        require_once "includes/views/footer.php";
    }
}