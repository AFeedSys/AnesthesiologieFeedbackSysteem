<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="./styles/totaal2.css">
        <link rel="stylesheet" type="text/css" href="./styles/stijl.css">

        <script language="javascript" type="text/javascript" src="./lib/jquery-1.7.2.js"></script>
        <script language="javascript" type="text/javascript" src="./lib/flot/jquery.flot.js"></script>
        <script language="javascript" type="text/javascript" src="./lib/flot/jquery.flot.resize.js"></script>

        <title>AFeedSys</title>
    </head>

    <body>

        <div class="container">
            <div class="header">
                <h1>
                <img class="logo" src="amc.png" width="120" height="65" align="middle" title="AMC">
                <img class="feedsyslogo" src="Afeedsyslogo1.png" width="15%">
                </h1>
            </div>

            <div class="menu"> 
                <?php include 'menu.php' ?>
            </div>
                <?= $writer->writePlaceholders(); ?>            
            <div class="footer"> 
                <?php include 'footer.php' ?>
            </div>

        </div>
 
</body>
</html>
