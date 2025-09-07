<?php
include "../conexion.php";

// Obtener fechas desde GET (si no llegan, usar hoy)
$fechaInicio = $_GET['inicio'] ?? date("Y-m-d");
$fechaFin    = $_GET['fin'] ?? date("Y-m-d");

// Inicializar array
$data = [];

// Consulta visitas por fecha y hora
$sql = "SELECT DATE(fecha) as dia, HOUR(fecha) as hora, COUNT(*) as total
        FROM visitas
        WHERE DATE(fecha) BETWEEN ? AND ?
        GROUP BY dia, hora
        ORDER BY dia, hora";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $fechaInicio, $fechaFin);
$stmt->execute();
$result = $stmt->get_result();

// Reorganizar datos: cada día tiene su array de 24 horas
while ($row = $result->fetch_assoc()) {
    $dia = $row['dia'];
    $hora = (int)$row['hora'];
    $total = (int)$row['total'];

    // Si no existe ese día, crear array de 24 horas en 0
    if (!isset($data[$dia])) {
        $data[$dia] = array_fill(0, 24, 0);
    }

    $data[$dia][$hora] = $total;
}

echo json_encode($data);
?>
