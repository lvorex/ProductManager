<?php 

    session_start();
    include("../../Includes/checkLogin.php");
    include("../../Includes/checkAdmin.php");
    include("../../Includes/config.php");

    $ch = null;
    $message = "";
    $dataReturned = -1;

    error_reporting(0);
    if ($_POST["userId"]) {
        error_reporting(E_ALL);
        $userId = $_POST["userId"];
        $ch = curl_init($apiAddress."/showUsers.aras?userId=$userId");
    } else {
        error_reporting(E_ALL);
        $ch = curl_init($apiAddress."/showUsers.aras");
    }
    error_reporting(E_ALL);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $data = curl_exec($ch);
    curl_close($ch);

    if (!str_contains($data, "{")) {
        $dataReturned = 0;
        if ($data == "[]") {
            $message = "User not found.";
        } else {
            $message = $data;
        }
    } else {
        $data = json_decode($data, false);
        $dataReturned = 1;
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
            <h1>Users</h1>
            <p>User ID</p>
            <input type="text" name="userId" placeholder="User ID">
            <br>
            <br>
            <button>Search</button>
        </form>
    </div>

    <table>
        <tr class="one">
            <td>USERID</td>
            <td>Permission</td>
        </tr>

        <?php if ($dataReturned == 0 or $dataReturned == -1) { ?>
            <tr>
                <td></td>
                <td></td>
            </tr>
        <?php } else { foreach($data as $user) { ?>
            <tr>
                <form action="<?= $siteLocation ?>/Handlers/deleteUser.php" method="POST" accept-charset="UTF-8">
                    <input type="hidden" name="userId" value="<?= $user->id ?>">
                    <td><?= $user->id ?></td>
                    <td><?= $user->permission ?></td>
                    <td><button>Delete</button></td>
                </form>
            </tr>
        <?php } } ?>
    </table>
</body>
</html>