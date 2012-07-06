<?php
/**
 * @author Coen Meulenkamp <coenmeulenkamp - at - gmail.com>
 * @version ALPHAv1.0 Friday Release
 */
    session_start();    
    //check of ajax-request is
    if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
        die("Stay off my lawn!");
    }

    // Hiermee is het mogelijk om fouten af te handelen in javascript.
    function handleException( $e ) {
       echo json_encode( $e );
    }
    set_exception_handler( 'handleException' );


    // setup van de request handler
    include '../loaders/AutoLoader.php';
    
    $manager = new DataManager();
    
    // check benodigde ouput
    if(!(isset($_REQUEST['target']) && isset($_REQUEST['option']))){
        throw new Exception("Needed variables not set!");
    }
    
    $target = $_REQUEST['target'];
    $option = $_REQUEST['option'];
    echo $manager->getJSONset($target, $option);
?>
