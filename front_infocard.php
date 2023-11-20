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

//Todas las zonas para la combobox
//$zonas = get_zonas();

// Establecer tiempo de inactividad en minutos
$inactividad = 15;

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

require_once 'database.php';

// Asignar la hora actual a $_SESSION['tiempo']
$_SESSION['tiempo'] = time();
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
    <div>
        <div class="row  #e0f2f1 teal lighten-5">
            <div class="col s3 marginbottom #e0f2f1 teal lighten-5">
                <!-- Grey navigation panel -->
                <label class="center-align"><h4>Artículo</h4></label>
                <img class="materialboxed" width="275" src="assets/no_image.jpg">
                <input type="text" name="nombre_articulo" id="nombre_articulo" placeholder="Buscar por ID">
                <label id="descripcion_articulo"><h6><strong>Descripciones - Probar 1, 2, 3, 4...</strong></h6></label>
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
                        <td class="center-align" id="id"></td>
                    </tr>
                    <tr>
                        <td class="center-align"><strong>Marca</strong></td>
                        <td class="center-align" id="marca_id"></td>
                    </tr>
                    <tr>
                        <td class="center-align"><strong>Modelo</strong></td>
                        <td class="center-align" id="modelo_articulo"></td>
                    </tr>

                    <tr>
                        <td class="center-align"><strong>Tipo</strong></td>
                        <td class="center-align" id="tipo_id"></td>
                    </tr>

                    <tr>
                        <td class="center-align"><strong>Año</strong></td>
                        <td class="center-align" id="year_articulo"></td>
                    </tr>

                    <tr>
                        <td class="center-align"><strong>Clasificación</strong></td>
                        <td class="center-align" id="clasificacion_articulo"></td>
                    </tr>

                    <tr>
                        <td class="center-align"><strong>Repuestos Asignados</strong></td>
                        <td class="center-align" id="repuestoAsig_articulo"></td>
                    </tr>

                    <tr>
                        <td class="center-align"><strong>Existencia</strong></td>
                        <td class="center-align" id="existencia_articulo"></td>
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
        <a id="editar" class="waves-effect waves-light btn"><i class="material-icons right">create</i>Editar</a>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#nombre_articulo').change(function(){
        var id = $(this).val();
        $.ajax({
            url: 'get_data.php',
            type: 'post',
            data: {id:id},
            dataType: 'json',
            success:function(response){
                var len = response.length;
                if(len > 0){
                    var id = response[0].id;
                    $('#hidden_id').val(id);
                    var descripcion = response[0].descripcion;
                    var marca = response[0].marca;
                    var modelo = response[0].modelo;
                    var tipo = response[0].tipo;
                    var año = response[0].año;
                    var clasificacion = response[0].clasificacion;
                    var repuestos_asignados = response[0].repuestos_asignados;
                    var existencia = response[0].existencia;

                    document.getElementById("id").innerHTML = id;
                    document.getElementById("descripcion_articulo").innerHTML = descripcion;
                    document.getElementById("marca_id").innerHTML = marca;
                    document.getElementById("modelo_articulo").innerHTML = modelo;
                    document.getElementById("tipo_id").innerHTML = tipo;
                    document.getElementById("year_articulo").innerHTML = año;
                    document.getElementById("clasificacion_articulo").innerHTML = clasificacion;
                    document.getElementById("repuestoAsig_articulo").innerHTML = repuestos_asignados;
                    document.getElementById("existencia_articulo").innerHTML = existencia;

                    // Establecer el href del botón "Editar"
                    document.getElementById('editar').href = "front_edit_infocard.php?id=" + id;

                    // Construir la ruta al archivo de imagen
                    var image_path = "assets/" + id + "/main.jpg";

                    // Verificar si existe el archivo de imagen
                    $.ajax({
                        url: image_path,
                        type:'HEAD',
                        error: function()
                        {
                            // Si no existe, mostrar la imagen por defecto
                            $('.materialboxed').attr('src', 'assets/no_image.jpg');
                        },
                        success: function()
                        {
                            // Si existe, mostrar la imagen
                            $('.materialboxed').attr('src', image_path);
                        }
                    });
                }
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