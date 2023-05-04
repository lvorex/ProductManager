<?php 

    session_start();
    include("../../Includes/checkLogin.php");
    include("../../Includes/checkAdmin.php");
    include("../../Includes/config.php");

    $message = "";

    error_reporting(0);
    if ($_POST["userId"] and $_POST["userPassword"]) {
        error_reporting(E_ALL);

        $userId = $_POST["userId"];
        $userPassword = $_POST["userPassword"];

        $ch = curl_init($apiAddress."/registerUser.aras?userId=$userId&userPassword=$userPassword");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        curl_close($ch);

        if ($data != "true") {
            $message = $data;
        } else {
            $message = "User registered successfully.";
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
    <title><?= $siteName ?> - Admin</title>
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
            <h1>Register User</h1>
            <p>User ID</p>
            <input type="text" name="userId" placeholder="User ID">
            <p>User Password</p>
            <input type="password" name="userPassword" placeholder="User Password">
            <br>
            <br>
            <button>Register</button>
        </form>
    </div>
</body>
</html>