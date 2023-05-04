<?php 

    session_start();
    include("../Includes/checkLogin.php");
    include("../Includes/checkAdmin.php");
    include("../Includes/config.php");

    $ch = null;
    $message = "";

    error_reporting(0);
    if ($_POST["type"] and $_POST["type"] == "Save") {
        if ($_POST["needProducts"] and $_POST["needAmountPerOne"] and $_POST["craftingProduct"]) {
            error_reporting(E_ALL);
            $craftingProduct = $_POST["craftingProduct"];
            $needProducts = $_POST["needProducts"];
            $needAmountPerOne = $_POST["needAmountPerOne"];
            $needProducts = str_replace(" and ", ",", $needProducts);
            $needAmountPerOne = str_replace(" and ", ",", $needAmountPerOne);

            $ch = curl_init($apiAddress."/updateRequire.aras?craftingProduct=$craftingProduct&needProducts=$needProducts&needAmountPerOne=$needAmountPerOne");
        }
    } elseif ($_POST["type"] and $_POST["type"] == "Delete") {
        if ($_POST["craftingProduct"]) {
            error_reporting(E_ALL);
            $craftingProduct = $_POST["craftingProduct"];

            $ch = curl_init($apiAddress."/deleteRequire.aras?craftingProduct=$craftingProduct");
        }
    } else {
        header("location: /Pages/Admin/editRequire.php");
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    echo $data;

    if ($data != "true") {
        $message = $data;
    } else {
        if ($_POST["type"] == "Save") {
            $message = "Successfully saved.";
        } else {
            $message = "Successfully deleted.";
        }
    }

?>

<?php if ($message != "") { ?>

    <form action="<?= $siteLocation ?>/Pages/Admin/editRequires.php" method="POST" accept-charset="UTF-8" id="form">
        <input type="hidden" name="message" value="<?= $message ?>">
        <?php if ($_POST["type"] == "Save") { ?>
            <input type="hidden" name="craftingProduct" value="<?= $_POST["craftingProduct"] ?>">
        <?php } ?>
    </form>

    <script>
        function submitForm() {
            document.getElementById("form").submit();
        }
        window.onload = submitForm();
    </script>

<?php } ?>