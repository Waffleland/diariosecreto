<?php
    session_start();
    $error="";
    if (array_key_exists("Logout",$_GET)){
        //si viene de la página sesión iniciada
        session_unset();
        setcookie("id","",time()-60*60);
        $_COOKIE['id']="";
    }
    else if ((array_key_exists("id",$_SESSION)AND $_SESSION['id']) OR (array_key_exists("id",$_COOKIE) AND $_COOKIE['id'])){
        //si ya tenia la sesión iniciada
        header("Location: sesioniniciada.php");
    }
    if (array_key_exists("submit",$_POST)){
include("connection.php");
        if (!$_POST['email']){
            $error .= "<br>Email requerido.";
        }
        if (!$_POST['password']){
            $error .= "<br>Contraseña requerida.";
        }
        if ($error!=""){
            $error="<p>Hubo algun(os) error(es) en el formulario".$error."</p>";
        }
        else {
            if ($_POST['registro']=='1')
            {
                // Vemos si la direccion de email está ya registrada o no
                $query="SELECT id FROM usuarios WHERE email='".mysqli_real_escape_string($enlace,$_POST['email'])."'LIMIT 1";
                $result = mysqli_query($enlace,$query);
                if (mysqli_num_rows($result)>0){
                    $error="Email ya registrado";
                }
                else {
                    $query="INSERT INTO usuarios (email,password) VALUES ('".mysqli_real_escape_string($enlace,$_POST['email'])."','".mysqli_real_escape_string($enlace,$_POST['password'])."')";
                    if (!mysqli_query($enlace,$query)){
                    $error="<p>No hemos podido completar el registro</p>";
                    }
                
                    else {
                        //actualizar el almacenamiento del password
                        $query="UPDATE usuarios SET password='".md5(md5(mysqli_insert_id($enlace)).$_POST['password'])."'WHERE id=".mysqli_insert_id($enlace)." LIMIT 1";
                        mysqli_query($enlace,$query);
                        $_SESSION['id']=mysqli_insert_id($enlace);
                        if ($_POST['permanecerIniciada']=='1'){
                            setcookie("id",mysqli_insert_id($enlace),time()+60*60*24*365);
                        }
                        header("Location: sesioniniciada.php");
                    }
                }
            }
            else{
                //comprobar el inicio de sesión
                $query="SELECT * FROM usuarios WHERE email='".mysqli_real_escape_string($enlace,$_POST['email'])."'";
                $result=mysqli_query($enlace,$query);
                $fila=mysqli_fetch_array($result);
                if(isset($fila)){
                    $passwordHasheada=md5(md5($fila['id']).$_POST['password']);
                    if($passwordHasheada==$fila['password']){
                        //usuario autenticado
                        $_SESSION['id']=$fila['id'];
                        if($_POST['permanecerIniciada']=='1'){
                            setcookie("id",$fila['id'],time()+60*60*24*365);
                        }
                        header("Location: sesioniniciada.php");
                    }
                    else{
                        $error="El email/contraseña no pudo ser encontrada";
                    }
                }
            }
        }
    }
?>

<?php include("header.php"); ?>
        <div class="container">
                <h1>Diario secreto</h1>
                <p><strong>Guarda tus pensamientos para siempre</strong></p>
        <div id="error">
            <?php echo $error; ?>
        </div>
        <form method="POST" id="formularioRegistro">
            <p>Interesado? Registrate ahora!</p>
            <div class="form-group">
                <input class="form-control" type="email" name="email" placeholder="Tu email">
            </div>
            <div class="form-group">
                <input class="form-control" type="password" name="password" placeholder="Tu contraseña">
            </div>
            <div class="form-check">
                <label class="form-check-label" for="defaultCheck1">
                    <input class="form-check-input" type="checkbox" name="permanecerIniciada" value=1> Permanecer iniciada
                </label>
            </div>
            <div class="form-group">
                <input type="hidden" name="registro" value=1>
                <input class="btn btn-success" type="submit" name="submit" value="Registrate!">
            </div>
            <p><a class="alternarFormularios">Inicia sesión</a></p>
        </form>

        <form method="POST" id="formularioLogin">
        <p>Inicia sesión con tu usuario y contraseña</p>
        <div class="form-group">
            <input class="form-control" type="email" name="email" placeholder="Tu email">
        </div>
        <div class="form-group">
            <input class="form-control" type="password" name="password" placeholder="Tu contraseña">
        </div>
        <div class="form-check">
            <input class="form-check-label" type="checkbox" name="permanecerIniciada" value=1> Permanecer iniciada
        </div>
        <div class="form-group">
            <input type="hidden" name="registro" value=0>
            <input class="btn btn-success"  type="submit" name="submit" value="Inicia sesión">
        </div>
        <p><a class="alternarFormularios">Registrate</a></p>
        </form>
</div>
<?php include("footer.php"); ?>