<?php
    $generaal = "generaal";
    $maand = "maand";
    $protocol = "protocol";
    
    function writeDataset($target, $type, $option){
        echo "var set_" . $target . " ="; 
        
        switch ($option) {
            case $general:
                
                break;
            case $maand
                if ($option == null){
                    
                } else {
                    
                }
                break;
            case $protocol
                if ($option == null)
                    die ("Protocol specifiek dataset heeft een protocol nodig als optie")
                break;
            default:
                //die("Unkown graph-type");
        }
        
        return "set_" . $target;
    } 
    function invokePlots($placeholders, $datasets){?>


<?php } ?>
