<?
include 'magic.php';
include 'managers\dataManager.php';

class IndexWriter {
    const generaal = "generaal";
    const maand = "maand";
    const protocol = "protocol";

    private $jsonsets;
    private $placeholders;
    private $typeOrder;
    private $dManager;

    function __construct($placeholders, $typeOrder){
        $this->isMatchedSized($placeholders, $typeOrder);
        $this->placeholders = $placeholders;
        $this->typeOrder = $typeOrder;
        $this->dManager = new dataManager();
    }

    public function getPlaceholders() {
        return $this->placeholders;
    }

    public function setPlaceHolders($value) {
        $this->placeholders = $value;
    }

    public function getJsonsets(){
        return $this->jsonsets;
    }

    public function setJsonsets($value) {
        $this->jsonsets = $value;
    }

    public function getTypeOrder(){
        return $this->typeOrder;
    }

    public function setTypeOrder($value) {
        $this->typeOrder = $value;
    }
    
    public function writePlaceholders($otherPlaceholders) {
        $toBePlaced = null;
        $output = "";
        if(is_null($otherPlaceholders)) {
            $toBePlaced = $this->placeholders;               
        } else {
            $toBePlaced = $otherPlaceholders;
        }
        foreach ($toBePlaced as $placeholder) {
            $output .= '<div id="' . $placeholder . '" class="' . $placeholder . '" style="width:600px;height:200px;"></div>';
        }

        return $output;
    }
    
    public function writeDataFlotsets($otherPlaceholders, $otherTypes) {        
        $flotsets = array();
        
        $toBePlaced = null;
        $toBeTyped = null;
        // Check if input is null for custom sets
            if(is_null($otherPlaceholders)){
                $toBePlaced = $this->placeholders;
            } else {
                $toBePlaced = $otherPlaceholders;
            }
            if(is_null($otherTypes)){
                $toBeTyped = $this->typeOrder;
            } else {
                $toBeTyped = $otherTypes;
            }
        //Test of instellingen en houders evengroot zijn
            $this->isMatchedSized($toBePlaced, $toBeTyped);
        //Schrijf de data blokken.
            for ($i = 0; $i<count($toBePlaced); $i++){
                $test = $this->writeDataFlotset($toBeTyped[$i], $toBeTyped[$i], null);
                array_push($flotsets, $test);
            }
        
        //Afsluiten
        $this->jsonsets = $flotsets;
        return $flotsets;
    } 
    
    /**
     *
     * @param type $target
     * @param type $type
     * @param type $option
     * @return json dataset zoals flot die accepteert.
     */
    public function writeDataFlotset($target, $type, $option ){
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

        $output["data"] = $this->dManager->getMap($points, $data);
        //var_dump($output);
        return json_encode($output);
    } 
    
    public function scriptWriter(){
        $scriptblock = '
        var protocolOptions = {
            bars: {
                show: true, 
                barWidth: 0.9, 
                align: "center",
                },
            grid: { hoverable: true, clickable: true },
            yaxis: { max: 10 },
        };
        var timeOptions = {
            bars: {
                show: true, 
                barWidth: 28*24*60*60*1000, 
                align: "center",
            },
            lines: {show: true,},
            grid: { hoverable: true, clickable: true, mouseActiveRadius: 50 },
            yaxis: { max: 10 },
            xaxis: { 
                mode: "time",
                timeformat: "%b",
                tickSize: [1, "month"],
                monthNames: ["Jan", "Feb", "Maa", "Apr", "Mei", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dec"]
                }  
        };
        ';
        for ($i = 0; $i < count($this->jsonsets); $i++) {
            $currentHolder = $this->placeholders[$i];
            $currentType = $this->typeOrder[$i];
            $currentSet = $this->jsonsets[$i];
            $scriptblock .= 
        '
        var data_' . $currentHolder . ' = '. $currentSet . ';
        var plot_'. $currentHolder . ' = $.plot($("#' . $currentHolder . '"), [' . $currentSet . '], ' . ($currentType == self::protocol ? "protocolOptions" : "timeOptions") . ');

        $("#' . $currentHolder . '").bind("plotclick", function (event, pos, item) {
            plot_'. $currentHolder . '.unhighlight();
            if (item) {
                alert("You clicked point " + item.dataIndex + " in " + item.series.label + ".");
                plot.highlight(item.series, item.datapoint);
            };
        });
        ';
        }
        
        return $scriptblock;
    }
    
    public function writeHoldersForJs(){
        $out = "";
        foreach ($toBePlaced as $placeholder) {
            $out .= "#" . $toBePlaced . ",";
        }
        return out;
    }
    
    private function isMatchedSized($array1, $array2){
        if(count($array1) != count($array2)) {
            writeError("Array-size doesn't match");
            echo '<strong>Array1:</strong> ';
            var_dump($array1);
            echo '<strong>Array2:</strong>';
            var_dump($array2);
        }
    }
    
    public function testMap(){
       //var_dump($this->dManager->getMap($this->datasets, $this->placeholders));
       var_dump($this->jsonsets);
       //var_dump(json_decode($this->jsonsets[1]));
    }
}
?>
