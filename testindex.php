<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script language="javascript" type="text/javascript" src="./lib/flot/jquery.js"></script>
        <script language="javascript" type="text/javascript" src="./lib/flot/jquery.flot.js"></script>
        
        <title></title>
    </head>
    <body>
        <div id="placeholder1" style="width:600px;height:300px"></div>
        <div id="placeholder2" style="width:600px;height:300px"></div>
        <p id="test">niets</p>
        
        <script type="text/javascript">
        $(function () {
            var test1 = [[0,1],[1,2],[2,3],[3,4],[4,5]];
            var test2 = [[0,5],[1,6],[2,7],[3,8],[4,9]];
         
            var plot = $.plot($("#placeholder1"), 
                [{
                        data:
                            test1, 
                        bars:
                            {show: true},
                        points:
                            {show: true},
                        grid: 
                            {clickable:true}
                    }]);
                
            $.plot($("#placeholder2"), [{
                    data:test2, 
                    bars:
                        {show: true},
                    points:
                        {show: true},
                    grid:
                        {clickable:true}
                }]);
            
            /*$("#placeholder1").bind("plotclick", function (event, pos, item) {
                alert("click!");
                if (item) {
                    $("#test").text("bardata geclickt");
                } else 
                    $("#test").text("geen bardata");
            });
            
            $("#placeholder2").bind("plotclick", function (event, pos, item) {
                alert("click!");
                if (item) {
                    $("#test").text("bardata geclickt");
                } else 
                    $("#test").text("geen bardata");
            });*/
            $("#placeholder1").bind("plotclick", function (event, pos, item) {
            alert("You clicked at " + pos.x + ", " + pos.y);
            // axis coordinates for other axes, if present, are in pos.x2, pos.x3, ...
            // if you need global screen coordinates, they are pos.pageX, pos.pageY

            if (item) {
              highlight(item.series, item.datapoint);
              alert("You clicked a point!");
            }
            });
        });
        </script>
    </body>
</html>
