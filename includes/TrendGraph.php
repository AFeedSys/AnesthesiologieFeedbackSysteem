<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TrendGraph
 *
 * @author Coen
 */
class TrendGraph extends FlotGraph {

    function __construct($titel, $type, $holder, $beschrijving, $tooltip, $updatesHolder, $updateType) {
        parent::__construct($titel, $type, "", $holder, $beschrijving, $tooltip, $updatesHolder, $updateType);
    }
    
    public function getOptionScript() {
        return '
    var ' . parent::getJSVarNaam(self::OPTION_PREFIX) . ' = {
        lines: {
            show: true, 
        },
        points: {
            show: true,
        },
        xaxis: { 
            mode: "time",
            timeformat: "%b",
            tickSize: [1, "month"],
            monthNames: ["Jan", "Feb", "Maa", "Apr", "Mei", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dec"],
        },
        ' . self::BASIS_OPTIES . '
    };';
    }

}

?>
