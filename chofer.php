<?php

session_start();
//Conecto la base de datos
$usuario="root";
$clave="";
$bd="programacioni";
$servidor="localhost";
$conexion = new PDO("mysql:host=$servidor;dbname=$bd",$usuario,$clave);



$hora = date('j-G.d');

$token = hash('sha256', $hora);



    switch ($_SERVER['REQUEST_METHOD']) {
        
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
