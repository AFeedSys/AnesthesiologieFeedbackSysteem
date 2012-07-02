<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<<<<<<< HEAD
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
        
        <!-- ScriptBlock for the created plots -->
        <script language="javascript" type="text/javascript">
$(function () {
    <?= $writer->getSharedBlock(); ?>
    <?= $writer->writeScriptBlock(); ?>
});
        </script>
=======

<head>

<link rel="stylesheet" type="text/css" href="styles/totaal2.css">
<link rel="stylesheet" type="text/css" href="styles/stijl.css">
<script type="text/javascript" src="script.js"></script>

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
    <div class="content1">blok 1 </div>
    <div class="content2">blok 2 </div>
    <div class="content3">blok 3 </div>
            
    <div class="footer"> 
        <?php include 'footer.php' ?>
    </div>

</div>
>>>>>>> 750b5378b3d688de30e458c461909f12320bfc6c
 
    </body>
</html>
