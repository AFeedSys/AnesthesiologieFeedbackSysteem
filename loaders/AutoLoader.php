<?php
/**
 * Alle includes op een rij in juiste volgorede van dependency.
 */
    // Geen dependency
    require_once 'managers/DataManager.php';
    
    // MOGELIJK: Datamanager
    require_once 'includes/FlotGraph.php'; //SuperClass + DataManagers
    require_once 'includes/ProtocolOverviewGraph.php'; //DataManager
    require_once 'includes/TrendGraph.php';
    
    // DataManger + FlotGraphs
    require_once 'includes/IndexWriter.php';


?>
