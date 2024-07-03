<?php
$servername='localhost';
$user = 'root';
$password = "";
$database = 'data_visualize';
$mysqli = new mysqli($servername, $user, $password, $database);

// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}
?>