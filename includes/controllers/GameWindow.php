<?php

class GameWindow extends Controller
{

    public static function Build()
    {
        parent::$jsFiles[] = "disconnect.js";
        parent::CreateView("gameWindow");
    }

}