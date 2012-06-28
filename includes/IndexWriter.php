<?
include 'magic.php';

class IndexWriter {

    private $graphs;
    private $dManager;

    function __construct($graphs) {
        $this->graphs = $graphs;
        $this->dManager = new DataManager();
    }

    public function getGraphs() {
        return $this->graphs;
    }

    public function getDManager() {
        return $this->dManager;
    }

    public function writePlaceholders() {
        $output = "";
        foreach ($graph as $this->graphs) {
            $output .= $graph->getHolderHTML();
        }
        return $output;
    }

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
