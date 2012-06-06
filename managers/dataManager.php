<?php
class dataManager {
    
    private $connection;
    
    public function openConnection(){
        include 'flotrequest.php';
        
        $server = "localhost:3306";
        if($_SESSION['userType'] == "admin") {
            $this->connection = mysql_connect($server, "aFeedSysAdmin", "admintest");
        } else {
            $this->connection = mysql_connect($server, "aFeedSysGebruik", "gebruikertest");
        }
    }

    public function closeConnection(){
        mysql_close();
    }
    
    public function getMap($labels, $amounts) {
        return array_map('make_pair', $labels, $amounts);
    }
}

function make_pair($label, $amount) {
    return array($label, $amount);
}
?>
