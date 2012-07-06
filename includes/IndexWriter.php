<?

/**
 * @author Coen Meulenkamp <coenmeulenkamp - at - gmail.com>
 * @version ALPHAv1.0 Friday Release
 */
class IndexWriter {

    private $graphs; //array met graphs
    private $dManager; //DataManager
    private $marks;
    
    /**
     * Constructor
     * @param array $graphs met FlotGraphs or subclasses
     */
    function __construct($graphs) {
        $this->graphs = $graphs;
        $this->dManager = new DataManager();
        $this->marks = array();
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
    public function writeScriptBlocks() {
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
            $scriptblock .= $graph->getBindScripts();
        }
        
        //Add shared section. Needs a graph to funciton
        //$scriptblock .= $this->getSharedBlock();
        
        return $scriptblock;
    }
    
    /**
     * Voegt gedeelde javascript elementen. Deze method moet wel omgeven worden door script-blok.
     * @return JavaScript/String
     */
    public function getSharedBlock(){
        $output = ''; 
        
        // Make year markings for each TrendGraph
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
        $this->marks = $zoek;
        
        //To output
        $output .= $markers; 
        
        //Add resize trigger to make the new anotations
           $output .= "
            $(window).resize(function() {
                anoteGraphs();
                $(\".tooltip\").remove();
            });
            
            var emptyGraph = {\"label\":\"protocol\",\"data\":[]};
     function showTooltip(x, y, contents) {
        $('<div id=\"tooltip\" class=\"tooltip\">' + contents + '</div>').css( {
                top: y+5,
                left: x+5,
        }).appendTo(\"body\").fadeIn(200);
    }

    var previousPoint = null;
    var maand=new Array();
        maand[0]=\"Januari\";
        maand[1]=\"Februari\";
        maand[2]=\"Maart\";
        maand[3]=\"April\";
        maand[4]=\"Mei\";
        maand[5]=\"Juni\";
        maand[6]=\"Juli\";
        maand[7]=\"Augustus\";
        maand[8]=\"September\";
        maand[9]=\"October\";
        maand[10]=\"November\";
        maand[11]=\"December\";
        
    function findFlotPoint(value, array){
        for(var i = 0 ; i < array.length; i++) {
            var item = array[i][0];
            if( item == value){
                return i;
            }
        }
    }
    ";
        
        return $output;
    }
    
    /**
     * Genereert javascript code voor het 
     * @todo Dynamisch maken van deze element, of verplaatsen buiten class.
     * @return JavaScript/String
     */
    public function annotatingMarkerScript(){
        $output = ' function anoteGraphs(){
                    $(".anotation").remove();
                    ';
            
        $output .= 'var o;
                    ';
        $trendGraphs = array();
        
        foreach ($this->graphs as $graph){
            if ((is_object($graph)) && ($graph instanceof TrendGraph)){
                array_push($trendGraphs, $graph);
            }
        }
        
        $jaren = array_keys($this->marks);
        $i = 0;
        
        foreach ($this->marks as $mark){
            foreach($trendGraphs as $graph){
                $output .= 'o = ' . $graph->getJSVarNaam(FlotGraph::PLOT_PREFIX) . '.pointOffset({ x: '. ($mark + 16846207) .', y: 15});
                        ';
                $output .= $graph->getJQuerySelector() . '.append(\'<div class="anotation" style="position:absolute;left:\' + (o.left + 4) + \'px;top: \' + o.top + \'px;color:#666;font-size:smaller">' . $jaren[$i] . '</div>\');
                        ';   
            }
            $i++;
        }
        return $output . '
            }
            anoteGraphs();
            plot_content1.highlight(0, data_content1.data[data_content1.data.length - 1]);
            '; //TODO move this out of here
    }
    
    /**
     * @todo marked voor refactoring, Bestaat al in DataManager
     */
    private function JStoPHPyears($stamp){
        return date('Y',$stamp / 1000);
    }
    
    /**
     * @todo marked voor refactoring, Bestaat al in DataManager
     */
    private function PHPtoJStimestamp($date){
        return strtotime($date . '-01-01') * 1000;
    }
}

?>
