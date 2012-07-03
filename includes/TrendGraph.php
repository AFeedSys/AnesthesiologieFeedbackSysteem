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
    
    const JS_MAAND = 1129743000 ;
    
    private static $minX;
    private static $maxX;

    function __construct($titel, $type, $holder, $beschrijving, $tooltip, $updatesHolder, $updateType) {
        parent::__construct($titel, $type, "", $holder, $beschrijving, $tooltip, $updatesHolder, $updateType);
        
        
        
        $referenceSet = json_decode(parent::getJsonset(), TRUE);
        $referenceData = $referenceSet['data'];
        $zoek = array();
        
        foreach ($referenceData as $dataPoint) {
            array_push($zoek, $dataPoint[0]);
        }
        if(array_key_exists(0, $zoek)){
            if(is_null(self::$minX)){
                self::$minX = $zoek[0] - self::JS_MAAND;
                self::$maxX = $zoek[(count($zoek) -1)] + self::JS_MAAND;
            } else {
                if(self::$minX > $zoek[0] ){
                    self::$minX = $zoek[0] - self::JS_MAAND;
                }
                if(self::$maxX > $zoek[0] ){
                    self::$maxX = $zoek[(count($zoek) -1)] + self::JS_MAAND;
                }
            }
        }
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
                radius: 7,
                fillColor: "#FFFFFF",
            },
        },
        
        xaxis: { 
            mode: "time",
            timeformat: "%b",
            tickSize: [1, "month"],
            monthNames: ["Jan", "Feb", "Maa", "Apr", "Mei", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dec"],
            autoscaleMargin: 0.05,
            min: ' . self::$minX . ',
            max: ' . self::$maxX . ',
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
