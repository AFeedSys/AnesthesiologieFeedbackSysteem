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
 */
array_push($graphs, new TrendGraph(
        "Protocol Specifieke Data", 
        DataManager::PROTOCOL_TREND, 
        "content1",
        "",
        "Click om van die maand alle procotollen te zien.", 
        "content2") );
array_push($graphs, new ProtocolOverviewGraph(
        "Data van de Maand", 
        DataManager::MAAND, 
        "content2", 
        "",
        "Click om van het geselecteerde protocol de trendlijn in te zien", 
        "content1") );
array_push($graphs, new TrendGraph(
        "Trend Gemiddelde", 
        DataManager::JAAR_TREND, 
        "content3",
        "",
        "Click om van geselecteerde maand de resultaten te zien", 
        "content2") );

$writer = new IndexWriter($graphs);
?>

<html>
    <head>
        <script language="javascript" type="text/javascript" src="./lib/jquery-1.7.2.js"></script>
        <script language="javascript" type="text/javascript" src="./lib/flot/jquery.flot.js"></script>
        <script language="javascript" type="text/javascript" src="./lib/flot/jquery.flot.resize.js"></script>
        <style type="text/css">
            .content1,.content2,.content3{
                width: 800px;
                height: 30%;
            }
        </style>
    </head>
    
    <body>
        <? var_dump($_SESSION['userType']); ?>
        <?= $writer->writePlaceholders(null); ?>
        <?= $result->mysqli_fetch_array() ?>
        
        <!-- ScriptBlock for the created plots -->
        <script language="javascript" type="text/javascript">
$(function () {
    <?= $writer->writeScriptBlock(); ?>
});
        </script>
    </body>
</html>
