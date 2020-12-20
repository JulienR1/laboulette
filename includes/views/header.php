<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo static::$title; ?></title>

    <?php
foreach (static::$cssFiles as $file) {
    echo '<link rel="stylesheet" href="css/' . $file . '?v=' . static::$importFileVersion . '">';
}
?>
</head>

<body>
    <main>