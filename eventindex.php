<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="lib/flot/excanvas.min.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="./lib/jquery-1.7.2.js"></script>
<script language="javascript" type="text/javascript" src="./lib/flot/jquery.flot.js"></script>
    </head>
<body>
    <h1>Flot Examples</h1>

    <div id="placeholder" style="width:1200px;height:300px"></div>

    <p>One of the goals of Flot is to support user interactions. Try
    pointing and clicking on the points.</p>

    <p id="hoverdata">Mouse hovers at
    (<span id="x">0</span>, <span id="y">0</span>). <span id="clickdata"></span></p>

    <p>A tooltip is easy to build with a bit of jQuery code and the
    data returned from the plot.</p>

    <p><input id="enableTooltip" type="checkbox">Enable tooltip</p>

<script type="text/javascript">
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