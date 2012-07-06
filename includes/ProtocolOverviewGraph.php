<?php

/**
 * @author Coen Meulenkamp <coenmeulenkamp - at - gmail.com>
 * @version ALPHAv1.0 Friday Release
 */
class ProtocolOverviewGraph extends FlotGraph {
    
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
    
    public function getBindScripts($beforeAjax='', $inAjax='', $afterAjax='', $customHoverMessage='') {
        
        $beforeAjax .= '
                clickValue = (' . parent::getJSVarNaam(self::OPTION_PREFIX) . '.xaxis.ticks[clickValue-1])[1];
            ';
        
        if($customHoverMessage == '') {
            $customHoverMessage .= parent::getJSVarNaam(self::OPTION_PREFIX) .'.xaxis.ticks[x-1][1] +" : <br />" + y + "%"';
        } else {
            $customHoverMessage .= $customHoverMessage;
        }
        
        
        return parent::getBindScripts($beforeAjax, $inAjax, $afterAjax, $customHoverMessage);
    }

}

?>
