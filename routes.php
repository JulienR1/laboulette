<?php

Route::set("index.php", function () {
    if (isset($_GET["id"])) {
        echo "playing game " . $_GET["id"];
    } else {
        echo "default page";
    }
});

Route::set("new", function () {
    echo "Creating new lobby";
});

Route::set("join", function () {
    echo "joining game";
    echo $_GET["id"];
});

Route::set("pageNotFound", function () {
    echo "404: page not found redirect";
});

if (!in_array($_GET["url"], Route::$validRoutes)) {
    header("Location: pageNotFound");
}