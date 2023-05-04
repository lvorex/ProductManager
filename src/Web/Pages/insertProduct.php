<?php 

    session_start();
    include("../Includes/checkLogin.php");
    include("../Includes/config.php");
    $message = "";

    error_reporting(0);
    if ($_POST["productName"] and $_POST["productAmount"] and $_POST["productStatus"]) {
        error_reporting(E_ALL);
        $productName = $_POST["productName"];
        $productAmount = $_POST["productAmount"];
        $productStatus = $_POST["productStatus"];
        if (str_contains($productName, " ")) {
            $productName = str_replace(" ", "%20", $productName);
        }
        if (str_contains($productStatus, " ")) {
            $productStatus = str_replace(" ", "%20", $productStatus);
        }

        $ch = curl_init($apiAddress."/insertProduct.aras?productName=$productName&productAmount=$productAmount&productStatus=$productStatus");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        curl_close($ch);

        if ($data != "true") {
            $message = $data;
        } else {
            $message = "Inserted product.";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $styleLocation ?>">
    <title><?= $siteName ?> - Insert Product</title>
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
            <h1>Insert Product</h1>
            <p>Product Name</p>
            <input type="text" name="productName" placeholder="Product Name">
            <p>Product Amount</p>
            <input type="number" name="productAmount" placeholder="Product Amount">
            <p>Product Status</p>
            <input type="text" name="productStatus" placeholder="Product Status">
            <br>
            <br>
            <button>Send</button>
        </form>
    </div>
</body>
</html>