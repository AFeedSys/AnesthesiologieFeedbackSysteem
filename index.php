<? 
session_start();
$_SESSION['userType'] = 'gebruiker';
$_SESSION['login'] = 1; 

include_once 'loaders/AutoLoader.php';

//Setting up page. CONFIG HERE
$graphs = array();
array_unshift($graph, new FlotGraph("Data generieke uitkomsten per Maand", DataManager::generaal, "content1", "", "Click om van geselecteerde maand de resultaten te zien", "content2"));
array_unshift($graph, new FlotGraph("Data van de Maand", DataManager::maand, "content2", $beschrijving, "Click om van het geselecteerde protocol de trendlijn in te zien", "content3"));
array_unshift($graph, new FlotGraph("Protocol Specifieke Data", DataManager::protocol, "content3", "", "Click om van die maand alle procotollen te zien.", "content2"));

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
    <?= $writer->scriptWriter(); ?>
});
        </script>
        
        <?$writer->testMap();?>
    </body>
</html>
