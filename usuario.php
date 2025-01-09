<?php

//importar la conexion
require 'includes/app.php';
$db = conectarDB();

//Crear un email y password
$email = "correo@correo.com";
$password = "1234";

$passwordHash = password_hash($password, PASSWORD_BCRYPT);

//query para crear al usuario
$query = " INSERT INTO usuarios (email, password) VALUES ( '${email}', '${passwordHash}'); ";

echo $query;

//agregarlo a la base de datos
mysqli_query($db, $query);