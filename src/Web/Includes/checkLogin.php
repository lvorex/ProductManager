<?php 

    if (!$_SESSION["userId"] or !$_SESSION["permission"]) {
        header("location: index.php");
    }

?>