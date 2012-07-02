<?php
/**
 * Alle includes op een rij in juiste volgorede van dependency.
 */
    $path = $_SERVER['DOCUMENT_ROOT'];
    $path .= '/afeedsys';
    
    // Geen dependency
    require_once $path . '/managers/DataManager.php';
    
    // MOGELIJK: Datamanager
    require_once $path . '/includes/FlotGraph.php'; //SuperClass + DataManagers
    require_once $path . '/includes/ProtocolOverviewGraph.php'; //DataManager
    require_once $path . '/includes/TrendGraph.php';
    
    // DataManger + FlotGraphs
    require_once $path . '/includes/IndexWriter.php';
    
    // Isolated functions
    require_once $path . '/includes/loginCheck.php';


?>
