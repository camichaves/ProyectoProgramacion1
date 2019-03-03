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
        $data = json_decode(file_get_contents("php://input"),true);
        if($token==$data['token']) {
            $time_pre = microtime(true);
            $sql = "INSERT INTO `sistema_transporte` (`nombresistema`, `pais_procedencia`) VALUES ('" . $data["nombresistema"] . "', '" . $data["pais_procedencia"] . "');";
            $ejecucionSQL = $conexion->prepare($sql);
            $ejecucionSQL->execute();
            $time_post = microtime(true);
            $time = $time_post - $time_pre;
            $time = $time*pow(10,3);
            $sql = "INSERT INTO `auditoria` (`fecha_acceso`, `user`, `response_time`, `endpoint`) VALUES ('".date('Y-m-d H:i:s')."', '".$data["usuario"]."', '".$time."', 'agregarSistemaTransporte');";
            $ejecucionSQL = $conexion->prepare($sql);
            $ejecucionSQL->execute();
        }
        break;
    case "GET":
        if($token==$_GET['token']) {
            $time_pre = microtime(true);
            if(!empty($_GET['id'])) {
                $sql = "SELECT * FROM sistema_transporte WHERE sistema_id = '".$_GET['id']."'";
                $ejecucionSQL = $conexion->prepare($sql);
                $ejecucionSQL->execute();
                $res = $ejecucionSQL->fetch(PDO::FETCH_ASSOC);
                echo json_encode($res);
            }else {
                $sql = "SELECT * FROM sistema_transporte";
                $stmt = $conexion->prepare($sql);
                $stmt->execute();
                $choferes = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $choferes['SistemasDeTransporte'][] = $row;
                }
                echo json_encode($choferes);
            }
            $time_post = microtime(true);
            $time = $time_post - $time_pre;
            $time = $time*pow(10,3);
            $sql = "INSERT INTO `auditoria` (`fecha_acceso`, `user`, `response_time`, `endpoint`) VALUES ('".date('Y-m-d H:i:s')."', '".$_GET['usuario']."', '".$time."', 'listarSistemaTransporte');";
            $ejecucionSQL = $conexion->prepare($sql);
            $ejecucionSQL->execute();
        }
        break;
    case "PUT":
        $data = json_decode(file_get_contents("php://input"),true);
        if($token==$data['token']) {
            $time_pre = microtime(true);
            if (isset($data["nombresistema"])) {
                $sql = "UPDATE `sistema_transporte` SET `nombresistema` = '" . $data["nombresistema"] . "' WHERE `sistema_id` = '" . $data["sistema_id"] . "';";
                $ejecucionSQL = $conexion->prepare($sql);
                $ejecucionSQL->execute();
            }
            if (isset($data["pais_procedencia"])) {
                $sql = "UPDATE `sistema_transporte` SET `pais_procedencia` = '" . $data["pais_procedencia"] . "' WHERE `sistema_id` = '" . $data["sistema_id"] . "';";
                $ejecucionSQL = $conexion->prepare($sql);
                $ejecucionSQL->execute();
            }
            $time_post = microtime(true);
            $time = $time_post - $time_pre;
            $time = $time*pow(10,3);
            $sql = "INSERT INTO `auditoria` (`fecha_acceso`, `user`, `response_time`, `endpoint`) VALUES ('".date('Y-m-d H:i:s')."', '".$data["usuario"]."', '".$time."', 'editarSistemaTransporte');";
            $ejecucionSQL = $conexion->prepare($sql);
            $ejecucionSQL->execute();
        }
        break;
    case "DELETE":
        $data = json_decode(file_get_contents("php://input"),true);
        if($token==$data['token']) {
            $time_pre = microtime(true);
            $sql = "DELETE FROM `sistema_transporte` WHERE `sistema_id` = '".$data["sistema_id"]."';";
            $ejecucionSQL = $conexion->prepare($sql);
            $ejecucionSQL->execute();
            $time_post = microtime(true);
            $time = $time_post - $time_pre;
            $time = $time*pow(10,3);
            $sql = "INSERT INTO `auditoria` (`fecha_acceso`, `user`, `response_time`, `endpoint`) VALUES ('".date('Y-m-d H:i:s')."', '".$data["usuario"]."', '".$time."', 'borrarSistemaTransporte');";
            $ejecucionSQL = $conexion->prepare($sql);
            $ejecucionSQL->execute();
        }
        break;
}