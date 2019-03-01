<?php

session_start();
//Conecto la base de datos
$usuario="root";
$clave="";
$bd="programacioni";
$servidor="localhost";
$conexion = new PDO("mysql:host=$servidor;dbname=$bd",$usuario,$clave);



$hora = date('j-G');

$token = hash('sha256', $hora);

switch ($_SERVER['REQUEST_METHOD']) {
    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        if($token==$data['token']) {
            $time_pre = microtime(true);
            $sql = "INSERT INTO `sistema_vehiculo` (`vehiculo_id`, `sistema_id`) VALUES ('".$data["vehiculo_id"]."', '".$data["sistema_id"]."');";
            $ejecucionSQL = $conexion->prepare($sql);
            $ejecucionSQL->execute();
            $time_post = microtime(true);
            $time = $time_post - $time_pre;
            $time = $time*pow(10, 3);
            $sql = "INSERT INTO `auditoria` (`fecha_acceso`, `user`, `response_time`, `endpoint`) VALUES ('".date('Y-m-d H:i:s')."', '".$data["usuario"]."', '".$time."', 'agregarSistemaVehiculo.php');";
            $ejecucionSQL = $conexion->prepare($sql);
            $ejecucionSQL->execute();
        }
        break;
   
}
