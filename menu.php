<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">


<html>

<head>

<link rel="stylesheet" type="text/css" href="stijl.css">
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

</script>

</head>

<body>

	<h2>Menu</h2>

	<p> Ingelogd als: </p>

	<p> Functie: </p>

	<input type="button" onclick="show_confirm()" value="Uitloggen" />


	<!-- <input type="button" value="Mail sturen" onclick="mailto:afs@amc.uva.nl" /> -->

	<p class="email">
		<a href="mailto:afs@amc.uva.nl">Mail sturen</a>
	</p>

</body>
</html>














































</body>
</html>