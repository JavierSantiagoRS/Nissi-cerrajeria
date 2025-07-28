<?php
$host = 'localhost';
$dbname = 'bd_cerrajeria';
$username = 'root';
$password = '';
$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die('Error de conexión: ' . mysqli_connect_error());
}
?>
