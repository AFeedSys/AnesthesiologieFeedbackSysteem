<?php
class DataManager {
    const generaal = "generaal";
    const maand = "maand";
    const protocol = "protocol";
    
    private function openConnection(){
        include 'includes/loginCheck.php';
        $server = "localhost:3306";
        $con = null;
        if($_SESSION['userType'] == "admin") {
             $con = mysql_connect($server, "aFeedSysAdmin", "admintest");
        } else {
            $con = mysql_connect($server, "aFeedSysGebruik", "gebruikertest");
        }
        if (!$con){
            die('Could not connect: ' . mysql_error());
        }
        mysql_select_db("afeedsys", $con);
        return $con;
    }
    
    //public function
    
    public function getProtocolData($option){
        $con = $this->openConnection();
        $result = null;
        $datums = array();
        $data = array();
        if($option == null){
            $result = mysql_query("SELECT  MAX(`datum`) AS `datum`, `shouldTotaal`, `doneTotaal` FROM `protocoltotalen` GROUP BY `naam`");
        } else {
            $result = mysql_query("SELECT `datum`, `shouldTotaal`, `doneTotaal` WHERE `naam` = " . $option . " FROM `protocoltotalen` GROUP BY `naam`");
        }
        while($row = mysql_fetch_array($result)) {
            array_push($datums, $this->toJStimestamp($row['datum']));
            array_push($data, $this->toPercentage($row['doneTotaal'], $row['shouldTotaal']));
        }
        $this->closeConnection($con);
        return $this->getMap($datums, $data);
    }
    
    public function getMaandData($option){
        $con = $this->openConnection();
        $result = null;
        $labels = array();
        $data = array();
        
        if($option == null){
            $result = mysql_query("SELECT `naam`, MAX(`datum`), `shouldTotaal`, `doneTotaal` FROM `protocoltotalen` GROUP BY `naam`");
        } else {
            $result = mysql_query("SELECT `naam`, `shouldTotaal`, `doneTotaal` FROM `protocoltotalen` WHERE `datum` = " . ($this->toSQLdate($option)) . " GROUP BY `naam`");
        }
        while($row = mysql_fetch_array($result)) {
            array_push($labels, $row['naam']);
            array_push($data, $this->toPercentage($row['doneTotaal'], $row['shouldTotaal']));
        }
        $this->closeConnection($con);
        
        return $this->getMap($labels, $data); 
    }
    
    public function getGeneralData(){
        $con = $this->openConnection();
        $result = mysql_query("SELECT * FROM `maandtotalen`");
        $datums = array();
        $data = array();
        
        while($row = mysql_fetch_array($result)) {
            array_push($datums, $this->toJStimestamp($row['datum']));
            array_push($data, $this->toPercentage($row['maandDoneTotaal'], $row['maandShouldTot']));
        }
        $this->closeConnection($con);
        
        return $this->getMap($datums, $data); 
    }
    
    private function toJStimestamp($date){
        return strtotime($date) * 1000;
    }
    
    private function toSQLdate($stamp){
        return date('Y-m-d',$stamp / 1000);
    }
    
    private function toUIdate($stamp) {
        return date('M-Y', $stamp / 1000);
    }
    
    private function toPercentage($part, $totaal){
        return round(($part/$totaal) * 100, 1, PHP_ROUND_HALF_UP);
    }
    
    public function closeConnection($con){
        mysql_close($con);
    }
    
    /**
     *
     * @param type $target
     * @param type $type
     * @param type $option
     * @return json dataset zoals flot die accepteert.
     */
    public function getJSONset($type, $option){
        $output = array("label" => $type);
        $points = array(1325376000*1000, 1328054400*1000, 1330560000*1000, 1333238400*1000, 1335830400*1000);
        //$
        switch ($type) {
            case self::generaal :
                $data = $this->getGeneralData();
                break;
            case self::maand :
                $output["label"] = ($option == null ? "" : $this->toUIdate($type));
                $data = $this->getMaandData($option);
                break;
            case self::protocol :
                $data = $this->getProtocolData($option);
                break;
            default:
                die("Unkown graph-type");
        }
        $output['data'] = $data;
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
?>
