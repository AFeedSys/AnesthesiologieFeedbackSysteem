<?php

/**
 * @todo prepare statement!
 * @author Coen Meulenkamp <coenmeulenkamp - at - gmail.com>
 * @version ALPHAv1.0 Friday Release
 */
class DataManager {
    /** \addtogroup  constants die aangeven welke type data de DataManager kan afhandelen
    *  @{
    */
    const JAAR_TREND = "generaal"; /**< identificatie voor het jaarovericht*/
    const MAAND = "maand"; /**< identificatie voor het maandoverzicht met geregistreerde protocollen */
    const PROTOCOL_TREND = "protocol"; /**< identificatie voor het verkrijgen van trenddata van een protocol*/
    const LABELS = "labels"; /**< identificatie voor het verkrijgen van de labels van een maand*/
    /** @} */
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
     * Genereert trendgegevens van een protocol
     * @param String $protocolNaam gegeven protocol
     * @return Array Flot-compatible array met trend van 1 protocol 
     */
    public function getProtocolTrendData($protocolNaam){
        $con = $this->openConnection();
        $result = null;
        $datums = array();
        $data = array();
        if(is_null($protocolNaam)){
            $result = $con->query("SELECT  MAX(`datum`) AS `datum`, `shouldTotaal`, `doneTotaal` FROM `protocoltotalen` WHERE `datum` = -1 GROUP BY `datum`");
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
    
    /**
     * Genereert categoriele data voor een maand voor alle geregistreerde data
     * @param JSTimestamp $maand datum als JS timestamp
     * @return Array Flot-compatible array met protocol gegevens van 1 maand
     */
    public function getProtocollenMaandData($maand){
        $con = $this->openConnection();
        $result = null;
        $labels = array();
        $data = array();
        
        if(is_null($maand)){
            $result = $con->query("SELECT `naam`, `shouldTotaal`, `doneTotaal` FROM `protocoltotalen` WHERE `datum` = (SELECT MAX(datum) FROM `protocoltotalen`) GROUP BY `naam` ORDER BY `naam`");
        } else {
            $result = $con->query("SELECT `naam`, `shouldTotaal`, `doneTotaal` FROM `protocoltotalen` WHERE `datum` = '" . ($this->JStoSQLdate($maand)) . "' GROUP BY `naam` ORDER BY `naam`");
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
    
    /**
     * Genereert trenddata. Een gewogen gemiddelde tussen alle bekende gevens. 
     * De weging is over een aantal uitgevoerde protocolen.
     * @return Array Flot-compatible array met ongewogen gemiddelden van alle bekende gegevens 
     */
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
    
    /**
     * Verkrijgt laatst bekend maand bekend in de database.
     * @return Date laatst bekende maand in database
     */
    public function getLaatsteMaand(){
        $con = $this->openConnection();
        $result = $con->query("SELECT MAX(datum) as maxD FROM `protocoltotalen`");
        $row = $result->fetch_array(MYSQLI_ASSOC);
        return $row['maxD'];
    }
    
    // </editor-fold>
    
    // <editor-fold desc="public : JSON-sets">
    /*
     * ******************************************
     *      voor het verkrijgen van JSON-sets
     * ******************************************
     */
    
    /**
     * Genereert JSON set uit grafiek. Heeft een $type nodig bekend in de DataManager class voor referentie
     * Gebruikt self:: wanneer extending op deze class of DataManager::.
     * @param DataManager::const $type Type invoer bekend in class
     * @param various $option null of invoer voor specifiek datum/protocol
     * @return json dataset zoals flot die accepteert.
     */
    public function getJSONset($type, $option){
        $output = array();
        if(is_null($option)){
            $output = array('label' => $type);
        }
        
        switch ($type) {
            case self::JAAR_TREND :
                $data = $this->getJaarTrendData();
                if(is_null($option))
                    unset ($output['label']);
                break;
            case self::MAAND :
                $data = $this->getProtocollenMaandData($option);
                $datum = null;
                if(!is_null($option)){
                    $datum = $this->JStoUIdate($option);
                } else {
                    $datum = $this->SQLtoUIdate($this->getLaatsteMaand());
                }
                $output['label'] = $datum;
                $output['ticks'] = $this->getProtocolLabelsJSON($option);
                break;
            case self::PROTOCOL_TREND :
                $data = $this->getProtocolTrendData($option);
                if(!is_null($option))
                    $output['label'] = $option;
                break;
            case self::LABELS :
                $returnVal = $this->getProtocolLabelsJSON($option);
                return $returnVal;
                break;
            default:
                die("Unkown graph-type");
        }
        
        $output['data'] = $data;
        return json_encode($output);
    } 
    // </editor-fold>
    
    // <editor-fold desc="public : labels">
     /*
     * ******************************************
     *  voor het verkrijgen van labels / procol-namen
     * ******************************************
     */  
    
    /**
     * 
     * @param JSTimestamp $maand datum van bekend
     * @return Array Flot compatible
     */
    public function getProtocolLabels($maand){
        $con = $this->openConnection();
        $result = null;
        $labels = array();
        $namen = array();
        
        if(is_null($maand)){
            $result = $con->query("SELECT DISTINCT `naam` FROM `protocoltotalen` WHERE `datum` = (SELECT MAX(datum) FROM `protocoltotalen`) ORDER BY `naam`");
        } else {
            $result = $con->query("SELECT DISTINCT `naam` FROM `protocoltotalen` WHERE `datum` = '" . $this->JStoSQLdate($maand) . "' ORDER BY `naam`" );
        }
        
        $i = 1;
        while($row = $result->fetch_array()) {
            array_push($labels, $i);
            array_push($namen, $row['naam']);
            $i++;
        }
        
        return $this->getMap($labels, $namen);
    }
    
    /**
     * Verkrijgt labels van de gegeven maand
     * @param JSTimestamp $maand maand voor het verkrijgen van labels
     * @return JSON labels van gegeven maand.
     */
    public function getProtocolLabelsJSON($maand){
       $output = json_encode($this->getProtocolLabels($maand));
       return $output;
    }
     // </editor-fold>
    
    // <editor-fold desc="public : static">
    /*
     * ******************************************
     *      (public) Static functies
     * ******************************************
     */  
    
    /**
     * Functie voor het compatible maken van php-arrays voor Flot.
     * @todo move naar util-class
     * @param Array $labels De x-punten / namen
     * @param Array $amounts De y-punten
     * @return Array Flot compatible gegevens 
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
    /** \addtogroup  constants gebruikt voor generatie javascript
     * @todo refactor naar util-class
    *  @{
    */
    private function SQLtoJStimestamp($date){
        return strtotime($date) * 1000;
    }
    
    private function SQLtoUIdate($date){
        return date('M-Y', strtotime($date));
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
    /** @} */
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
 * Geisoleerde functie om de gebruikte get_map functie werkbaar te maken
 * @param array $label x-cords
 * @param array $amount y-cords
 * @return array flot-compatible 
 */
function make_pair($label, $amount) {
    return array($label, $amount);
}
?>
