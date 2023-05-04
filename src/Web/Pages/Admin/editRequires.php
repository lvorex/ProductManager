<?php 

    session_start();
    include("../../Includes/checkLogin.php");
    include("../../Includes/config.php");
    $message = "";
    $returned = -1;
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
        $returned = 1;
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
    <?php include("../../Includes/navbar.php"); ?>
    <br>
    <?php 
    
        if ($message != "") {
            echo '<p style="text-align: center; color: black; font-weight: bolder; font-size: 20px;">'.$message."</p>";
        }

    ?>
    <div class="form-container">
        <form action="" method="POST" accept-charset="UTF-8">
            <h1>Edit Requires</h1>
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

        <?php if ($returned == -1) { ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        <?php } else { foreach($data as $require) { ?>
            <form action="<?= $siteLocation ?>/Handlers/editRequire.php" method="POST" accept-charset="UTF-8">
                <tr>
                    <input type="hidden" name="craftingProduct" value="<?= $require->craftingProduct ?>">
                    <td><?= $require->id ?></td>
                    <td><?= $require->craftingProduct ?></td>
                    <td><textarea name="needProducts" oninput='this.style.height = "";this.style.height = this.scrollHeight + "px"' onfocus='this.style.height = "";this.style.height = this.scrollHeight + "px"' rows="1"><?= str_replace(",", " and ", $require->needProducts) ?></textarea></td>
                    <td><textarea name="needAmountPerOne" oninput='this.style.height = "";this.style.height = this.scrollHeight + "px"' onfocus='this.style.height = "";this.style.height = this.scrollHeight + "px"' cols="1" rows="1"><?= str_replace(",", " and ", $require->needAmountPerOne) ?></textarea></td>
                    <td><input type="submit" name="type" value="Save"></button></td>
                    <td><input type="submit" name="type" value="Delete"></td>
                </tr>
            </form>
        <?php } } ?>
    </table>
</body>
</html>