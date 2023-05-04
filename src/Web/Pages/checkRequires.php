<?php 

    session_start();
    include("../Includes/checkLogin.php");
    include("../Includes/config.php");
    $message = "";
    $data = null;
    $ch = null;

    error_reporting(0);
    if ($_POST["craftingProduct"]) {
        error_reporting(E_ALL);
        $craftingProduct = $_POST["craftingProduct"];
        $ch = curl_init($apiAddress."/checkRequire.aras?craftingProduct=$craftingProduct");
    } else {
        $ch = curl_init($apiAddress."/checkRequire.aras");
    }
    error_reporting(E_ALL);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $data = curl_exec($ch);
    curl_close($ch);

    if (!str_contains($data, "{")) {
        if ($data == "[]" or $data == " ") {
            $message = "Not found any requires.";
        } else {
            $message = $data;
        }
    } else {
        $data = json_decode($data, false);
    }

    error_reporting(0);

    if ($_POST["message"]) {
        $message = $_POST["message"];
    }

    error_reporting(E_ALL);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $styleLocation ?>">
    <title><?= $siteName ?> - Requires</title>
</head>
<body>
    <?php include("../Includes/navbar.php"); ?>
    <br>
    <div class="form-container">
        <form action="" method="POST" accept-charset="UTF-8">
            <h1>Require Check</h1>
            <p>Crafting Product Name</p>
            <input type="text" name="craftingProduct" placeholder="Crafting Product Name">
            <br>
            <br>
            <button>Search</button>
        </form>
    </div>

    <table>
        <tr class="one">
            <td>ID</td>
            <td>craftingProduct</td>
            <td>needProducts</td>
            <td>needAmountPerOne</td>
        </tr>

        <?php if ($message != "") { ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        <?php } else { foreach($data as $require) {?>
            <tr>
                <td><?= $require->id ?></td>
                <td><?= $require->craftingProduct ?></td>
                <td><?= str_replace(",", " and ", $require->needProducts) ?></td>
                <td><?= str_replace(",", " and ", $require->needAmountPerOne) ?></td>
            </tr>
        <?php } } ?>
    </table>
</body>
</html>