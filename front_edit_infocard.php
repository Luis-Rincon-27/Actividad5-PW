<?php
// Comprobar si la sesión ya ha sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    // Iniciar la sesión
    session_start();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    // Redirigir al usuario a la página de inicio de sesión
    header('Location: login.php');
    exit();
}

/*
// Establecer tiempo de inactividad en minutos
$inactividad = 4;

// Comprobar si $_SESSION['tiempo'] está establecido
if(isset($_SESSION['tiempo']) ) {
    // Calcular el tiempo de inactividad
    $vida_session = time() - $_SESSION['tiempo'];
    
    if($vida_session > $inactividad*60) {
        // Si ha pasado más tiempo del establecido en $inactividad, destruir la sesión
        session_destroy();
        
        // Redirigir al usuario a la página de inicio de sesión
        header("Location: login.php");
    }
}

// Asignar la hora actual a $_SESSION['tiempo']
$_SESSION['tiempo'] = time();
*/

//conexion
require_once 'database.php';

// Obtener el id de la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "No se proporcionó ningún ID.";
    exit; // Salir del script si no se proporcionó ningún ID
}

//cargar datos de vehiculo

$conn = connect();
$sql = "SELECT * FROM vehiculo_icarplus WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $id]);

// Almacenar los datos en variables
$vehiculo = $stmt->fetch(PDO::FETCH_ASSOC);
if ($vehiculo) {
    $id = $vehiculo['id'];
    $marca = $vehiculo['marca'];
    $modelo = $vehiculo['modelo'];
    $tipo = $vehiculo['tipo'];
    $descripcion = $vehiculo['descripcion'];
    $año = $vehiculo['año'];
    $clasificacion = $vehiculo['clasificacion'];
    $repuestos_asignados = $vehiculo['repuestos_asignados'];
    $existencia = $vehiculo['existencia'];
} else {
    echo "No se encontró ningún vehículo con el ID especificado.";
}

// combo-box marcas
$sql = "SELECT * FROM marca_vehiculo_icarplus";
$stmt = $conn->prepare($sql);
$stmt->execute();

// Crear un array para almacenar las marcas de vehículos
$marcas = array();

if ($stmt->rowCount() > 0) {
    // Almacenar los datos de cada fila en el array $tipos
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $marcas[] = $row;
    }
} else {
    echo "0 resultados";
}

// combo-box tipos
$sql = "SELECT * FROM tipo_vehiculo_icarplus";
$stmt = $conn->prepare($sql);
$stmt->execute();

// Crear un array para almacenar los tipos de vehículos
$tipos = array();

