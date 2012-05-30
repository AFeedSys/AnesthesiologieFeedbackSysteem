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
        if(count($placeholders) != count($typeOrder)) {
            writeError("Array-size doesn't match");
            echo '<strong>Placeholders</strong> ';
            var_dump($placeholders);
            echo '<strong>TypeOrder</strong>';
            var_dump($typeOrder);
        }

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

    public function writePlaceholders($otherPlaceholders) {
        $toBePlaced = null;
        $output = "";
        if(is_null($otherPlaceholders)) {
            $toBePlaced = $this->placeholders;               
        } else {
            $toBePlaced = $otherPlaceholders;
        }
        foreach ($toBePlaced as $placeholder) {
            $output .= '<div id="' . $placeholder . '"></div>';
        }

        return $output;
    }
    
    public function writeDataSets($otherPlaceholders, $otherTypes) {
        $toBePlaced = null;
        $toBeUsed = null;
        
    }
    
    public function writeDataset($target, $type, $option ){
        $output = "";
        switch ($type) {
            case self::general:
                //TestData
                $output .= '[[0,1],[1,2],[2,3],[3,4],[4,5]]';
                break;
            case self::maand:
                if ($option == null){
                    //TestData
                    $output .= '[[0,5],[1,6],[2,7],[3,8],[4,9]]';

                } else {

                }
                break;
            case self::protocol:
                if ($option == null) {
                    die ("Protocol specifiek dataset heeft een protocol nodig als optie");
                }
                    $output .=  '[[0,0],[1,0],[2,0],[3,0],[4,0]]';
                break;
            default:
                die("Unkown graph-type");
        }
        return $output;
    } 
}
?>
