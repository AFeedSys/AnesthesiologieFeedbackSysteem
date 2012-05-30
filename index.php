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

$datasets = $writer->writeDatasets()
?>

<html>
    <head>
        <script language="javascript" type="text/javascript" src="./lib/jquery.js"></script>
        <script language="javascript" type="text/javascript" src="./lib/flot/jquery.flot.js"></script>
    </head>
    
    <body>
        <?= $writer->writePlaceholders(null); ?>
        <script language="javascript" type="text/javascript">
$(function () {
    var sin = [], cos = [];
    for (var i = 0; i < 14; i += 1) {
        sin.push([i, Math.sin(i)]);
        cos.push([i, Math.cos(i)]);
    }

    var plot = $.plot($("#placeholder"),
           [ { data: sin, label: "sin(x)"}, { data: cos, label: "cos(x)" } ], {
               series: {
                   bars: { show: true }
               },
               grid: { hoverable: true, clickable: true },
               yaxis: { min: -1.2, max: 1.2 }
             });

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

    var previousPoint = null;
    $("#placeholder").bind("plothover", function (event, pos, item) {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));

        if ($("#enableTooltip:checked").length > 0) {
            if (item) {
                if (previousPoint != item.dataIndex) {
                    previousPoint = item.dataIndex;
                    
                    $("#tooltip").remove();
                    var x = item.datapoint[0].toFixed(2),
                        y = item.datapoint[1].toFixed(2);
                    
                    showTooltip(item.pageX, item.pageY,
                                item.series.label + " of " + x + " = " + y);
                }
            }
            else {
                $("#tooltip").remove();
                previousPoint = null;            
            }
        }
    });
    
    $("#placeholder").bind("plotclick", function (event, pos, item) {
        plot.unhighlight();
        if (item) {
            $("#clickdata").text("You clicked point " + item.dataIndex + " in " + item.series.label + ".");
            plot.highlight(item.series, item.datapoint);
        }
    });
});
        </script>
    </body>
</html>