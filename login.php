<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="login.css">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

</head>
<body>
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
    


if(empty($_POST['pass'])  ) {

             if(!empty($_GET['er'])){
                 echo("<script>alert(\"Datos incorrectos\")</script>"); 
            }
    
    ?>
    
 

<div class="container">

<div class="row" style="margin-top:20px">
    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
		<form role="form" action="login.php" method="POST">
			<fieldset>
				<h2>Please Sign In</h2>
				<hr class="colorgraph">
				<div class="form-group">
                    <input type="text" name="user" id="user" class="form-control input-lg" placeholder="User">
				</div>
				<div class="form-group">
                    <input type="password" name="pass" id="pass" class="form-control input-lg" placeholder="Password">
				</div>
				
				<hr class="colorgraph">
				<div class="row">
					<div class="col-xs-6 col-sm-6 col-md-6">
                        <input type="submit" class="btn btn-lg btn-success btn-block" value="Sign In">
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6">
						<a href="" class="btn btn-lg btn-primary btn-block">Register</a>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>

</div>

<?php


}else if ( !empty($_POST['pass'])){


    $conexionPDO= new PDO('mysql:host=localhost;dbname=programacioni;charset=UTF8','root','');
    $pass = hash('sha256', strtoupper($_POST['pass'] . "adminxd")); // clave
    $sql="select * from usuario where usuario = :nom and clave=:clav";
    $ejecucionSQL=$conexionPDO->prepare($sql);
    $ejecucionSQL->bindValue(':nom',$_POST['user']);
    $ejecucionSQL->bindValue(':clav',$pass);
    $ejecucionSQL->execute();

    

    if ((empty($filaPDO=$ejecucionSQL->fetch(PDO::FETCH_ASSOC)))) {
        echo("<script>alert(\"Datos incorrectos\")</script>"); 
        $_SESSION['habilitado']=0;
        header("location: http://localhost/proyProg1/login.php?er=1");
        die();
    }

    $_SESSION['usuario']=$filaPDO['usuario'];
    $_SESSION['token']=$token;
    //insert en usuario o pass el token
    $sql = "UPDATE `usuario` SET `token` = '".$_SESSION['token']."'  WHERE `usuario` = '".$_SESSION['usuario']."';";
    $ejecucionSQL = $conexion->prepare($sql);
    $ejecucionSQL->execute();
    
                        
                        if($filaPDO['rol']=="admin"){ header("location: http://localhost/proyProg1/mainadmin.php"); $_SESSION['rol']="ad"; }
    else{header("location: http://localhost/proyProg1/mainusu.php"); $_SESSION['rol']="us"; }


}else{
    
    if($_SESSION['rol']=="ad"){header("location: http://localhost/proyProg1/mainadmin.php");}
    if($_SESSION['rol']=="us"){header("location: http://localhost/proyProg1/mainusu.php");}
    //redirigir a donde corresponda

}
?>
</body>
</html>
