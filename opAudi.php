<?php
session_start();
//Conecto la base de datos
$usuario="root";
$clave="";
$bd="programacioni";
$servidor="localhost";
$conexion = new PDO("mysql:host=$servidor;dbname=$bd",$usuario,$clave);

$hora = date('j-G');
$session_id = session_id();
$token = hash('sha256', $hora.$session_id);


if( $_SESSION['rol']!="ad" || $_SESSION['token']!=$token ){ 

session_destroy();
header("location: http://localhost/proyProg1/login.php");

die();
}




if (empty($_POST['opcion']) || !empty($_POST['volver'])){
    ?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>


    <form method="POST">                  
    <input type="hidden" name="opcion" value="reg">
    <input value="Ver Registros de Auditoria" type="submit">
</form>
<form method="POST">
    <input type="hidden" name="opcion" value="arch">
    <input value="Descargar archivo de auditorias" type="submit">
</form>
<?php

}else if($_POST["opcion"] == "reg") {
    ?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>

<?php
    $sql = "SELECT * FROM auditoria";
    $ejecucionSQL = $conexion->prepare($sql);
    $ejecucionSQL->execute();
    $res = $ejecucionSQL->fetchAll();
    ?>
    <table style="width:100%" border="1px solid black">
        <tr>
            <th>ID</th>
            <th>Fecha de acceso</th>
            <th>User</th>
            <th>Response Time</th>
            <th>Endpoint</th>
        </tr>
        <?php
        foreach ($res as $rs) {
            echo "<tr>";
            echo "<td>" . $rs["auditoria_id"] . "</td>";
            echo "<td>" . $rs["fecha_acceso"] . "</td>";
            echo "<td>" . $rs["user"] . "</td>";
            echo "<td>" . $rs["response_time"] . "</td>";
            echo "<td>" . $rs["endpoint"] . "</td>";
            echo "</tr>";
        } ?>
    </table>
    <form method="POST">
                        <input type="hidden" name="volver" value="1">
                        <input value="Volver" type="submit">
                        </form>
                        <?php 
   
}else if ($_POST["opcion"] == "arch") {
    
if(empty($_POST["fechaInicio"]) && empty($_POST["fechaFinal"]))
     {
        ?>
        
        <!DOCTYPE html>
        <html>
        <head>
        </head>
        <body>
        
       
        <form method='POST' action="opAudi.php">
            Desde <input type='date' name='fechaInicio'>
            <br>
            Hasta <input type='date' name='fechaFinal'>
            <br>
            <input type="hidden" name="opcion" value="arch">
            <input type="submit" value="enviar">

        </form>
        <form method="POST">
                        <input type="hidden" name="volver" value="1">
                        <input value="Volver" type="submit">
                        </form>
                        
        </body>
        </html>
        <?php
    } else {
        
        $file = fopen("auditoria.txt", "c+");
        $contenido = "";
        $sql = "SELECT * FROM auditoria WHERE fecha_acceso BETWEEN '".$_POST["fechaInicio"]." 00:00:00"."' AND '".$_POST["fechaFinal"]." 23:59:59"."';";
        $ejecucionSQL = $conexion->prepare($sql);
        $ejecucionSQL->execute();
        $res = $ejecucionSQL->fetchAll();
        foreach ($res as $rs) {
            $contenido = $contenido.$rs["auditoria_id"].",".$rs["fecha_acceso"].",".$rs["user"].",".$rs["response_time"].",".$rs["endpoint"]."\r\n";
        }
        file_put_contents("auditoria.txt", $contenido);
        $_POST["opcion"] = "";
        header ("Content-Disposition: attachment; filename=auditoria.txt");
        header ("Content-Type: application/octet-stream");
        readfile("auditoria.txt");
    }
}