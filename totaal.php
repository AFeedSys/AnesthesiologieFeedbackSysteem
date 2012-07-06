<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?
/**
 * @author Coen Meulenkamp <coenmeulenkamp - at - gmail.com>
 * @version ALPHAv1.0 Friday Release
 */
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="./styles/main.css" />
        <link rel="stylesheet" type="text/css" href="./styles/loading.css" />

        <script language="javascript" type="text/javascript" src="./lib/jquery-1.7.2.js"></script>
        <script language="javascript" type="text/javascript" src="./lib/flot/jquery.flot.js"></script>
        <script language="javascript" type="text/javascript" src="./lib/flot/jquery.flot.resize.js"></script>

        <title>AFeedSys</title>
    </head>

    <body>

        <div class="container" id="pagewidth">
            <div class="header">
                <h1>
                <img class="AMClogo" src="amc.png" width="120" height="65" align="middle" title="AMC">
                <img class="feedsyslogo" src="logo.png" height="100px">
                </h1>
            </div>

            <div class="menu"> 
                <?php include 'menu.php' ?>
            </div>
            <div class="graphs">
                <?= $writer->writePlaceholders(); ?>            
            </div>
            <div class="footer"> 
                <?php include 'footer.php' ?>
            </div>

        </div>
        
        <!-- ScriptBlock for the created plots -->
        <script language="javascript" type="text/javascript">
$(function () {
    <?= $writer->getSharedBlock(); ?>
    <?= $writer->writeScriptBlocks(); ?>
    <?= $writer->annotatingMarkerScript()?>
});
        </script> 
    </body>
</html>
