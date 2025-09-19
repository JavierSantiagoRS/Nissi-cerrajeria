<?php
$host = 'localhost';
$dbname = 'bd_cerrajeria';
$username = 'root';
$password = '';

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die('Error de conexión: ' . mysqli_connect_error());
}

// ✅ Ajustar zona horaria en PHP
date_default_timezone_set('America/Bogota');

// ✅ Ajustar zona horaria en MySQL
mysqli_query($conn, "SET time_zone = '-05:00'");
?>
