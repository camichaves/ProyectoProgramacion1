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
    case "GET":
        if($token==$_GET['token']) {
            $time_pre = microtime(true);
            if (!empty($_GET['id'])) {
                $sql = "SELECT * FROM sistema_vehiculo WHERE sistemavehiculo_id=".$_GET["id"].";";
                $ejecucionSQL = $conexion->prepare($sql);
                $ejecucionSQL->execute();
                $res = $ejecucionSQL->fetch(PDO::FETCH_ASSOC);
                echo json_encode($res);
            } else {
                $sql = "SELECT * FROM sistema_vehiculo;";
                $ejecucionSQL = $conexion->prepare($sql);
                $ejecucionSQL->execute();
                $sistema_vehiculos = array();
                while($res = $ejecucionSQL->fetch(PDO::FETCH_ASSOC)) {
                    $sistema_vehiculos["Sistema_vehiculo"][] = $res;
                }
                echo json_encode($sistema_vehiculos);
            }
            $time_post = microtime(true);
            $time = $time_post - $time_pre;
            $time = $time * pow(10, 3);
            $sql = "INSERT INTO `auditoria` (`fecha_acceso`, `user`, `response_time`, `endpoint`) VALUES ('" . date('Y-m-d H:i:s') . "', '" . $_GET['usuario'] . "', '" . $time . "', 'listarChofer');";
            $ejecucionSQL = $conexion->prepare($sql);
            $ejecucionSQL->execute();
        }
        break;
    case "PUT":
        $data = json_decode(file_get_contents("php://input"), true);
        if($token==$data['token']) {
            $time_pre = microtime(true);
            
            if(isset($data["vehiculo_id"])) {
                echo "hola";
                $sql = "UPDATE `sistema_vehiculo` SET `vehiculo_id` = '".$data["vehiculo_id"]."' WHERE  `sistemavehiculo_id` = '".$data["sistemavehiculo_id"]."';";
                $ejecucionSQL = $conexion->prepare($sql);
                $ejecucionSQL->execute();
            }
            if(isset($data["sistema_id"])) {
                echo "hola";
                $sql = "UPDATE `sistema_vehiculo` SET `sistema_id` = '".$data["sistema_id"]."' WHERE `sistemavehiculo_id` = '".$data["sistemavehiculo_id"]."';";
                $ejecucionSQL = $conexion->prepare($sql);
                $ejecucionSQL->execute();
            }
            $time_post = microtime(true);
            $time = $time_post - $time_pre;
            $sql = "INSERT INTO `auditoria` (`fecha_acceso`, `user`, `response_time`, `endpoint`) VALUES ('".date('Y-m-d H:i:s')."', '".$data["usuario"]."', '".$time."', 'editarSistemaVehiculo.php');";
            $ejecucionSQL = $conexion->prepare($sql);
            $ejecucionSQL->execute();
        }
        break;
    case "DELETE":
        $data = json_decode(file_get_contents("php://input"), true);
        if($token==$data['token']) {
            $time_pre = microtime(true);
            $sql = "DELETE FROM `sistema_vehiculo` WHERE `sistemavehiculo_id` = '".$data["sistemavehiculo_id"]."';";
            $ejecucionSQL = $conexion->prepare($sql);
            $ejecucionSQL->execute();
            $time_post = microtime(true);
            $time = $time_post - $time_pre;
            $sql = "INSERT INTO `auditoria` (`fecha_acceso`, `user`, `response_time`, `endpoint`) VALUES ('".date('Y-m-d H:i:s')."', '".$data["usuario"]."', '".$time."', 'eliminarSistemaVehiculo.php');";
            $ejecucionSQL = $conexion->prepare($sql);
            $ejecucionSQL->execute();
        }
        break;
}