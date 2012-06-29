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
            $result = mysql_query("SELECT  MAX(`datum`) AS `datum`, `shouldTotaal`, `doneTotaal` FROM `protocoltotalen` GROUP BY `datum`");
        } else {
            $result = mysql_query("SELECT `datum`, `shouldTotaal`, `doneTotaal` WHERE `naam` = " . $protcolNaam . " FROM `protocoltotalen`");
        }
        while($row = mysql_fetch_array($result)) {
            array_push($datums, $this->toJStimestamp($row['datum']));
            array_push($data, $this->toPercentage($row['doneTotaal'], $row['shouldTotaal']));
        }
        $this->closeConnection($con);
        return $this->getMap($datums, $data);
    }
    
    public function getProtocollenMaandData($maand){
        $con = $this->openConnection();
        $result = null;
        $labels = array();
        $data = array();
        
        if($maand == null){
            $result = mysql_query("SELECT `naam`, MAX(`datum`), `shouldTotaal`, `doneTotaal` FROM `protocoltotalen` GROUP BY `naam`");
        } else {
            $result = mysql_query("SELECT `naam`, `shouldTotaal`, `doneTotaal` FROM `protocoltotalen` WHERE `datum` = " . ($this->toSQLdate($maand)) . " GROUP BY `naam`");
        }
        $i = 1;
        while($row = mysql_fetch_array($result)) {
            array_push($labels, $i);
            array_push($data, $this->toPercentage($row['doneTotaal'], $row['shouldTotaal']));
            $i++;
        }
        $this->closeConnection($con);
        
        return $this->getMap($labels, $data); 
    }
    
    public function getJaarTrendData(){
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
            $result = mysql_query("SELECT DISTINCT `naam` FROM `protocoltotalen` WHERE `datum` = " . $this->toSQLdate($maand) );
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
        mysql_close($con);
    }
    
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
