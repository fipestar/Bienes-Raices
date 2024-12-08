<?php 

    //Validar que sea ID valido
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if(!$id){
        header('Location: /admin');
    }


    //base de datos
    require '../../includes/config/database.php';
    $db = conectarDB();

    //Obtener los datos de la propiedad
    $consulta = "SELECT * FROM propiedades WHERE id = ${id}";
    $resultado = mysqli_query($db, $consulta);
    $propiedad = mysqli_fetch_assoc($resultado);

    // Consultar para obtener los vendedores
    $consulta = "SELECT * FROM vendedores";
    $resultado = mysqli_query($db, $consulta);

    //Arreglo con mensajes de errores
    $errores = [];

        $titulo = $propiedad['titulo'];
        $precio = $propiedad['precio'];
        $descripcion = $propiedad['descripcion'];
        $habitaciones = $propiedad['habitaciones'];
        $wc = $propiedad['wc'];
        $estacionamiento = $propiedad['estacionamiento'];
        $vendedores_Id = $propiedad['vendedores_Id'];
        $imagenPropiedad = $propiedad['imagen'];

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

        if(!$imagen['name'] || $imagen['error']){
            $errores[] = 'La imagen es obligatoria';
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

            /**Subida de archivos */
            //Crear Carpeta
            $carpetaImagenes = '../../imagenes/';

            if(!is_dir($carpetaImagenes)){
                mkdir($carpetaImagenes);
            }

            //Generar un nombre unico
            $nombreImagen = md5( uniqid( rand(), true ) ) .".jpg";

            //Subir la imagen
            move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen  );



            //Insertar en la base de datos
        $query = " INSERT INTO propiedades (titulo, precio, imagen, descripcion, habitaciones, wc, estacionamiento, creado, vendedores_Id ) VALUES ( '$titulo', '$precio', '$nombreImagen', '$descripcion', '$habitaciones', 
        '$wc', '$estacionamiento', '$creado', '$vendedores_Id' )";

        //echo $query;

        $resultado = mysqli_query($db, $query);

        if ($resultado){
            //Redireccionar al usuario

            header('Location: /admin?resultado=1');
        }
        }
       
    }

   require '../../includes/funciones.php';
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

        <form class="formulario" method="POST" action="/admin/propiedades/crear.php" enctype="multipart/form-data">
            <fieldset>
                <legend>Informacion General</legend>

                <label for="titulo">Titulo:</label>
                <input type="text" id="titulo" name="titulo" placeholder="Titulo Propiedad" value="<?php echo $titulo; ?>">

                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo $precio; ?>">

                <label for="imagen">Imagen:</label>
                <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

                <img src="/imagenes/<?php echo $imagenPropiedad; ?>" class="imagen-small">

                <label for="descripcion">Descripcion:</label>
                <textarea id="descripcion" name="descripcion"><?php echo $descripcion; ?></textarea>
            </fieldset>

            <fieldset>
                <legend>Informacion Propiedad</legend>
                
                <label for="habitaciones">Habitaciones:</label>
                <input 
                  type="number" 
                  id="habitaciones" 
                  name="habitaciones" 
                  placeholder="Ej:3" 
                  min="1" 
                  max="9" 
                  value="<?php echo $habitaciones; ?>" >

                <label for="wc">Baños</label>
                <input type="number" id="wc" name="wc" placeholder="Ej:3" min="1" max="9" value="<?php echo $wc; ?>">

                <label for="estacionamiento">Estacionamiento:</label>
                <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej:3" min="1" max="9" value="<?php echo $estacionamiento; ?>">
            </fieldset>

            <fieldset>
                <legend>Vendedor</legend>
                <select name="vendedor">
                    <option value="">-- Seleccione --</option>
                    <?php while($vendedor = mysqli_fetch_assoc($resultado) ) : ?>
                        <option <?php echo $vendedores_Id === $vendedor['id'] ? 'selected' : ''; ?> 
                            value="<?php echo   $vendedor['id']; ?>"> <?php echo $vendedor['nombre'] . " " . $vendedor['apellido']; ?> </option>
                    <?php endwhile; ?>    
                </select>
            </fieldset>

            <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">
        </form>
    </main>

    <?php 
   incluirTemplate('footer'); 
?>