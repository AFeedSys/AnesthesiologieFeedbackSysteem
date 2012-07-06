<?php

/**
 * @todo Verdere specialisatie tussen 
 * @see FlotGraph.php
 * @author Coen Meulenkamp <coenmeulenkamp - at - gmail.com>
 * @version ALPHAv1.0 Friday Release
 */
class TrendGraph extends FlotGraph {
    
    const JS_MAAND = 1129743000 ; /**< JS timestamp die een maand representeerd*/
    
    private static $minX; /**< static tussen graphs, geeft alle TrendGraphs gelijke tijdschalen. Minimale X*/
    private static $maxX; /**< static tussen graphs, geeft alle TrendGraphs gelijke tijdschalen. Maximale X */

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
                fillColor: "#F7EAAF",
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
    
    public function getBindScripts($beforeAjax='', $inAjax='', $afterAjax='', $customHoverMessage='') {
        
        $updateHolder = parent::getUpdatesHolder();
        
        if(parent::getUpdateType() == DataManager::MAAND){
            $updateOption = self::OPTION_PREFIX . $updateHolder;
            $innerAjax ='
                var rTicks = response.ticks;
                if(rTicks.indexOf(";") == -1){ // Vang hier misbruik eval op
                    ' . $updateOption . '.xaxis.ticks = eval(rTicks);
                } //TODO remove this evil method
                ';
            if(parent::getType() == DataManager::JAAR_TREND){
                $innerAjax .= 'plot_content3 = $.plot($("#content3"), [emptyGraph], option_content3); //TODO make this dynamic
                ';
            }
            if(parent::getType() == DataManager::PROTOCOL_TREND){
                $innerAjax .= 'plot_content1.unhighlight(); //TODO make this dynamic
                plot_content1.highlight(0, data_content1.data[findFlotPoint(item.datapoint[0], data_content1.data)]);
                ';
            }

        }
        
        $inAjax .= $innerAjax;
        
        if($customHoverMessage == '') {
            $customHoverMessage .= 'maand[dt.getMonth()] + " " + dt.getFullYear() + ": <br />" + y + "%"';
        } else {
            $customHoverMessage .= $customHoverMessage;
        }
        
        return parent::getBindScripts($beforeAjax, $inAjax, $afterAjax, $customHoverMessage);
    }


}

?>
