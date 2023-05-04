<?php 

    session_start();
    include("../Includes/checkLogin.php");
    include("../Includes/config.php");
    $dataReturned = -1;
    $toName = "";
    $toAmount = "";
    $toStatus = "";

    if ($_POST["productId"] and $_POST["toAmount"] != "" and $_POST["toStatus"] and $_POST["toName"]) {
        $productId = $_POST["productId"];
        $toName = $_POST["toName"];
        $toAmount = $_POST["toAmount"];
        $toStatus = $_POST["toStatus"];
        if (str_contains($toStatus, " ")) {
            $toStatus = str_replace(" ", "%20", $toStatus);
        }
        $ch = curl_init($apiAddress."/updateProduct.aras?productId=$productId&toAmount=$toAmount&toStatus=$toStatus");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        curl_close($ch);

        if ($data != "true") {
            $message = $data;
            $dataReturned = 0;
        } else {
            $message = "Product Saved.";
            $dataReturned = 1;
        }
    } else {
        echo $_POST["productId"]." ".$_POST["toAmount"]." ".$_POST["toStatus"];
        $message = "Wrong input.";
        $dataReturned = 0;
    }

?>

<?php 

    if ($dataReturned == 1) {
?>

    <form action="<?= $siteLocation ?>/Pages/checkProduct.php" method="POST" id="successForm">
        <input type="hidden" name="productName" value="<?= $toName ?>">
        <input type="hidden" name="productAmount" value="<?= $toAmount ?>">
        <input type="hidden" name="productStatus" value="<?= $toStatus ?>">
        <input type="hidden" name="message" value="<?= $message ?>">
    </form>

    <script>
        function submitForm() {
            document.getElementById("successForm").submit();
        }
        window.onload = submitForm();
    </script>

<?php } elseif ($dataReturned == 0) { ?>

    <form action="<?= $siteLocation ?>/Pages/checkProduct.php" method="POST" id="errorForm">
        <input type="hidden" name="message" value="<?= $message ?>">
    </form>

    <script>
        function submitForm() {
            document.getElementById("errorForm").submit();
        }
        window.onload = submitForm();
    </script>

<?php } elseif ($dataReturned == -1) { ?>

    <?php
        
    ?>

<?php } ?>