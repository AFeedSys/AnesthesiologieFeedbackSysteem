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
        "Click om van geselecteerde maand de resultaten te zien", 
        "content2",
        DataManager::MAAND) );
array_push($graphs, new ProtocolOverviewGraph(
        "Protocoloverzicht van geslecteerde Maand", 
        DataManager::MAAND, 
        "content2", 
        "",
        "Click om van het geselecteerde protocol de trendlijn in te zien", 
        "content3",
        DataManager::PROTOCOL_TREND) );
array_push($graphs, new TrendGraph(
        "Maandoverzicht van geselecteerde Protocol", 
        DataManager::PROTOCOL_TREND, 
        "content3",
        "",
        "Click om van die maand alle procotollen te zien.", 
        "content2",
        DataManager::MAAND) );


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
        <?= $writer->writePlaceholders(); ?>
        
        <div id="message"></div>
        <!-- ScriptBlock for the created plots -->
        <script language="javascript" type="text/javascript">
$(function () {
    <?= $writer->getSharedBlock(); ?>
    <?= $writer->writeScriptBlock(); ?>
});
        </script>
    </body>
</html>
