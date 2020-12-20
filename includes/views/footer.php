</main>

<?php
foreach (static::$jsFiles as $file) {
    echo '<script src="js/' . $file . '?v=' . static::$importFileVersion . '"></script>';
}
?>

</body>

</html>