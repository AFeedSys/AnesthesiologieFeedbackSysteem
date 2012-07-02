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
        $output = '';
        
        $output .= '
    var ' . parent::getJSVarNaam(self::OPTION_PREFIX) . ' = {
        series: { 
            lines: {
                show: true, 
            },
            points: {
                show: true,
            },
        },
        //colors: [#BAE8FF, #]
        
        xaxis: { 
            mode: "time",
            timeformat: "%b",
            tickSize: [1, "month"],
            monthNames: ["Jan", "Feb", "Maa", "Apr", "Mei", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dec"],
            autoscaleMargin: 0.05,
        },
        ' . self::BASIS_OPTIES . '
    };';
        
        return $output;
    }
    
    public function getBindScript($before = '', $in = '', $after = '') {
        
        $updateHolder = parent::getUpdatesHolder();
        
        $varName = self::AJAX_PREFIX . '_labels_' . $updateHolder;
        $updateOption = self::OPTION_PREFIX . $updateHolder;
        if(parent::getUpdateType() == DataManager::MAAND){
            $in ='
                ' . $updateOption . '.xaxis.ticks = eval(response.ticks);                
                ' . $in;
        }
        
        return parent::getBindScript($before, $in, $after);
    }


}

?>
