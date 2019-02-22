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
<<!DOCTYPE html>
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


if (empty($_POST['opcion']) || !empty($_POST['volver'])){

    ?>
        <form method="POST">                  
            <input type="hidden" name="opcion" value="list">
            <input value="Listar" type="submit">
        </form>
        <form method="POST">
            <input type="hidden" name="opcion" value="add">
            <input value="Agregar" type="submit">
        </form>
        <form method="POST">
            <input type="hidden" name="opcion" value="del">
            <input value="Borra" type="submit">
        </form>
        <form method="POST">
            <input type="hidden" name="opcion" value="edit">
            <input value="Editar" type="submit">
        </form>
    <?php

}else if($_POST["opcion"]=="list"){
                    $sql = "SELECT * from usuario";
                    $ejecucionSQL = $conexion->prepare($sql);
                    $ejecucionSQL->execute();
                    $res = $ejecucionSQL->fetchAll();
                    $count = $ejecucionSQL->rowCount();
                    if ($count > 0) {
                        ?>
                         <table style="width:100%" border="1px solid black">
                        <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Clave</th>
                    
                        </tr>
                        <?php
                        foreach ($res as $rs) {
                            echo "<tr>";
                            echo "<td>".$rs["id"]."</td>";
                            echo "<td>".$rs["usuario"]."</td>";
                            echo "<td>".$rs["clave"]."</td>";
                            echo "</tr>";
                        } 
                        ?>
                        </table>
                        <form method="POST">
                        <input type="hidden" name="volver" value="1">
                        <input value="Volver" type="submit">
                        </form>
                        <?php 
                    }
    }else if ($_POST["opcion"]=="add"){

                    ?>
                    <form method="POST" action="opUsu.php">
                        <p>Usuario: </p>
                        <input type="text" name="usuario">
                        <p>Clave: </p>
                        <input type="password" name="pass">
                        <input type="hidden" name="opcion" value="add">
                        <br><br>
                        <input type="submit">
                    </form>
                    <?php
                    $sql ="";
                    $ejecucionSQL="";

                    ?>
                    </table>
                        <form method="POST">
                        <input type="hidden" name="volver" value="1">
                        <input value="Volver" type="submit">
                        </form>
                     <?php   

                    if(!empty($_POST['usuario'])) {
                        
                        $sql = "INSERT INTO `usuario` (`usuario`, `clave`) VALUES ('" . $_POST['usuario'] . "', '" . hash('sha256', strtoupper($_POST['pass'] . "adminxd")) . "');";
                        $ejecucionSQL = $conexion->prepare($sql);
                        $ejecucionSQL->execute();
                        echo "<h4>Se ha creado el usuario " . $_POST['usuario'] . "</h4>";
                       
                    }
                     

                }else if ($_POST['opcion'] == "del"){?>
                    <form method="POST" action="opUsu.php">
                        <p>Ingrese el ID del usuario a eliminar: </p>
                        <input type="text" name="idborrar">
                        <input type="hidden" name="opcion" value="del">
                        <br><br>
                        <input type="submit">
                    </form>
                <?php
                    $sql ="";
                    $ejecucionSQL="";


                    ?>
                    </table>
                        <form method="POST">
                        <input type="hidden" name="volver" value="1">
                        <input value="Volver" type="submit">
                        </form>
                     <?php 


                if (!empty($_POST['idborrar'])){
                    $sql = "DELETE FROM `programacioni`.`usuario` WHERE `id` = '".$_POST['idborrar']."';";
                    $ejecucionSQL = $conexion->prepare($sql);
                    $ejecucionSQL->execute();
                    echo "<h4>Se ha borrado el usuario con id " . $_POST['idborrar'] . "</h4>";
                    
                    

                }




            }else if($_POST['opcion'] == "edit") {
                if(!empty($_POST['editarUsuarioOK'])) {
                    $sql = "UPDATE `usuario` SET `usuario` = '".$_POST['usuario']."' , `clave` = '".hash('sha256', strtoupper($_POST['pass'] . "adminxd"))."' WHERE `id` = '".$_POST['lista']."';";
                    $ejecucionSQL = $conexion->prepare($sql);
                    $ejecucionSQL->execute();
                    echo "<h4>Se ha editado el usuario " . $_POST['usuario'] . "</h4>";

                }
                $sql = "SELECT * FROM `usuario`";
                $ejecucionSQL = $conexion->prepare($sql);
                $ejecucionSQL->execute();
                $res = $ejecucionSQL->fetchAll();
                ?>
                <form method="POST" action="opUsu.php">
                <select name="lista">
                    <?php
                    foreach ($res as $rs) {
                        ?>
                        <option value="<?php echo $rs["id"]; ?>"><?php echo $rs["id"] . " " . $rs["usuario"]; ?></option>
                        <?php
                    } ?>
                </select>
                <input type="hidden" name="opcion" value="edit">
                <input type="submit" value="editar">
                </form><?php
                foreach ($res as $rs) {
                    if (!empty($_POST['lista'])) {
                        if ($_POST['lista'] == $rs['id']) {
                            echo "<form method='POST' action='opUsu.php'>";
                            echo "<input type='text' name='usuario' placeholder='" . $rs['usuario'] . "'>";
                            echo "<input type='password' name='pass' placeholder='clave'>";
                            echo "<input type='hidden' name='opcion' value='edit'>";
                            echo "<input type='hidden' name='editarUsuarioOK' value='editarUsuarioOK'>";
                            echo "<input type='hidden' name='lista' value='".$_POST['lista']."'>";
                            echo "<input type='submit' value='OK'>";
                            echo "</form>";
                        }
                    }
                }
                ?>
                </table>
                <form method="POST">
                <input type="hidden" name="volver" value="1">
                <input value="Volver" type="submit">
                </form>
             <?php 
            
            }
            ?>
            <li><a href="mainadmin.php"><i class="fa fa-bar-chart-o fa-fw"></i>Menu Principal</a></li>
      
</body>
</html>