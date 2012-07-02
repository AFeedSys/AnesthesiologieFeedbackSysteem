<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>

<link rel="stylesheet" type="text/css" href="stijl.css">
<script type="text/javascript" src="script.js"></script>

<title>Inloggen</title>

</head>

<body>

<h4>Voer een gebruikersnaam en wachtwoord in om in te loggen</h4>

<form action='j_security_check' method='POST'>
<table>
	<tr>
		<td>Gebruikersnaam</td> 
		<td><input name='j_username'></td>
	</tr>
	
	<tr>
		<td>Wachtwoord</td>
		<td><input TYPE="password" name='j_password'></td>
	</tr>
	
	<tr>
		<td><input type=submit value="Inloggen"></td>
	</tr>

</table>
</form>

</body>
</html>