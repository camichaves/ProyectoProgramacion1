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
        
        if($token ==$data["token"]) {
            
            $time_pre = microtime(true);
            $sql = "INSERT INTO `vehiculo` (`patente`, `anho_patente`, `anho_fabricacion`, `marca`, `modelo`) VALUES ('" . $data["patente"] . "', '" . $data["anho_patente"] . "', '" . $data["anho_fabricacion"] . "', '" . $data["marca"] . "', '" . $data["modelo"] . "');";
            $ejecucionSQL = $conexion->prepare($sql);
            $ejecucionSQL->execute();
            $time_post = microtime(true);
            $time = $time_post - $time_pre;
            $time = $time*pow(10, 3);
            $sql = "INSERT INTO `auditoria` (`fecha_acceso`, `user`, `response_time`, `endpoint`) VALUES ('".date('Y-m-d H:i:s')."', '".$data["usuario"]."', '".$time."', 'agregarVehiculo.php');";
            $ejecucionSQL = $conexion->prepare($sql);
            $ejecucionSQL->execute();
        }
        break;
    case "GET":
        if($token ==$_GET["token"]) {
            $time_pre = microtime(true);
            if (!empty($_GET['id'])) {
                $sql = "SELECT * FROM vehiculo WHERE vehiculo_id =".$_GET["id"].";";
                $ejecucionSQL = $conexion->prepare($sql);
                $ejecucionSQL->execute();
                $res = $ejecucionSQL->fetch(PDO::FETCH_ASSOC);
                echo json_encode($res);
            } else {
                $sql = "SELECT * FROM vehiculo";
                $ejecucionSQL = $conexion->prepare($sql);
                $ejecucionSQL->execute();
                $vehiculos = array();
                while($res = $ejecucionSQL->fetch(PDO::FETCH_ASSOC)) {
                    $vehiculos["vehiculos"][] = $res;
                }
                echo json_encode($vehiculos);
                $time_post = microtime(true);
                $time = $time_post - $time_pre;
                $time = $time * pow(10, 3);
                $sql = "INSERT INTO `auditoria` (`fecha_acceso`, `user`, `response_time`, `endpoint`) VALUES ('" . date('Y-m-d H:i:s') . "', '" . $_GET['usuario'] . "', '" . $time . "', 'listarChofer');";
                $ejecucionSQL = $conexion->prepare($sql);
                $ejecucionSQL->execute();
            }
        }
        break;
    case "PUT":
        $data = json_decode(file_get_contents("php://input"), true);
        if($token ==$data["token"]) {
            $time_pre = microtime(true);
            if (isset($data["patente"])) {
                $sql = "UPDATE `vehiculo` SET `patente` = '" . $data["patente"] . "' WHERE `vehiculo_id` = '" . $data["vehiculo_id"] . "';";
                $ejecucionSQL = $conexion->prepare($sql);
                $ejecucionSQL->execute();
            }
            if (isset($data["anho_patente"])) {
                $sql = "UPDATE `vehiculo` SET `anho_patente` = '" . $data["anho_patente"] . "' WHERE `vehiculo_id` = '" . $data["vehiculo_id"] . "';";
                $ejecucionSQL = $conexion->prepare($sql);
                $ejecucionSQL->execute();
            }
            if (isset($data["anho_fabricacion"])) {
                $sql = "UPDATE `vehiculo` SET `anho_fabricacion` = '" . $data["anho_fabricacion"] . "' WHERE `vehiculo_id` = '" . $data["vehiculo_id"] . "';";
                $ejecucionSQL = $conexion->prepare($sql);
                $ejecucionSQL->execute();
            }
            if (isset($data["marca"])) {
                $sql = "UPDATE `vehiculo` SET `marca` = '" . $data["marca"] . "' WHERE `vehiculo_id` = '" . $data["vehiculo_id"] . "';";
                $ejecucionSQL = $conexion->prepare($sql);
                $ejecucionSQL->execute();
            }
            if (isset($data["modelo"])) {
                $sql = "UPDATE `vehiculo` SET `modelo` = '" . $data["modelo"] . "' WHERE `vehiculo_id` = '" . $data["vehiculo_id"] . "';";
                $ejecucionSQL = $conexion->prepare($sql);
                $ejecucionSQL->execute();
            }
            $time_post = microtime(true);
            $time = $time_post - $time_pre;
            $sql = "INSERT INTO `auditoria` (`fecha_acceso`, `user`, `response_time`, `endpoint`) VALUES ('".date('Y-m-d H:i:s')."', '".$data["usuario"]."', '".$time."', 'modificarVehiculo.php');";
            $ejecucionSQL = $conexion->prepare($sql);
            $ejecucionSQL->execute();
        }
        break;
    case "DELETE":
        $data = json_decode(file_get_contents("php://input"), true);
        if($token ==$data["token"]) {
            echo "hola";
            $time_pre = microtime(true);
            echo "vehiculo id:".$data['vehiculo_id'];
            $sql = "DELETE FROM vehiculo WHERE vehiculo_id = ".$data["vehiculo_id"].";";
            $ejecucionSQL = $conexion->prepare($sql);
            $ejecucionSQL->execute();
            $time_post = microtime(true);
            $time = $time_post - $time_pre;
            $sql = "INSERT INTO `auditoria` (`fecha_acceso`, `user`, `response_time`, `endpoint`) VALUES ('".date('Y-m-d H:i:s')."', '".$data["token"]."', '".$time."', 'eliminarVehiculo.php');";
            $ejecucionSQL = $conexion->prepare($sql);
            $ejecucionSQL->execute();
        }
        break;
}