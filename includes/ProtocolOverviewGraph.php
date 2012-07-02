<?php

/**
 * Description of BarGraph
 *
 * @author Coen
 */
class ProtocolOverviewGraph extends FlotGraph {

    //put your code here    
    function __construct($titel, $type, $holder, $beschrijving, $tooltip, $updatesHolder, $updateType) {
        parent::__construct($titel, $type, "", $holder, $beschrijving, $tooltip, $updatesHolder, $updateType);
    }
    
    public function getOptionScript() {
        return '
    var ' . parent::getJSVarNaam(self::OPTION_PREFIX) . ' = {
        bars: {
            show: true,
            barWidth: 0.9,
            align: "center",
        },
        xaxis: { 
            ticks: ' . parent::getDManager()->getProtocolLabelsJSON(null) . ',
            autoscaleMargin: 0.1,
        }, 
        ' . self::BASIS_OPTIES . '
    };';
    }
    
    public function getBindScript($before='', $in='', $after='') {
        
        $before .= '
                clickValue = (' . parent::getJSVarNaam(self::OPTION_PREFIX) . '.xaxis.ticks[clickValue-1])[1];
            ';
        
        return parent::getBindScript($before, $in, $after);
    }

}

?>
