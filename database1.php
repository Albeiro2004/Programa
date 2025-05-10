<?php

$host = "localhost:3306";
$User = "root";
$pass = "2004";

$db = "almacen";

$conexion = mysqli_connect($host, $User, $pass, $db);

if(!$conexion){
    echo "Conexion fallida";
}