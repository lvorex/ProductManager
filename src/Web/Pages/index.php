<?php 

    session_start();
    include("../Includes/config.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $styleLocation ?>">
    <title><?= $siteName ?> - Panel</title>
</head>
<body>
    <?php include("../Includes/navbar.php") ?>

    <div style="text-align: center">
        <h1>Product Manager Panel</h1>
        <p>You can remote your products, check your stocks or control your require lists with this tool easily.</p>
        <br>
        <h3>Your Credentials</h3>
        <p>User ID: <b><?= $_SESSION["userId"]; ?></b></p>
        <p>Permission: <b><?= $_SESSION["permission"]; ?></b></p>
    </div>
</body>
</html>