<? 
session_start();
$_SESSION['userType'] = 'gebruiker';
$_SESSION['login'] = 1; 

require_once 'loaders/AutoLoader.php';

//Setting up page. CONFIG HERE
$graphs = array();

/*
 * Constructor subClasses Graphs
 *  $titel, 
 *  $type, 
 *  $holder, 
 *  $beschrijving, 
 *  $tooltip, 
 *  $updatesHolder
 *  $updateType
 */
array_push($graphs, new TrendGraph(
        "Jaaroverzicht", 
        DataManager::JAAR_TREND, 
        "content1",
        "",
        "Klik om van de geselecteerde maand de resultaten te zien", 
        "content2",
        DataManager::MAAND) );
array_push($graphs, new ProtocolOverviewGraph(
        "Protocoloverzicht van geselecteerde Maand", 
        DataManager::MAAND, 
        "content2", 
        "",
        "Klik om van het geselecteerde protocol de trendlijn in te zien", 
        "content3",
        DataManager::PROTOCOL_TREND) );
array_push($graphs, new TrendGraph(
        "Maandoverzicht van geselecteerde Protocol", 
        DataManager::PROTOCOL_TREND, 
        "content3",
        "",
        "Klik om van de geselecteerde maand alle procotollen te zien.", 
        "content2",
        DataManager::MAAND) );


$writer = new IndexWriter($graphs);
include 'totaal.php';
?>