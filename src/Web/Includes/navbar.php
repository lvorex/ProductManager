<?php include("config.php"); ?>
<div class="panelBar">
    <a href="<?= $siteLocation ?>/Pages/index.php"><div class="panelButton">Home</div></a>
    <a href="<?= $siteLocation ?>/Pages/checkProduct.php"><div class="panelButton">Check Product</div></a>
    <a href="<?= $siteLocation ?>/Pages/insertProduct.php"><div class="panelButton">Insert Product</div></a>
    <a href="<?= $siteLocation ?>/Pages/checkStocks.php"><div class="panelButton">Stocks</div></a>
    <a href="<?= $siteLocation ?>/Pages/checkRequires.php"><div class="panelButton">Requires</div></a>
    <?php 
    
        if ($_SESSION["permission"] == "admin") {
            echo '<div class="panelButton"> | </div>';
            echo '<a href="'.$siteLocation.'/Pages/Admin/registerUser.php"><div class="panelButton">Register User</div></a>';
            echo '<a href="'.$siteLocation.'/Pages/Admin/showUsers.php"><div class="panelButton">Users</div></a>';
            echo '<a href="'.$siteLocation.'/Pages/Admin/editRequires.php"><div class="panelButton">Edit Requires</div></a>';
            echo '<a href="'.$siteLocation.'/Pages/Admin/addRequire.php"><div class="panelButton">Add Require</div></a>';
        }
    
    ?>
   <a href="<?= $siteLocation ?>/Pages/logout"><div class="panelButton" id="right">Logout</div></a>
</div>