<?php
declare(strict_types=1);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = 'localhost';
$dbname = 'bd_cerrajeria';
$username = 'root';
$password = '';

date_default_timezone_set('America/Bogota');

try {
    $conn = new mysqli($host, $username, $password, $dbname);
    $conn->set_charset('utf8mb4');
    $conn->query("SET time_zone = '-05:00'");
} catch (mysqli_sql_exception $exception) {
    http_response_code(500);
    exit('No fue posible establecer la conexion con la base de datos.');
}
