<?php session_start();
$_SESSION['userType'] = 'admin';?>
<html>
    <head>
    </head>
    
    <body>
        <?php
        echo "Hello World <br />";
        echo $_SESSION['userType'];
        include './managers/databaseManager.php';
        ?>
    </body>
</html>