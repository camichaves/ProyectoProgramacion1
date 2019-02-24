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
            if($token==$data["token"]) {
                $time_pre = microtime(true);
                $sql = "INSERT INTO `chofer` (`apellido`, `nombre`, `documento`, `email`, `vehiculo_id`, `sistema_id`) VALUES ('" . $data["apellido"] . "', '" . $data["nombre"] . "', '" . $data["documento"] . "', '" . $data["email"] . "', '" . $data["vehiculo_id"] . "', '" . $data["sistema_id"] . "');";
                $ejecucionSQL = $conexion->prepare($sql);
                $ejecucionSQL->execute();
                $time_post = microtime(true);
                $time = $time_post - $time_pre;
                $time = $time * pow(10, 3);
                $sql = "INSERT INTO `auditoria` (`fecha_acceso`, `user`, `response_time`, `endpoint`) VALUES ('" . date('Y-m-d H:i:s') . "', '" . $data["usuario"] . "', '" . $time . "', 'agregarChofer');";
                $ejecucionSQL = $conexion->prepare($sql);
                $ejecucionSQL->execute();
            }
            break;
        case "GET":
            if($token==$_GET["token"]) {
                $time_pre = microtime(true);
                if (!empty($_GET['id'])) {
                    $sql = "SELECT chofer.`chofer_id`,chofer.`apellido`,chofer.`nombre`,chofer.`documento`,chofer.`email`,marca,sistema_transporte.`nombresistema` FROM chofer INNER JOIN vehiculo ON chofer.vehiculo_id = vehiculo.vehiculo_id INNER JOIN sistema_transporte ON chofer.sistema_id = sistema_transporte.`sistema_id` WHERE chofer.chofer_id = '" . $_GET['id'] . "';";
                    $ejecucionSQL = $conexion->prepare($sql);
                    $ejecucionSQL->execute();
                    $res = $ejecucionSQL->fetch(PDO::FETCH_ASSOC);
                    echo json_encode($res);
                } else {
                    $sql = "SELECT chofer.`chofer_id`,chofer.`apellido`,chofer.`nombre`,chofer.`documento`,chofer.`email`,marca,sistema_transporte.`nombresistema` FROM chofer INNER JOIN vehiculo ON chofer.vehiculo_id = vehiculo.vehiculo_id INNER JOIN sistema_transporte ON chofer.sistema_id = sistema_transporte.`sistema_id`";
                    $stmt = $conexion->prepare($sql);
                    $stmt->execute();
                    $choferes = array();
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $choferes['Choferes'][] = $row;
                    }
                    echo json_encode($choferes);
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
            $data = json_decode(file_get_contents("php://input"),true);
            if($token==$data["token"]) {
                $time_pre = microtime(true);
                if (isset($data["apellido"])) {
                    $sql = "UPDATE `chofer` SET `apellido` = '" . $data["apellido"] . "' WHERE `chofer_id` = '" . $data["chofer_id"] . "';";
                    $ejecucionSQL = $conexion->prepare($sql);
                    $ejecucionSQL->execute();
                }
                if (isset($data["nombre"])) {
                    $sql = "UPDATE `chofer` SET `nombre` = '" . $data["nombre"] . "' WHERE `chofer_id` = '" . $data["chofer_id"] . "';";
                    $ejecucionSQL = $conexion->prepare($sql);
                    $ejecucionSQL->execute();
                }
                if (isset($data["documento"])) {
                    $sql = "UPDATE `chofer` SET `documento` = '" . $data["documento"] . "' WHERE `chofer_id` = '" . $data["chofer_id"] . "';";
                    $ejecucionSQL = $conexion->prepare($sql);
                    $ejecucionSQL->execute();
                }
                if (isset($data["email"])) {
                    $sql = "UPDATE `chofer` SET `email` = '" . $data["email"] . "' WHERE `chofer_id` = '" . $data["chofer_id"] . "';";
                    $ejecucionSQL = $conexion->prepare($sql);
                    $ejecucionSQL->execute();
                }
                if (isset($data["vehiculo_id"])) {
                    $sql = "UPDATE `chofer` SET `vehiculo_id` = '" . $data["vehiculo_id"] . "' WHERE `chofer_id` = '" . $data["chofer_id"] . "';";
                    $ejecucionSQL = $conexion->prepare($sql);
                    $ejecucionSQL->execute();
                }
                if (isset($data["sistema_id"])) {
                    $sql = "UPDATE `chofer` SET `sistema_id` = '" . $data["sistema_id"] . "' WHERE `chofer_id` = '" . $data["chofer_id"] . "';";
                    $ejecucionSQL = $conexion->prepare($sql);
                    $ejecucionSQL->execute();
                }
                $time_post = microtime(true);
                $time = $time_post - $time_pre;
                $time = $time * pow(10, 3);
                $sql = "INSERT INTO `auditoria` (`fecha_acceso`, `user`, `response_time`, `endpoint`) VALUES ('" . date('Y-m-d H:i:s') . "', '" . $data["usuario"] . "', '" . $time . "', 'editarChofer');";
                $ejecucionSQL = $conexion->prepare($sql);
                $ejecucionSQL->execute();
            }
            break;
        case "DELETE":
            $data = json_decode(file_get_contents("php://input"),true);
            if($token==$data["token"]) {
                $time_pre = microtime(true);
                $sql = "DELETE FROM `chofer` WHERE `chofer_id` = '" . $data["chofer_id"] . "';";
                $ejecucionSQL = $conexion->prepare($sql);
                $ejecucionSQL->execute();
                $time_post = microtime(true);
                $time = $time_post - $time_pre;
                $time = $time*pow(10,3);
                $sql = "INSERT INTO `auditoria` (`fecha_acceso`, `user`, `response_time`, `endpoint`) VALUES ('" . date('Y-m-d H:i:s') . "', '" . $data["usuario"] . "', '" . $time . "', 'borrarChofer');";
                $ejecucionSQL = $conexion->prepare($sql);
                $ejecucionSQL->execute();
            }
            break;
    }