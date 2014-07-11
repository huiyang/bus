<?php if (isset($_POST['coords'])): ?>
	<?php
		include_once 'dbConnection.php';
		//$query = 'SET @p0=\''.$_POST['coords']['latitude'].'\'; SET @p1=\''.$_POST['coords']['longitude'].'\'; SET @p2=\'10\'; CALL `geodist`(@p0, @p1, @p2);';
		$query = 'CALL `geodist`('.$_POST['coords']['latitude'].', '.$_POST['coords']['longitude'].', 10)';
		$result = $mysqli->query($query);
		$data = array();
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		echo json_encode($data);
		exit(-1);
	?>
<?php else: ?>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" type="text/css" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/geoPosition.js"></script>
<script src="js/bus.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script>
	
	
	getMyCoordinate(function(data) {
		getNearestBusStop(data);
		
		var imgSrc;
		var markerSetting = 'markers=red|' + data.coords.latitude + ',' + data.coords.longitude;
		if (data !== undefined) {
			imgSrc = 'http://maps.googleapis.com/maps/api/staticmap?center=' + data.coords.latitude + ',' + data.coords.longitude + '&zoom=17&size=400x400&sensor=false&markers=' + markerSetting;
		}
		//$('#map').html('<img style="max-width: 100%;" src="' + imgSrc + '"/>');
	}, function(error) {
		if (error.code == 1) {
			// Deny by user
		} else if (error.code == 2) {
			// Position unavailable
		} else if (error.code == 3) {
			// Timeout
		} else {
		}
	});
</script>
</head>
<body>
	<div>
		<table class="table">
			<tr><td><a href='index.php' style="padding: 10px;">Back</a></td></tr>
		</table>
	</div>	
	<div id="map" style="padding: 10px;"></div>
	<div id="result" style="padding: 10px;"><table class="table"><tr><td>Loading...</td></tr></table></div>
</body>
</html>
<?php endif; ?>
<?php
$lat = 5.3859885;
$long = 100.27690989999999;

/*

DELIMITER $$

CREATE PROCEDURE geodist (IN mylat double, IN mylon double, IN dist int) 
BEGIN
	-- declare @lon1 float; 
	-- declare @lon2 float;
	-- declare @lat1 float; 
	-- declare @lat2 float;
	
	-- get the original lon and lat for the userid 
	-- select longitude, latitude into mylon, mylat from users5 
	-- where id=userid limit 1;
	
	-- calculate lon and lat for the rectangle:
	set @lon1 = mylon-dist/abs(cos(radians(mylat))*69);
	set @lon2 = mylon+dist/abs(cos(radians(mylat))*69);
	set @lat1 = mylat-(dist/69); 
	set @lat2 = mylat+(dist/69);
	
	-- run the query:
	SELECT destination.*,3956 * 2 * ASIN(SQRT( POWER(SIN((mylat -destination.latitude) * pi()/180 / 2), 2) +COS(mylat * pi()/180) * COS(destination.latitude * pi()/180) *POWER(SIN((mylon -destination.longitude) * pi()/180 / 2), 2) )) as distance 
	FROM bus_stop destination
	-- , users origin
	WHERE 
	-- origin.id=userid and 
	destination.longitude between @lon1 and @lon2 and 
	destination.latitude between @lat1 and @lat2 
	having distance < dist 
	ORDER BY Distance 
	limit 10;

END$$
DELIMITER ;


CALL geodist(5.3859885, 100.2769099, 10)

*/