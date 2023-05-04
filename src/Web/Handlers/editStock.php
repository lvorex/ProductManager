<?php 

    session_start();
    include("../Includes/checkLogin.php");
    include("../Includes/config.php");
    $message = "";
    $productName = null;

    error_reporting(0);
    if ($_POST["productName"] and $_POST["toAmount"] != "") {
        error_reporting(E_ALL);

        $productName = $_POST["productName"];
        $toAmount = $_POST["toAmount"];

        $ch = curl_init($apiAddress."/updateStock.aras?productName=$productName&toAmount=$toAmount");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        curl_close($ch);

        if ($data != "true") {
            $message = $data;
        } else {
            $message = "Stock saved.";
        }
    }
    error_reporting(E_ALL);

    echo $message." MESSAGE";

?>

<?php if ($message != "") { ?>

    <form action="<?= $siteLocation ?>/Pages/checkStocks.php" method="POST" accept-charset="UTF-8" id="formMenu">
        <input type="hidden" name="message" value="<?= $message ?>">
        <?php if ($message == "Stock saved.") { ?>
            <input type="hidden" name="productName" value="<?= $productName ?>">
        <?php } ?>
    </form>

    <script>
        function saveStock() {
            document.getElementById("formMenu").submit();
        }
        window.onload = saveStock();
    </script>

<?php } ?>