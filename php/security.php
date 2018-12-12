<?php
    $associations = array("/INFS3202/index.php");
    if($_SERVER['HTTP_X_REQUESTED_WITH']) {
        header("HTTP/1.0 400 Bad Request");
        die("Cannot respond for ajax in this page");
    }
    else if(!in_array($_SERVER["PHP_SELF"], $associations)) {
        header("Location: /INFS3202/index.php");
    }
?>