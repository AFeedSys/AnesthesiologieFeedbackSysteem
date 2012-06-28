<? 
session_start();
$_SESSION['userType'] = 'gebruiker';
$_SESSION['login'] = 1; 

require_once 'loaders/AutoLoader.php';

//Setting up page. CONFIG HERE
$graphs = array();

/**
 * Constructor subClasses Graphs
 *  $titel, 
 *  $type, 
 *  $holder, 
 *  $beschrijving, 
 *  $tooltip, 
 *  $updatesHolder
 */
array_unshift($graph, new TrendGraph(
        "Trend Gemiddelde", 
        DataManager::JAAR_TREND, 
        "content1",
        "",
        "Click om van geselecteerde maand de resultaten te zien", 
        "content2") );
array_unshift($graph, new ProtocolOverviewGraph(
        "Data van de Maand", 
        DataManager::MAAND, 
        "content2", 
        "",
        "Click om van het geselecteerde protocol de trendlijn in te zien", 
        "content3") );
array_unshift($graph, new TrendGraph(
        "Protocol Specifieke Data", 
        DataManager::PROTOCOL_TREND, 
        "content3",
        "",
        "Click om van die maand alle procotollen te zien.", 
        "content2") );

$writer = new IndexWriter($graph);

$datasets = $writer->writeDataFlotsets(null, null);
?>

<html>
    <head>
        <script language="javascript" type="text/javascript" src="./lib/jquery-1.7.2.js"></script>
        <script language="javascript" type="text/javascript" src="./lib/flot/jquery.flot.js"></script>
        <script language="javascript" type="text/javascript" src="./lib/flot/jquery.flot.resize.js"></script>
    </head>
    
    <body>
        <? var_dump($_SESSION['userType']); ?>
        <?= $writer->writePlaceholders(null); ?>
        <!-- ScriptBlock for the created plots -->
        <script language="javascript" type="text/javascript">
$(function () {
    <?= $writer->writeScriptBlock(); ?>
});
        </script>
        
        <?$writer->testMap();?>
    </body>
</html>
