<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BarGraph
 *
 * @author Coen
 */
class ProtocolOverviewGraph extends FlotGraph {
    
    private $dManager = null;

    //put your code here    
    function __construct($titel, $type, $holder, $beschrijving, $tooltip, $updatesHolder) {
        parent::__construct($titel, $type, null, $holder, $beschrijving, $tooltip, $updatesHolder);
        $this->$dManager = new DataManager();
    }
    
    public function getOptionScript() {
        return '
    var ' . self::OPTION_PREFIX . $this->titel . ' = {
        bars: {
            show: true,
            width: 0.9,
            align: "center",
        },
        xaxis: { 
            ticks: ' . $this->dManager->getProtocolLabelsJSON(null) . ',
        }, 
        ' . self::BASIS_OPTIES . '
    };';
    }
}

?>
