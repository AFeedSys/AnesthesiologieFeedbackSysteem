<? 
session_start();
//Control if user has logged on
//include './includes/loginCheck.php';
//Other requirements
require './includes/indexWriter.php';
//Setting up page. CONFIG HERE
$placeholders = array("topGraph", "middleGraph", "bottomGraph");
$typeOrder = array(IndexWriter::protocol, IndexWriter::maand, IndexWriter::generaal);
$writer = new IndexWriter($placeholders, $typeOrder);

$datasets = $writer->writeDataSets(null, null);
?>

<html>
    <head>
        <script language="javascript" type="text/javascript" src="./lib/jquery-1.7.2.js"></script>
        <script language="javascript" type="text/javascript" src="./lib/flot/jquery.flot.js"></script>
    </head>
    
    <body>
        <?= $writer->writePlaceholders(null); ?>
        <!-- ScriptBlock for the created plots -->
        <script language="javascript" type="text/javascript">
    $(function () {
    
<?= $writer->scriptWriter(); ?>
    function showTooltip(x, y, contents) {
        $('<div id="tooltip">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#fee',
            opacity: 0.80
        }).appendTo("body").fadeIn(200);
    }
});
        </script>
    </body>
</html>
