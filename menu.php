<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<script type="text/javascript">

	function show_confirm()
	{
		var r=confirm("Weet u zeker dat u wilt uitloggen?");
		if (r==true)
  		{
  			alert("U bent succesvol uitgelogd!");
  		}
	}

	function mail()
	{ 
		action="mailto:afs@amc.uva.nl";	
	}
        
        function blokje(id,flagit)
        {
            if (flagit=="1")
            {
            if (document.layers) document.layers[''+id+''].visibility = "show"
                else if (document.all) document.all[''+id+''].style.visibility = "visible"
                else if (document.getElementById) document.getElementById(''+id+'').style.visibility = "visible"
            }    
            else
                if (flagit=="0")
                {
                if (document.layers) document.layers[''+id+''].visibility = "hide"
                else if (document.all) document.all[''+id+''].style.visibility = "hidden"
                   else if (document.getElementById) document.getElementById(''+id+'').style.visibility = "hidden"
                }
        }
    
        
       

</script>

<h2>Menu</h2>

<p> Ingelogd als: </p>

<p> Functie: </p>

<input type="button" onclick="show_confirm()" value="Uitloggen" />

<br>
<p> <a href="#" onMouseOver="blokje('div1',1)" onMouseOut="blokje('div1',0)">Uitleg</a></p>
<div class="uitlegblokje" id="div1"><b>Klik op de grafieken om specifiekere informatie te zien.</b><br>De bovenste grafiek laat de gemiddelde feedback over alle protocollen zien.<br>De middelste grafiek laat per maand zien hoe er gescoord is.<br>De onderste grafiek laat per protocol zien hoe er gescoord is over het afgelopen jaar.<br></div>
<!-- <input type="button" value="Mail sturen" onclick="mailto:afs@amc.uva.nl" /> -->

<p class="email">
        <a href="mailto:afs@amc.uva.nl">Mail sturen</a>
</p>
