<?php
include('conexion.php');

$nombre = $_POST['nombre'];
$alias = $_POST['alias'];
$rut = $_POST['rut'];
$email = $_POST['email'];
$region = $_POST['region'];
$comuna = $_POST['comuna'];
$candidato = $_POST['candidato'];
$options = implode(',', $_POST['checkbox']);

$datos = [$nombre, $alias, $rut, $email, $region, $comuna, $candidato, $options];
$rut = str_replace('.', '', $rut);
function validarVacios($datos) {
    foreach ($datos as $dato) {
        if (empty($dato)) {
            return false;
        }
    }
    return true;
}

if (!validarVacios($datos)){
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
    exit;
}
function validarAlias($alias){
    if(strlen($alias) <= 5|| !preg_match('/[A-Za-z]/', $alias) || !preg_match('/\d/', $alias)){
        return false;
    }
    return true;
}

if (!validarAlias($alias)){
    echo json_encode(['success' => false, 'message' => 'Tu Alias debe ser mayor a 5 caracteres además debe contener letras y numeros']);
    exit;
}
function validarRut($rut){
    if(!preg_match("/^[0-9]+-[0-9kK]{1}$/", $rut)){
        return false;
    }

list($numero, $dv) = explode('-', $rut);

$dv = strtoupper($dv);

$suma = 0;
$factor = 2;

for ($i = strlen($numero) - 1; $i >= 0; $i--) {
    $suma += $factor * $numero[$i];
    $factor = ($factor == 7) ? 2 : $factor + 1;
}

$resto = $suma % 11;

$dvCalculado = 11 - $resto;

if($dvCalculado == 11){
    $dvCalculado = '0';
}elseif($dvCalculado == 10){
    $dvCalculado = 'K';
}else{
    $dvCalculado = (string)$dvCalculado;
}

return $dv == $dvCalculado;
}

if (!validarRut($rut)){
    echo json_encode(['success' => false, 'message' => 'RUT inválido.']);
    exit;
}

function validarEmail($email){
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return false;
    }
    return true;
}

if (!validarEmail($email)){
    echo json_encode(['success' => false, 'message' => 'Formato de Email incorrecto']);
    exit;
}
function validarCheckboxes($options){
    $array_opciones = explode(",", $options);
    if (!is_array($array_opciones)) {
        return false;
    }
    if (count($array_opciones) !== 2) {
        return false;
    }
    return true;
}

if (!validarCheckboxes($options)){
    echo json_encode(['success' => false, 'message' => 'Debes seleccionar 2 checkbox']);
    exit;
}

try {

    $consulta = "INSERT INTO votaciones (nombre, alias, rut, email, region, comuna, candidato, options) VALUES (:nombre, :alias, :rut, :email, :region, :comuna, :candidato, :options)";
    $stmt = $conn->prepare($consulta);

    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':alias', $alias);
    $stmt->bindParam(':rut', $rut);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':region', $region);
    $stmt->bindParam(':comuna', $comuna);
    $stmt->bindParam(':candidato', $candidato);
    $stmt->bindParam(':options', $options);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Registro exitoso.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error en el registro.']);
    };
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $e->getMessage()]);
}
