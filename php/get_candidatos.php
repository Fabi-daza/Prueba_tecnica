<?php 
include('conexion.php');

try {
    $consulta = "SELECT * FROM candidatos";
    $stmt = $conn->query($consulta);
    $regiones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-type:application/json');
    echo json_encode($regiones);

    exit;
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
}
?>