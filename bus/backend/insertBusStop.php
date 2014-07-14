<?php
include_once 'dbConnection.php';
/*
$json = [
    {}  
];*/

$query = 'INSERT INTO bus_stop (code, bus_stop_code, bus_stop, latitude, longitude) VALUES 
    ("' . $_POST['code'] . '", "' . $_POST['bus_stop_code'] . '", "' . $_POST['bus_stop'] . '", "' . $_POST['latitude'] . '", "' . $_POST['longitude'] . '")';
$mysqli->query($query);

$bus_stop_id = $mysqli->insert_id;
/*
$query = 'INSERT INTO route (route, destination) VALUES 
    ("' . $_POST['route'] . '", "' . $_POST['destination'] . '")';
$mysqli->query($query);

$route_id = $mysqli->insert_id;

$query = 'INSERT INTO bus_stop_route (bus_stop_id, route_id) VALUES 
    ("' . $bus_stop_id . '", "' . $route_id . '")';
$mysqli->query($query);
*/
$mysqli->close();
?>
