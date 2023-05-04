<?php 

    session_start();
    include("../../Includes/checkLogin.php");
    include("../../Includes/checkAdmin.php");
    include("../../Includes/config.php");

    $message = "";

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
        <form action="<?= $siteLocation ?>/Handlers/addRequire.php" method="POST" accept-charset="UTF-8">
            <h1>Add Require List</h1>
            <p>Crafting Product</p>
            <input type="text" name="craftingProduct" placeholder="Crafting Product">
            <p>Need Products</p>
            <input type="text" name="needProducts" placeholder="Use ',' for seperate.">
            <p>Need Amount Per One</p>
            <input type="text" name="needAmountPerOne" placeholder="Use ',' for seperate.">
            <br>
            <br>
            <button>Save</button>
        </form>
    </div>
</body>
</html>