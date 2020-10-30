<?php

    session_start();
    $contenidoDiario="";
    if(array_key_exists("id",$_COOKIE) && $_COOKIE['id']){
        $_SESSION['id']=$_COOKIE['id'];
    }
    if(array_key_exists("id",$_SESSION) && $_SESSION['id']){
        include("connection.php");
        $query="SELECT diario from usuarios WHERE id='".mysqli_real_escape_string($enlace, $_SESSION['id'])."' LIMIT 1";
        $result=mysqli_query($enlace,$query);
        $fila=mysqli_fetch_array($result);
        $contenidoDiario=$fila['diario'];

    }
    else{
        header("Location: index.php");
    }
    include("header.php");
?>
<?php include("header.php"); ?>
    <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-fixed-top">
    <a class="navbar-brand" href="#">Diario secreto</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="form-inline my-2 my-lg-0">
        <a href="index.php?Logout=1"><button class="btn btn-outline-success my-2 my-sm-0" type="submit">Cerrar sesión</button></a>
        </div>
    </div>
    </nav>
    <div class="container-fluid" id="contenedorSesionIniciada">
        <textarea id="diario" class="form-control"> <?php echo $contenidoDiario; ?> </textarea>
    </div>
<?php include("footer.php"); ?>