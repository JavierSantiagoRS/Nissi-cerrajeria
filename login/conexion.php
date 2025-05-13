<?php
$host = 'localhost';
$dbname = 'ejemplo_bd';
$username = 'root';
$password = '';
$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die('Error de conexión: ' . mysqli_connect_error());
}
?>