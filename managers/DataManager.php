<?php
class DataManager {
    const JAAR_TREND = "generaal";
    const MAAND = "maand";
    const PROTOCOL_TREND = "protocol";
    
    private $labelUpdate;
    
    // <editor-fold desc="public : verkrijgen van data-arrays">
    /*
     * ******************************************
     *              Public functies
     * ******************************************
     * ******************************************
     *     voor het verkrijgen van data-arrays
     * ******************************************
     */
    
    /**
     * 
     * @param String $protcolNaam gegeven protocol
     * @return array Flot-compatible array met trend van 1 protocol 
     */
    public function getProtocolTrendData($protcolNaam){
        $con = $this->openConnection();
        $result = null;
        $datums = array();
        $data = array();
        if($protcolNaam == null){
            $result = $con->query("SELECT  MAX(`datum`) AS `datum`, `shouldTotaal`, `doneTotaal` FROM `protocoltotalen` GROUP BY `datum`");
        } else {
            $result = $con->query("SELECT `datum`, `shouldTotaal`, `doneTotaal` WHERE `naam` = " . $protcolNaam . " FROM `protocoltotalen`");
        }
        while($row = $result->mysqli_fetch_array()) {
            array_push($datums, $this->SQLtoJStimestamp($row['datum']));
            array_push($data, $this->toPercentage($row['doneTotaal'], $row['shouldTotaal']));
        }
        $this->closeConnection($con);
        
        $this->labelUpdate = $protocolNaam;
        
        return $this->getMap($datums, $data);
    }
    
    public function getProtocollenMaandData($maand){
        $con = $this->openConnection();
        $result = null;
        $labels = array();
        $data = array();
        
        if($maand == null){
            $result = $con->query("SELECT `naam`, MAX(`datum`), `shouldTotaal`, `doneTotaal` FROM `protocoltotalen` GROUP BY `naam`");
            $this->labelUpdate = $this->JStoUIdate();
        } else {
            $result = $con->query("SELECT `naam`, `shouldTotaal`, `doneTotaal` FROM `protocoltotalen` WHERE `datum` = " . ($this->JStoSQLdate($maand)) . " GROUP BY `naam`");
            $this->labelUpdate = $this->JStoUIdate($maand);
        }
        $i = 1;
        var_dump($i . $result);
        
        while($row = $result->mysqli_fetch_array(MYSQLI_ASSOC)) {
            array_push($labels, $i);
            $done = $row['doneTotaal'];
            $should = $row['shouldTotaal'];
            array_push($data, $this->toPercentage($done, $should));
            $i++;
        }
        $this->closeConnection($con);
        
        return $this->getMap($labels, $data); 
    }
    
    public function getJaarTrendData(){
        $con = $this->openConnection();
        $result = $con->query("SELECT * FROM `maandtotalen`");
        $datums = array();
        $data = array();
        
        while($row = $result->mysqli_fetch_array()) {
            array_push($datums, $this->SQLtoJStimestamp($row['datum']));
            array_push($data, $this->toPercentage($row['maandDoneTotaal'], $row['maandShouldTot']));
        }
        $this->closeConnection($con);
        
        return $this->getMap($datums, $data); 
    }
    // </editor-fold>
    
    // <editor-fold desc="public : JSON-sets">
    /*
     * ******************************************
     *      voor het verkrijgen van JSON-sets
     * ******************************************
     */
    
    /**
     *
     * @param type $target
     * @param type $type
     * @param type $option
     * @return json dataset zoals flot die accepteert.
     */
    public function getJSONset($type, $option){        
        switch ($type) {
            case self::JAAR_TREND :
                $data = $this->getJaarTrendData();
                break;
            case self::MAAND :
                $data = $this->getProtocollenMaandData($option);
                break;
            case self::PROTOCOL_TREND :
                $data = $this->getProtocolTrendData($option);
                break;
            default:
                die("Unkown graph-type");
        }
        $output = array("label" => $type);
        $output['data'] = $data;
        //var_dump($output);
        return json_encode($output);
    } 
    // </editor-fold>
    
    // <editor-fold desc="public : labels">
     /*
     * ******************************************
     *  voor het verkrijgen van labels / procol-namen
     * ******************************************
     */  
    
    public function getProtocolLabels($maand){
        $con = $this->openConnection();
        $labels = array();
        $namen = array();
        
        if($maand == null){
            $result = mysql_query("SELECT DISTINCT `naam`, MAX(`datum`) FROM `protocoltotalen` GROUP BY `datum`");
        } else {
            $result = mysql_query("SELECT DISTINCT `naam` FROM `protocoltotalen` WHERE `datum` = " . $this->JStoSQLdate($maand) );
        }
        
        $i = 1;
        while($row = mysql_fetch_array($result)) {
            array_push($labels, $i);
            array_push($namen, $row['naam']);
            $i++;
        }
        
        return $this->getMap($labels, $namen);
    }
    
    public function getProtocolLabelsJSON($maand){
       return json_encode($this->getProtocolLabels($maand));
    }
     // </editor-fold>
    
    // <editor-fold desc="public : static">
    /*
     * ******************************************
     *      (public) Static functies
     * ******************************************
     */  
    
    public static function getMap($labels, $amounts) {
        return array_map('make_pair', $labels, $amounts);
    }
    // </editor-fold>
    
    // <editor-fold desc="private : misc">    
    /*
     * ******************************************
     *            Private functies
     * ******************************************
     */

    private function SQLtoJStimestamp($date){
        return strtotime($date) * 1000;
    }
    
    private function SQLtoUIdate($date){
        return date('M-Y', $date);
    }
    
    private function JStoSQLdate($stamp){
        return date('Y-m-d',$stamp / 1000);
    }
    
    private function JStoUIdate($stamp) {
        return date('M-Y', $stamp / 1000);
    }
    
    private function toPercentage($part, $totaal){
        return round(($part/$totaal) * 100, 1, PHP_ROUND_HALF_UP);
    }
    
    private function getLabelUpdate(){
        $temp = $this->labelUpdate;
        $this->labelUpdate = null;
        return $temp == null ? '' : $temp;
    }
    // </editor-fold>
    
    // <editor-fold desc="private : connectiviteit">
    /*
     * ******************************************
     *         Connecitiviteit functies
     * ******************************************
     */
    private function closeConnection($con){
        $con->close();
    }
    
    private function openConnection(){
        include 'includes/loginCheck.php';
        $server = "localhost:3306";
        $con = null;
        if($_SESSION['userType'] == "admin") {
             $con = new mysqli($server, "aFeedSysAdmin", "admintest");
        } else {
            $con = new mysqli($server, "aFeedSysGebruik", "gebruikertest");
        }
        
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        
        $con->select_db("afeedsys");
        return $con;
    }
    // </editor-fold>
}

/**
 * 
 * @param type $label
 * @param type $amount
 * @return array flot-compatible 
 */
function make_pair($label, $amount) {
    return array($label, $amount);
}
?>
