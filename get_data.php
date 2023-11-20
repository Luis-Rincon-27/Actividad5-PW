<?php
require_once 'database.php';

if(isset($_POST['id']) && $_POST['id'] != ""){
    $conn = connect();
    $id = $_POST['id'];

    $sql = "SELECT * FROM vehiculo_icarplus WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $id]);

    $data = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }

    echo json_encode($data);
}
?>