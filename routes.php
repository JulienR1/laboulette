<?php

Route::set("index.php", function () {
    session_start();
    if (isset($_GET["id"])) {
        if (isset($_SESSION["userId"])) {
            GameWindow::Build();
        } else {
            header("Location: /");
            exit;
        }
    } else {
        echo "default page<br>";
        echo "<a href='join'>Join</a><br>";
        echo "<a href='new'>New</a>";
    }
});

Route::set("new", function () {
    CreateLobby::Build();
});

Route::set("join", function () {
    JoinLobby::Build();
});

Route::set("game", function () {
    Game::Dispatch();
});

Route::set("pageNotFound", function () {
    echo "404: page not found redirect";
});

if (!in_array($_GET["url"], Route::$validRoutes)) {
    header("Location: pageNotFound");
}