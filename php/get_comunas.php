<?php 
include('conexion.php');

if (isset($_GET['region_id'])) {
    $region_id = $_GET['region_id'];

    try {
        $consulta = "SELECT * FROM comunas WHERE region_id = :region_id";
        $stmt = $conn->prepare($consulta);
        $stmt->bindParam(':region_id', $region_id);
        $stmt->execute();
        $comunas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-type: application/json');
        echo json_encode($comunas);
        exit;
    } catch (PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
    }
} else {
    echo "No se proporcionó un ID de región";
}
?>
