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

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->

    <link rel="stylesheet" type="text/css" media="screen" href="main.css">
    
</head>
<body>
    
</body>
</html>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="mainadmin.php"><i class="fa fa-home fa-fw"></i>Main</a></li>
                <li><a href="opUsu.php"><i class="fa fa-list-alt fa-fw"></i>Usuarios</a></li>
                <li><a href="opAudi.php"><i class="fa fa-file-o fa-fw"></i>Auditorias</a></li>
                <li><a href="logout.php"><i class="fa fa-bar-chart-o fa-fw"></i>Logout</a></li>
                
            </ul>
        </div>
        <div class="col-md-9 well">
            Escoga una opcion.
        </div>
    </div>
</div>