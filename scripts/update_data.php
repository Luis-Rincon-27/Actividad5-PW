<?php
require_once '../database.php';
require_once '../front_edit_infocard.php';

// Obtener los datos del formulario
$id = $_POST['id'];
$marca_id = $_POST['marca_id']; // Actualizado a 'marca_id'
$modelo_articulo = $_POST['modelo_articulo'];
$tipo_id = $_POST['tipo_id']; // Actualizado a 'tipo_id'
$year_articulo = $_POST['year_articulo'];
$clasificacion_articulo = $_POST['clasificacion_articulo'];
$repuestoAsig_articulo = $_POST['repuestoAsig_articulo'];
$existencia_articulo = $_POST['existencia_articulo'];

// Buscar el nombre de la marca correspondiente al ID seleccionado
$sql = "SELECT nombre FROM marca_vehiculo_icarplus WHERE id = :marca_id";
$stmt = $conn->prepare($sql);
$stmt->execute(['marca_id' => $marca_id]);
$marca = $stmt->fetchColumn();

// Buscar el nombre del tipo correspondiente al ID seleccionado
$sql = "SELECT nombre FROM tipo_vehiculo_icarplus WHERE id = :tipo_id";
$stmt = $conn->prepare($sql);
$stmt->execute(['tipo_id' => $tipo_id]);
$tipo = $stmt->fetchColumn();

// Preparar la consulta SQL
$sql = "UPDATE vehiculo_icarplus SET marca = :marca, modelo = :modelo_articulo, tipo = :tipo, año = :year_articulo, clasificacion = :clasificacion_articulo, repuestos_asignados = :repuestoAsig_articulo, existencia = :existencia_articulo WHERE id = :id";

// Preparar la declaración
$stmt = $conn->prepare($sql);

// Ejecutar la declaración
$result = $stmt->execute([
    'id' => $id,
    'marca' => $marca,
    'modelo' => $modelo_articulo,
    'tipo' => $tipo,
    'año' => $year_articulo,
    'clasificacion' => $clasificacion_articulo,
    'repuestos_asignados' => $repuestoAsig_articulo,
    'existencia' => $existencia_articulo
    ]);
    
    // Imprimir el resultado
    var_dump($result);
    
// Redirigir al usuario a la página de información del vehículo
header("Location: front_infocard.php?id=" . $id);

?>


