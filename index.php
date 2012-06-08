<? 
session_start();
$_SESSION['userType'] = 'gebruiker';
$_SESSION['login'] = 1; 
//Control if user has logged on
//include './includes/loginCheck.php';
//Other requirements
require './includes/indexWriter.php';

//Setting up page. CONFIG HERE
$placeholders = array("topGraph", "middleGraph", "bottomGraph");
$typeOrder = array(DataManager::protocol, DataManager::maand, DataManager::generaal);
$writer = new IndexWriter($placeholders, $typeOrder);

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
