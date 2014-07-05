<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" type="text/css" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>
<body>
<?php
// To Jetty
$busstop = array(
	'B0365' => 'Lone Pine',
	'B0607' => 'Bukit Gambir - USM',
	'B0492' => 'USM Sg2 Gate',
	'B0687' => 'Tesco Extra',
	'B0642' => 'Queensbay Mall',
);
// From Jetty,
$busstop2 = array(
	'B0364' => 'Lone Pine',
	'B0291' => 'Han Chiang',
	'B0009' => 'Komtar Terminal',
);?>
<table class="table">
	<tr><th>To Jetty</th></tr>
	<?php foreach ($busstop as $id => $b): ?>
		<tr><td><a href="<?php echo 'bus.php?id='.$id; ?>"><?php echo $b; ?></a></td></tr>
	<?php endforeach; ?>
	<tr><th>From Jetty</th></tr>
	<?php foreach ($busstop2 as $id => $b): ?>
		<tr><td><a href="<?php echo 'bus.php?id='.$id; ?>"><?php echo $b; ?></a></td></tr>
	<?php endforeach; ?>
</table>
</body>
</html>