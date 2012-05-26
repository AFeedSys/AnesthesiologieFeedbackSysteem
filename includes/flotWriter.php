<?php
    $generaal = "generaal";
    $maand = "maand";
    $protocol = "protocol";
    
    function writeDataset($target, $type, $option = null){
        echo "var set_" . $target . " ="; 
        
        switch ($option) {
            case $general:
                //TestData
                echo '[[0,1],[1,2],[2,3],[3,4],[4,5]];';
                break;
            case $maand:
                if ($option == null){
                    //TestData
                    echo '[[0,5],[1,6],[2,7],[3,8],[4,9]];';
                    
                } else {
                    
                }
                break;
            case $protocol:
                if ($option == null) {
                    die ("Protocol specifiek dataset heeft een protocol nodig als optie");
                }
                    echo '';
                break;
            default:
                die("Unkown graph-type");
        }
        return "set_" . $target;
    } 
    function invokePlots($placeholders, $datasets){?>


<?php } ?>
