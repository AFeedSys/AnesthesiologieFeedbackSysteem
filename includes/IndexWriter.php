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
        foreach ($toBePlaced as $this->graphs) {
            $output .= $toBePlaced->getHolderHTML();
        }
        return $output;
    }

    public function writeDataFlotsets() {
        //Schrijf de data blokken.
        foreach ($graph as $this->graphs) {
            $test = $this->dManager->getJSONset($graph->type, null);
            $graph->jsonset = $test;
        }
    }

    public function scriptWriter() {
        $scriptblock = '
        var protocolOptions = {
            bars: {
                show: true, 
                barWidth: 0.9, 
                align: "center",
            },
            grid: { hoverable: true, clickable: true, mouseActiveRadius: 50 },
            yaxis: { max: 100 },
            xaxis: { ticks: ' . $this->dManager->getJSONLabelMaand() . '}  
        };
            
        var timedOptions = {
            bars: {
                show: true, 
                barWidth: 28*24*60*60*1000, 
                align: "center",
            },
            grid: { hoverable: true, clickable: true, mouseActiveRadius: 50 },
            yaxis: { max: 100 },
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
        var data_' . $currentHolder . ' = ' . $currentSet . ';
        var plot_' . $currentHolder . ' = $.plot($("#' . $currentHolder . '"), [' . $currentSet . '],' . ($currentType == DataManager::maand ? "protocolOptions" : "timedOptions") . ');

        $("#' . $currentHolder . '").bind("plotclick", function (event, pos, item) {
            plot_' . $currentHolder . '.unhighlight();
            if (item) {
                alert("You clicked point " + item.datapoint + " in " + item.series.label + ".");
                plot.highlight(item.series, item.datapoint);
            };
        });
        ';
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
