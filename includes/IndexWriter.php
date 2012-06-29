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
        
        return $scriptblock;
    }

    public function testMap() {
    }
}

?>
