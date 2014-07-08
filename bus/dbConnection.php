<?php
$mysqli = new mysqli("localhost", "foodcent_chy1988", "y3a9n5g0", "foodcent_bus");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
?>
