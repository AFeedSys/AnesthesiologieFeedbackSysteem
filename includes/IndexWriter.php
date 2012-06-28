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
        foreach ($graph as $this->graphs) {
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
        // options voor iedere graph
        foreach ($graph as $this->graphs){
            $scriptblock .= $graph->getOptionScript();
        }
        // plots & keybinds (AJAX)
        foreach ($graph as $this->graphs){
            $scriptblock .= $graph->getDataScript();
            $scriptblock .= $graph->getPlotScript();
            $scriptblock .= $graph->getBindScript();
        }
        
        return $scriptblock;
    }

    public function testMap() {
        //var_dump($this->dManager->getMap($this->datasets, $this->placeholders));
        var_dump($this->jsonsets);
        //var_dump(json_decode($this->jsonsets[1]));
    }
}

?>
