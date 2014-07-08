<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" type="text/css" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="insert.js"></script>
<script>
	//loadBusData();
	
	var number = 8;
	// 945 bus stop
	setInterval(function() { loadBusData(number++);  }, 2000);
	
	
</script>
</head>
<body>
	<div>
		<table class="table">
			<tr><td><a href='index.php' style="padding: 10px;">Back</a></td></tr>
		</table>
	</div>	
	<div id="result" style="padding: 10px;"><table class="table"><tr><td>Loading...</td></tr></table></div>
</body>
</html>