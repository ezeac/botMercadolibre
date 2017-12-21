<!DOCTYPE html>
<html>
<head>
	<title>BOT Extractor Mercadolibre</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body class="container">

	<hr>
	<form action="bot.php" class="form-group">
		<label for="url">Ingrese la URL de Mercadolibre: </label><br>
		<input class="form-control" style="width:400px; height: 30px" type="text" name="url" required/>
		<input class="btn-success" style="border: 1px solid" type="submit" value="Enviar" name="Enviar">
	</form>

</body>
</html>