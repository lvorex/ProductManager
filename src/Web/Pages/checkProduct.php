<?php 

    session_start();
    include("../Includes/config.php");
    include("../Includes/checkLogin.php");
    $data = null;
    $found = -1;
    $message = "";
    $ch = null;

    error_reporting(0);
    if ($_POST["productName"] or $_POST["productAmount"] or $_POST["productStatus"]) {
        error_reporting(E_ALL);

        $productName = $_POST["productName"];
        $productAmount = $_POST["productAmount"];
        $productStatus = $_POST["productStatus"];
        if (str_contains($productStatus, " ")) {
            $productStatus = str_replace(" ", "%20", $productStatus);
        }
        if (str_contains($productName, " ")) {
            $productName = str_replace(" ", "%20", $productName);
        }

        $ch = curl_init($apiAddress."/checkProduct.aras?productName=$productName&productAmount=$productAmount&productStatus=$productStatus");
    } else {
        $ch = curl_init($apiAddress."/checkProduct.aras");
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $data = curl_exec($ch);
    curl_close($ch);

    if (!str_contains($data, "{")) {
        $found = 0;
        if ($data == "[]") {
            $message = "Product not found.";
        } else {
            $message = $data;
        }
    } else {
        $found = 1;
        $data = json_decode($data, false);
    }

    error_reporting(0);
    if ($_POST["message"]) {
        if (!empty($_POST["message"])) {
            $message = $_POST["message"];
        }
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
    <title><?= $siteName ?> - Check Product</title>
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
        <form action="" method="POST" accept-charset="utf-8">
            <h1>Check Product</h1>
            <p>Product Name</p>
            <input type="text" name="productName" placeholder="Product Name">
            <p>Product Amount</p>
            <input type="number" name="productAmount" placeholder="Product Amount">
            <p>Product Status</p>
            <input type="text" name="productStatus" placeholder="Product Status">
            <br>
            <br>
            <button>Search</button>
        </form>
    </div>
    <table>
        <tr class="one">
            <td>ID</td>
            <td>Name</td>
            <td>Amount</td>
            <td>Status</td>
        </tr>

        <?php 
        
            if ($found != 1) {
                echo '<tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>';
            } else {
                foreach($data as $product) {
                    echo '<form action="'.$siteLocation.'/Handlers/editProduct.php" method="POST" accept-charset="utf-8"><input type="hidden" name="productId" value="'.$product->id.'"><input type="hidden" name="toName" value="'.$product->name.'"><tr>
                        <td>'.$product->id.'</td>
                        <td>'.$product->name.'</td>
                        <td><textarea name="toAmount" rows="1" cols="1">'.$product->amount.'</textarea></td>
                        <td><textarea name="toStatus" rows="1" cols="1">'.$product->status.'</textarea></td>
                        <td><button>Save</button></td>
                    </tr></form>';
                }
            }
        
        ?>
    </table>
</body>
</html>