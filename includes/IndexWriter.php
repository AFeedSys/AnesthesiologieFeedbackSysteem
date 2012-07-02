<?

class IndexWriter {

    private $graphs; //array met graphs
    private $dManager; //DataManager
    
    /**
     * Constructor
     * @param array $graphs met FlotGraphs or subclasses
     */
    function __construct($graphs) {
        $this->graphs = $graphs;
        $this->dManager = new DataManager();
    }

    /**
     * Genereert HTML-elementen voor alle opgegeven FlotGraphs.
     * @return String/HTML 
     */
    public function writePlaceholders() {
        $output = "";
        foreach ( $this->graphs as $graph ) {
            $output .= $graph->getHolderHTML();
        }
        return $output;
    }
    
    /**
     * Genereert alle benodigde javascript elementen. Deze method moet wel omgeven worden door een script-element.
     * @return String/Javascript 
     */
    public function writeScriptBlock() {
        $scriptblock = '';
        //Get Shared content
                
        // options voor iedere graph
        foreach ($this->graphs as $graph){
            $scriptblock .= $graph->getOptionScript();
        }
        // plots & keybinds (AJAX)
        foreach ($this->graphs as $graph){
            $scriptblock .= $graph->getDataScript();
            $scriptblock .= $graph->getPlotScript();
            $scriptblock .= $graph->getBindScript();
        }
        
        //Add shared section. Needs a graph to funciton
        //$scriptblock .= $this->getSharedBlock();
        
        return $scriptblock;
    }
    
    public function getSharedBlock(){
        // Make year markings for each graph
        
        $reference = NULL;
        
        foreach($this->graphs as $graph){
            if ((is_object($graph)) && ($graph instanceof TrendGraph)){
                if($graph->getType() == DataManager::JAAR_TREND)
                    $reference = $graph;
                    break;
            }
        }
        
        if(is_null($reference)){
            die ('No general graph found');
        }
        
        $markings = array();
        $referenceSet = $graph->getDataSet();
        
        $zoek = array();
        
        foreach ($referenceSet as $dataPoint) {
            $zoek[$this->JStoPHPyears($dataPoint[0])] = 1;
        }
        
        $jaren = array_keys($zoek);
        
        //Creatie markers
        $markers = 'var markers = [
            ';
        foreach ($jaren as $jaar){
            $stamp = $this->PHPtoJStimestamp($jaar);
            $zoek[$jaar] = $stamp;
            $markers .= '{ color: "#000", lineWidth: 1, xaxis: { from: ' . $stamp . ', to: ' . $stamp .  ' } },
            ';
        }
        $markers .= '];';
        echo $markers; 
    }
    
    private function JStoPHPyears($stamp){
        return date('Y',$stamp / 1000);
    }
    
    private function PHPtoJStimestamp($date){
        return strtotime($date . '-01-01') * 1000;
    }
}

?>
