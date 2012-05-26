<?php 
    require 'flotWriter.php';
?>

<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="lib/flot/excanvas.min.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="lib/jquery.js"></script>
<script language="javascript" type="text/javascript" src="lib/flot/jquery.flot.js"></script>
<script language="javascript" type="text/javascript">
$(function () {
    <?php
        $datasets = NULL;
        $dataset = array_push( writeDataset("bottom", $generaal, null) );
        $dataset = array_push( writeDataset("middle", $maand, null) );
        $dataset = array_push( writeDataset("top", $procotol, null) );
        
        $placeholders = NULL;
        $placeholders = array_push("hold_bottom");
        $placeholders = array_push("hold_middle");
        $placeholders = array_push("hold_top");
        
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