if ($stmt->rowCount() > 0) {
    // Almacenar los datos de cada fila en el array $tipos
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $tipos[] = $row;
    }
} else {
    echo "0 resultados";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio5
    </title>

         <!--Import Google Icon Font-->
         <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <!--Import materialize.css-->
      <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>

      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      
      <script>
	    $(window).on('beforeunload', function(){
	      $.ajax({
	        type: 'POST',
	        async: false,
	        url: 'logout.php'
	      });
	    });
      </script>

</head>
<nav>
        <div class="nav-wrapper #80cbc4 #004d40 teal darken-4">
            <a href="login.php" class="brand-logo center">iCar Plus</a>
        </div>
</nav>
<br>
<body>

<div class="container" style="border-radius: 15px;">    
    <form id="updateForm" action="scripts/update_data.php" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <div>
        <div class="row  #e0f2f1 teal lighten-5">
            <div class="col s3 marginbottom #e0f2f1 teal lighten-5">
                <!-- Grey navigation panel -->
                <label class="center-align"><h4>Artículo</h4></label>
                <?php
                // Construir la ruta al archivo de imagen
                $image_path = "assets/" . $id . "/main.jpg";

                // Verificar si existe el archivo de imagen
                if (file_exists($image_path)) {
                    // Si existe, mostrar la imagen
                    echo '<img class="materialboxed" width="275" src="' . $image_path . '">';
                } else {
                    // Si no existe, mostrar la imagen por defecto
                    echo '<img class="materialboxed" width="275" src="assets/no_image.jpg">';
                }
                ?>
                <p id="id" name="id"><h5><?php print $id ?></h5></p>
                <input id="descripcion_articulo" type="text" name="descripcion" class="validate" placeholder="<?php print $descripcion; ?>">
            </div>

            <div class="col s9 marginbottom white-text text-darken-2 #e0f7fa cyan lighten-5">
                <!-- Teal page content  -->
                <label class="center-align"><h4>Información</h4></label>

                <table class="striped #9fa8da indigo lighten-3">
                    <thead>
                    <tr>
                        <th class="center-align">Datos</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td class="center-align"><strong>ID</strong></td>
                        <td><?php print $id ?></td>
                    </tr>
                    <tr>
                        <td class="center-align"><strong>Marca</strong></td>
                        <td class="center-align input-field col s12">
                        <select id="marca_id" name="marca" class="browser-default">
                                <?php foreach ($marcas as $marca): ?>
                                    <option value="<?php echo $marca['id']; ?>"><?php echo $marca['nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="center-align"><strong>Modelo</strong></td>
                        <td class="center-align input-field col s12">
                            <input id="modelo_articulo" type="text" name="modelo" class="validate" placeholder="<?php print $modelo; ?>">
                        </td>
                    </tr>

                    <tr>
                        <td class="center-align"><strong>Tipo</strong></td>
                        <td class="center-align input-field col s12">
                            <select id="tipo_id" name="tipo" class="browser-default">
                                <?php foreach ($tipos as $tipo): ?>
                                    <option value="<?php echo $tipo['id']; ?>"><?php echo $tipo['nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td class="center-align"><strong>Año</strong></td>
                        <td class="center-align input-field col s12">
                            <select name="año" id="year_articulo" class="browser-default">
                                <?php for($i=date("Y"); $i>=1900; $i--): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td class="center-align"><strong>Clasificación</strong></td>
                        <td class="center-align input-field col s12">
                            <select id="clasificacion_articulo" name="clasificacion" class="browser-default">
                                <option value="Excelente">Excelente</option>
                                <option value="Usado">Usado</option>
                                <option value="Devuelto">Devuelto</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td class="center-align"><strong>Repuestos Asignados</strong></td>
                        <td class="center-align input-field col s12">
                            <input id="repuestoAsig_articulo" type="text" name="repuestos_asignados" class="validate" placeholder="<?php print $repuestos_asignados; ?>">
                        </td>
                    </tr>

                    <tr>
                        <td class="center-align"><strong>Existencia</strong></td>
                        <td class="center-align input-field col s12">
                            <input id="existencia_articulo" type="number" name="existencia" class="validate" placeholder="<?php print $existencia; ?>">
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="row"></div>
            </div>
        </div>
    </div>

</div>

<div class="container">
    <div class="row right-align">
        <button type="submit" class="waves-effect waves-light btn"><i class="material-icons right">done</i>Aceptar</button>
    </div>
</div>
</form>

<script>
$(document).ready(function(){
    // Agregar un controlador de eventos para el envío del formulario
    $("#updateForm").on("submit", function(event) {
        event.preventDefault(); // Prevenir el envío normal del formulario

        // Obtener los datos del formulario
        var formData = $(this).serialize();

        // Registrar formData
        console.log(formData);

        // Enviar los datos del formulario a través de AJAX
        $.ajax({
            url: "scripts/update_data.php",
            type: "POST",
            data: formData,
            success: function(data) {
                // Aquí puedes manejar la respuesta del servidor
                alert("Datos actualizados exitosamente.");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar los errores
                alert("Error al actualizar los datos: " + textStatus);
            }
        });
    });
});
</script>

<!--JavaScript at end of body for optimized loading-->
<script type="text/javascript" src="js/materialize.min.js"></script>
</body>

<footer class="page-footer #004d40 teal darken-4">
          <div class="container">
            <div class="row">
              <div class="col l6 s12">
                <h5 class="white-text">Footer Content</h5>
                <p class="grey-text text-lighten-4">Luis Rincon Page 4°.</p>
              </div>
              <div class="col l4 offset-l2 s12">
                <h5 class="white-text">Links</h5>
                <ul>
                  <li><a class="grey-text text-lighten-3" href="#!">Link del directorio Principal</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="footer-copyright">
            <div class="container">
            © 2088 Copyright Text
            <a class="grey-text text-lighten-4 right" href="#!">hello~</a>
            </div>
          </div>
        </footer>
</html>