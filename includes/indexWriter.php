<?
include 'magic.php';

class IndexWriter {
    const generaal = "generaal";
    const maand = "maand";
    const protocol = "protocol";

    private $datasets;
    private $placeholders;
    private $typeOrder;

    function __construct($placeholders, $typeOrder){
        $this->isMatchedSized($placeholders, $typeOrder);
        $this->placeholders = $placeholders;
        $this->typeOrder = $typeOrder;
    }

    public function getPlaceholders() {
        return $this->placeholders;
    }

    public function setPlaceHolders($value) {
        $this->placeholders = $value;
    }

    public function getDatasets(){
        return $this->datasets;
    }

    public function setDatasets($value) {
        $this->datasets = $value;
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
    
    public function writeDataSets($otherPlaceholders, $otherTypes) {        
        $datasets = array();
        
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
        //Test
        $this->isMatchedSized($toBePlaced, $toBeTyped);
        //WriteDatasets
        for ($i = 0; $i<count($toBePlaced); $i++){
            array_push($datasets, $this->writeDataset($toBeTyped[$i], $toBeTyped[$i], null));
        }
        
        $this->datasets = $datasets;
        return $datasets;
    }
    
    public function writeDataset($target, $type, $option ){
        $label = $type;
        $output = json_encode($label);
        switch ($type) {
            case self::generaal :
                //TestData will be initial call
                //$data = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5)';
                    $data = '[[0,1],[1,2],[2,3],[3,4],[4,5]]';
                break;
            case self::maand :
                if ($option == null){
                    //TestData will be initial call
                    $data = '[[0,5],[1,6],[2,7],[3,8],[4,9]]';
                    //$data = array(1 => 4,2 => 5,3 => 6,4 => 7,5 => 8);
                } else {

                }
                break;
            case self::protocol :
                if ($option == null) {
                    //Testdata will be intial dataset-call.
                    //$data = array(1 => 0,2 => 0,3 => 0,4 => 0,5 => 0);
                    $data =  '[[0,0],[1,0],[2,0],[3,0],[4,0]]';
                }
                    
                break;
            default:
                die("Unkown graph-type");
        }
        //$output = json_encode($data);
        //var_dump($output);
        return $data;
    } 
    
    public function scriptWriter(){
        $scriptblock = "";
        for ($i = 0; $i < count($this->datasets); $i++) {
    $scriptblock .= 
        '
        var data_' . $this->placeholders[$i] . ' = '. $this->datasets[$i] .';
        var plot_'. $this->placeholders[$i] . ' = $.plot($("#' . $this->placeholders[$i] . '"), [data_' . $this->placeholders[$i] . '], {            
            bars: { show: true },
            grid: { hoverable: true, clickable: true },
            yaxis: { max: 10 }
        }    
        );


        $("#' . $this->placeholders[$i] . '").bind("plotclick", function (event, pos, item) {
            plot_'. $this->placeholders[$i] . '.unhighlight();
            if (item) {
                alert("You clicked point " + item.dataIndex + " in " + item.series.label + ".");
                plot.highlight(item.series, item.datapoint);
            };
        });
        ';
        }
        
        return $scriptblock;
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
}
?>
