<?php
class DataManager {
    const JAAR_TREND = "generaal";
    const MAAND = "maand";
    const PROTOCOL_TREND = "protocol";
    const LABELS = "labels";
    
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
     * @param String $protocolNaam gegeven protocol
     * @return array Flot-compatible array met trend van 1 protocol 
     */
    public function getProtocolTrendData($protocolNaam){
        $con = $this->openConnection();
        $result = null;
        $datums = array();
        $data = array();
        if(is_null($protocolNaam)){
            $result = $con->query("SELECT  MAX(`datum`) AS `datum`, `shouldTotaal`, `doneTotaal` FROM `protocoltotalen` GROUP BY `datum`");
        } else {
            $result = $con->query("SELECT `datum`, `shouldTotaal`, `doneTotaal` FROM `protocoltotalen` WHERE `naam` = '" . $protocolNaam . "'");
        }
        while($row = $result->fetch_array()) {
            array_push($datums, $this->SQLtoJStimestamp($row['datum']));
            array_push($data, $this->toPercentage($row['doneTotaal'], $row['shouldTotaal']));
        }
        $result->close();
        $this->closeConnection($con);
        
        //$this->labelUpdate = $protocolNaam;
        
        return $this->getMap($datums, $data);
    }
    
    public function getProtocollenMaandData($maand){
        $con = $this->openConnection();
        $result = null;
        $labels = array();
        $data = array();
        
        if(is_null($maand)){
            $result = $con->query("SELECT `naam`, `shouldTotaal`, `doneTotaal` FROM `protocoltotalen` WHERE `datum` = (SELECT MAX(datum) FROM `protocoltotalen`) GROUP BY `naam`");
        } else {
            //var_dump($this->JStoSQLdate($maand));
            $result = $con->query("SELECT `naam`, `shouldTotaal`, `doneTotaal` FROM `protocoltotalen` WHERE `datum` = '" . ($this->JStoSQLdate($maand)) . "' GROUP BY `naam`");
        }
        
        $i = 1;
        
        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            array_push($labels, $i);
            $done = $row['doneTotaal'];
            $should = $row['shouldTotaal'];
            array_push($data, $this->toPercentage($done, $should));
            $i++;
        }
        $result->close();
        $this->closeConnection($con);
        
        return $this->getMap($labels, $data); 
    }
    
    public function getJaarTrendData(){
        $con = $this->openConnection();
        $result = $con->query("SELECT * FROM `maandtotalen`");
        $datums = array();
        $data = array();
        
        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            array_push($datums, $this->SQLtoJStimestamp($row['datum']));
            array_push($data, $this->toPercentage($row['maandDoneTotaal'], $row['maandShouldTot']));
        }
        $result->close();
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
            case self::LABELS :
                return $this->getProtocolLabelsJSON($option);
                break;
            default:
                die("Unkown graph-type");
        }
        $output = array('label' => $type);
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
        $result = null;
        $labels = array();
        $namen = array();
        
        if(is_null($maand)){
            $result = $con->query("SELECT DISTINCT `naam` FROM `protocoltotalen` WHERE `datum` = (SELECT MAX(datum) FROM `protocoltotalen`)");
        } else {
            $result = $con->query("SELECT DISTINCT `naam` FROM `protocoltotalen` WHERE `datum` = " . $this->JStoSQLdate($maand) );
        }
        
        $i = 1;
        while($row = $result->fetch_array()) {
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
        loginCheck();
        $server = "localhost:3306";
        $con = null;
        if(is_null($con)) {
            if($_SESSION['userType'] == "admin") {
                $con = new mysqli($server, "aFeedSysAdmin", "admintest", "afeedsys");
            } else {
                $con = new mysqli($server, "aFeedSysGebruik", "gebruikertest", "afeedsys");
            }
            if ($con->connect_errno) {
                echo "Failed to connect to MySQL: (" . $con->connect_errno . ") " . $con->connect_error;
            }
        } else {
            die('connectie was niet null');
        }
        
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
