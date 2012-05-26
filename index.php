<html>
    <head>
        <?php 
            require './includes/scriptBlock.php';
        ?>
    </head>
    
    <body>
        <?php foreach ($placeholders as $placeholder) {?>
        <div id="<?php echo $placeholder; ?>" style="width:600px;height:300px"></div>
        <?php } ?>
    </body>
</html>