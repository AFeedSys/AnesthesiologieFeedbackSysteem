<?php
    //check of ajax-request is
//    if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
//        die("Stay off my lawn!");
//    }
    
    // setup van de request handler
    include '/managers/dataManager.php';
    
    $manager = new DataManager();
    
    // check benodigde ouput
    if(!(isset($_GET['type']) && isset($_GET['option']))){
        die ('none set');
    }
    
    $type = $_GET['type'];
    $option = $_GET['option'];
    
    echo $manager->writeDataFlotset($type, $option); 
?>
