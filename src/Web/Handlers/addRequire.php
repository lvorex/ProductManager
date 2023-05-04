<!-- Test It -->

<?php 

    session_start();
    include("../Includes/checkLogin.php");
    include("../Includes/checkAdmin.php");
    include("../Includes/config.php");

    $message = "";
    $successfull = -1;

    error_reporting(0);
    if ($_POST["craftingProduct"] and $_POST["needProducts"] and $_POST["needAmountPerOne"]) {
        error_reporting(E_ALL);

        $craftingProduct = $_POST["craftingProduct"];
        $needProducts = $_POST["needProducts"];
        $needAmountPerOne = $_POST["needAmountPerOne"];

        $ch = curl_init($apiAddress."/addRequire.aras?craftingProduct=$craftingProduct&needProducts=$needProducts&needAmountPerOne=$needAmountPerOne");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        curl_close($ch);

        if ($data != "true") {
            $message = $data;
            $successfull = 0;
        } else {
            $message = "Your list added to require list.";
            $successfull = 1;
        }
    } else {
        $successfull = 0;
        $message = "Please fill all blanks.";
    }

?>

<?php if ($successfull != -1) { ?>

    <form action="<?= $siteLocation ?>/Pages/Admin/addRequire.php" method="POST" accept-charset="UTF-8" id="form">
        <input type="hidden" name="message" value="<?= $message ?>">
    </form>

    <script>
        function submitForm() {
            document.getElementById("form").submit();
        }
        window.onload = submitForm();
    </script>

<?php } ?>