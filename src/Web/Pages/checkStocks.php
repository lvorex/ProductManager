<?php 

    session_start();
    include("../Includes/checkLogin.php");
    include("../Includes/config.php");
    $ch = curl_init($apiAddress."/checkStocks.aras");
    $message = "";

    error_reporting(0);
    if ($_POST["productName"]) {
        error_reporting(E_ALL);

        $productName = $_POST["productName"];
        $ch = curl_init($apiAddress."/checkStocks.aras?productName=$productName");
    }

    error_reporting(E_ALL);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $data = curl_exec($ch);
    $dataString = $data;
    curl_close($ch);

    if (!str_contains($dataString, "[")) {
        $message = $dataString;
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
    <title><?= $siteName ?> - Stocks</title>
</head>
<body>
    <?php include("../Includes/navbar.php"); ?>
    <br>
    <?php 
    
        if ($message != "") {
            echo '<p style="text-align: center; color: black; font-weight: bolder; font-size: 20px;">'.$message."</p>";
        }

    ?>
    <div class="form-container">
        <form action="" method="POST" accept-charset="UTF-8">
            <h1>Stock Control</h1>
            <p>Product Name</p>
            <input type="text" name="productName" placeholder="Product Name">
            <br>
            <br>
            <button>Control</button>
        </form>
    </div>
    <table>
    <tr class="one">
        <td>ID</td>
        <td>Name</td>
        <td>Amount</td>
    </tr>

    <?php 
    
        if (!str_contains($dataString, "{")) {
            echo '<tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>';
        } else {
            foreach($data as $product) {?>
                <form action="<?= $siteLocation ?>/Handlers/editStock.php" method="POST" accept-charset="UTF-8">
                    <tr>
                        <input type="hidden" name="productName" value="<?= $product->name ?>">
                        <td><?= $product->id ?></td>
                        <td><?= $product->name ?></td>
                        <td><textarea name="toAmount" rows="1" cols="1"><?= $product->amount ?></textarea></td>
                        <td><button>Save</button></td>
                    </tr>
                </form>
            <?php }
        }

    ?>
    </table>
</body>
</html>