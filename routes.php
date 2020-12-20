<?php

Route::set("index.php", function () {
    if (isset($_GET["id"])) {
        session_start();
        echo "playing game " . $_GET["id"] . "<br>";
        print_r($_SESSION);
    } else {
        echo "default page";
    }
});

Route::set("new", function () {
    CreateLobby::Build();
});

Route::set("join", function () {
    JoinLobby::Build();
});

Route::set("pageNotFound", function () {
    echo "404: page not found redirect";
});

if (!in_array($_GET["url"], Route::$validRoutes)) {
    header("Location: pageNotFound");
}