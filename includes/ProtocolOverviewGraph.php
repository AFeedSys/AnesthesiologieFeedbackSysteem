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

    //put your code here    
    function __construct($titel, $type, $holder, $beschrijving, $tooltip, $updatesHolder) {
        parent::__construct($titel, $type, "", $holder, $beschrijving, $tooltip, $updatesHolder);
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
            ticks: ' . $this->getDManager()->getProtocolLabelsJSON(null) . ',
        }, 
        ' . self::BASIS_OPTIES . '
    };';
    }
}

?>