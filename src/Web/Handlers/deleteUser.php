<?php 

    session_start();
    include("../Includes/checkLogin.php");
    include("../Includes/checkAdmin.php");
    include("../Includes/config.php");

    $message = "";

    if ($_POST["userId"]) {
        $userId = $_POST["userId"];

        $ch = curl_init($apiAddress."/deleteUser.aras?userId=$userId");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        curl_close($ch);

        if ($data != "true") {
            $message = $data;
        } else {
            $message = "User deleted.";
        }
    }

?>

<?php if ($message != "") { ?>
    <form action="<?= $siteLocation ?>/Pages/Admin/showUsers.php" id="form" method="POST">
        <input type="hidden" name="message" value="<?= $message ?>">
    </form>
    <script>
        function submitForm() {
            document.getElementById("form").submit();
        }
        window.onload = submitForm();
    </script>
<?php } ?>