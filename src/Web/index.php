<?php 
    include("Includes/config.php");
    session_start();
    error_reporting(0);
    if ($_SESSION["userId"]) {
        header("location: /Pages/index.php");
    }

    $loggedIn = -1;
    $message = "";

    if ($_GET["userId"] and $_GET["password"]) {
        error_reporting(E_ALL);
        $userId = $_GET["userId"];
        $password = $_GET["password"];
        $ch = curl_init($apiAddress."/loginUser.aras?userId=$userId&userPassword=$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        curl_close($ch);

        if (str_contains($data, "Wrong") == true or $data == "") {
            $loggedIn = 0;
            $message = "User ID or password not matched.";
        } else {
            if ($data == "Security problems with used character. Please don't use it.") {
                $loggedIn = 0;
                $message = $data;
            } else {
                $loggedIn = 1;
                $_SESSION["userId"] = $userId;
                $_SESSION["permission"] = $data;
                header("location: /Pages/index.php");
            }
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
    <title><?= $siteName ?> - Login</title>
</head>
<body>
    <?php 
        if ($message != "") {
            echo '<p style="text-align: center; color: black; font-weight: bolder; font-size: 20px;">'.$message."</p>";
        }
    ?>
    <div class="form-container">
        <form action="" method="GET">
            <h1>Login Form</h1>
            <p>User ID</p>
            <input type="text" name="userId" placeholder="Type your User ID.">
            <p>User Password</p>
            <input type="password" name="password" placeholder="Type your password.">
            <br>
            <br>
            <button>Login</button>
        </form>
    </div>
</body>
</html>