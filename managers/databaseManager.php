<?php
class databaseManager {
    function openConnection(){
        $server = "localhost:3306";
        if($_SESSION['userType'] == "admin") {
            mysql_connect($server, "aFeedSysAdmin", "admintest");
        } else {
            mysql_connect($server, "aFeedSysGebruik", "gebruikertest");
        }
    }
    
    function closeConnection(){
        mysql_close();
    }
}

$test = new databaseManager();
$test::openConnection();
$test::closeConnection();
?>
