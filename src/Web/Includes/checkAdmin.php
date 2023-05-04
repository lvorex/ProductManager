<?php 

    if ($_SESSION["permission"] != "admin") {
        header("location: /Pages/index.php");
    }

?>