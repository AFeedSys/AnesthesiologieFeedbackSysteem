<?php 
    require 'flotWriter.php';
?>

<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="lib/flot/excanvas.min.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="./lib/jquery.js"></script>
<script language="javascript" type="text/javascript" src="./lib/flot/jquery.flot.js"></script>
<script language="javascript" type="text/javascript">
$(function () {
    <?php
        $datasets = NULL;
        $datasets = array_push( writeDataset("bottom", $generaal) );
        $datasets = array_push( writeDataset("middle", $maand) );
        //$datasets = array_push( writeDataset("top", $procotol, null) );
        
        $placeholders = NULL;
        foreach ($datasets as $dataset) {
            $placeholders = array_push("hold_" . $dataset);
        }
        
        invokePlots($placeholders, $datasets);
        ?> 
    
    function onDataReceived(series) {
        
    }
    
    $.ajax({
            url: './handlers/flotrequest.php',
            method: 'GET',
            dataType: 'json',
            success: onDataReceived
        });
    
});
</script>
