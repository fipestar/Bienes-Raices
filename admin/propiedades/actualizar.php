<?php 

    require '../../includes/app.php';
    use App\Propiedad;

    estaAutenticado();

    //Validar que sea ID valido
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if(!$id){
        header('Location: /admin');
    }


    //Obtener los datos de la propiedad
    $propiedad = Propiedad::find($id);

    // Consultar para obtener los vendedores
    $consulta = "SELECT * FROM vendedores";
    $resultado = mysqli_query($db, $consulta);

    //Arreglo con mensajes de errores
    $errores = [];

        $titulo = $propiedad->titulo;
        $precio = $propiedad->precio;
        $descripcion = $propiedad->descripcion;
        $habitaciones = $propiedad->habitaciones;
        $wc = $propiedad->wc;
        $estacionamiento = $propiedad->estacionamiento;
        $vendedores_Id = $propiedad->vendedores_Id;
        $imagenPropiedad = $propiedad->imagen;

    //Ejecutar el codigo despues de que el usuario envia el formulario
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        // echo"<pre>";
        // var_dump($_POST);
        // echo"</pre>";

        $titulo = mysqli_real_escape_string( $db, $_POST['titulo'] );
        $precio = mysqli_real_escape_string( $db, $_POST['precio'] );
        $descripcion = mysqli_real_escape_string( $db, $_POST['descripcion'] );
        $habitaciones = mysqli_real_escape_string( $db, $_POST['habitaciones'] );
        $wc = mysqli_real_escape_string( $db, $_POST['wc'] );
        $estacionamiento = mysqli_real_escape_string( $db, $_POST['estacionamiento'] );
        $vendedores_Id = mysqli_real_escape_string( $db, $_POST['vendedor'] );
        $creado = date('Y/m/d');

        //Asignar files hacia una variable
        $imagen = $_FILES['imagen'];

        if(!$titulo){
            $errores[] = "Debes añadir un titulo";
        }

        if(!$precio){
            $errores[] = "El precio es obligatorio";
        }

        if( strlen( $descripcion ) < 50){
            $errores[] = "La descripcion es obligatoria y debe tener al menos 50 caracteres";
        }

        if(!$habitaciones){
            $errores[] = "El Numero de habitaciones es obligatorio";
        }

        if(!$wc){
            $errores[] = "El Numero de baños es obligatorio";
        }

        if(!$estacionamiento){
            $errores[] = "El Numero de lugares de estacionamientos es obligatorio";
        }

        if(!$vendedores_Id){
            $errores[] = "Elige un vendedor";
        }

        //Validar por tamaño (1mb maximo)
        $medida = 1000 * 1000;
        if($imagen['size'] > $medida){
            $errores[] = 'La imagen es muy pesada';
        }

        //  echo"<pre>";
        //  var_dump($errores);
        //  echo"</pre>";

        //Revisar que el array de errores este vacio

        if(empty($errores)){

            // //Crear Carpeta
            $carpetaImagenes = '../../imagenes/';

            if(!is_dir($carpetaImagenes)){
                mkdir($carpetaImagenes);
            }

            $nombreImagen = '';

            // /**Subida de archivos */
            if($imagen['name']){
                //Eliminar la imagen previa

                unlink($carpetaImagenes . $propiedad['imagen']);

          // //Generar un nombre unico
             $nombreImagen = md5( uniqid( rand(), true ) ) .".jpg";

             // //Subir la imagen
             move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen  );
            } else {
                $nombreImagen = $propiedad['imagen'];
            }


            
            //Insertar en la base de datos
        $query = "UPDATE propiedades SET titulo = '${titulo}', precio = '${precio}', imagen = '${nombreImagen}', descripcion = '${descripcion}', habitaciones = ${habitaciones}, 
        wc = ${wc}, estacionamiento = ${estacionamiento}, vendedores_Id = ${vendedores_Id} WHERE id = ${id} " ;
        // echo $query;
        

        $resultado = mysqli_query($db, $query);

        if ($resultado){
            //Redireccionar al usuario

            header('Location: /admin?resultado=2');
        }
        }
       
    }

   
   incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Actualizar Propiedad</h1>

        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php foreach($errores as $error): ?>
            <div class="alerta error">
               <?php echo $error; ?>
            </div>           
        <?php endforeach; ?>    

        <form class="formulario" method="POST" enctype="multipart/form-data">

            <?php include '../../includes/templates/formulario_propiedades.php'; ?>

            <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">
        </form>
    </main>

    <?php 
   incluirTemplate('footer'); 
?>