<?php
class DataManager {
    const generaal = "generaal";
    const maand = "maand";
    const protocol = "protocol";
    
    private $connection;
    
    private function openConnection(){
        include 'includes/loginCheck.php';
        
        $server = "localhost:3306";
        if($_SESSION['userType'] == "admin") {
            return mysql_connect($server, "aFeedSysAdmin", "admintest");
        } else {
            return mysql_connect($server, "aFeedSysGebruik", "gebruikertest");
        }
    }
    
    //public function
    
    public function getProtocolData($option){
        $con = $this->openConnection();
        
    }
    
    public function getMaandData($option){
        $con = $this->connection;
    }
    
    public function getGeneralData(){
        $con = $this->connection;
    }
    
    public function closeConnection(){
        mysql_close();
    }
    
    /**
     *
     * @param type $target
     * @param type $type
     * @param type $option
     * @return json dataset zoals flot die accepteert.
     */
    public function writeDataFlotset($type, $option ){
        $output = array("label" => $type);
        $points = array(1325376000*1000, 1328054400*1000, 1330560000*1000, 1333238400*1000, 1335830400*1000);
        //$
        switch ($type) {
            case self::generaal :
                if ($option == null){
                    
                    $data = array(6,7,8,9,10);
                } else {

                }
                
                $data = array(1,2,3,4,5);
                break;
            case self::maand :
                if ($option == null){
                    
                    $data = array(6,7,8,9,10);
                } else {

                }
                break;
            case self::protocol :
                if ($option == null) {
                    $points = array("a", "b", "c", "d", "e");
                    $data = array(4,5,6,7,8);
                } else {
                    
                }   
                break;
            default:
                die("Unkown graph-type");
        }

        $output["data"] = $this->getMap($points, $data);
        //var_dump($output);
        return json_encode($output);
    } 
    
    public function getMap($labels, $amounts) {
        return array_map('make_pair', $labels, $amounts);
    }
}

function make_pair($label, $amount) {
    return array($label, $amount);
}

function make_classpair($point, $label) {
    return array($point, $label);
}
?>
