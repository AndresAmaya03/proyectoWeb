<?php

    $usuario = cleanStrings($_POST['login_usuario']);
    $clave = cleanStrings($_POST['login_clave']);

    #verificando campos obligatorios #
    if($usuario == "" || $clave == "" ) {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No has llenado todos los campos que son obligatorios
        </div>
    ';
        exit();
    }

    #verificar integridad de datos#
    if(validateData("[a-zA-Z0-9]{4,20}",$usuario)){
        echo '
         <div class="notification is-danger is-light">
             <strong>¡Ocurrio un error inesperado!</strong><br>
             El usuario no cumple con el formato solicitado
         </div>
        ';
        exit();
     }


    //CHECAR ESTA MADRE
    /*
    if(validateData("[a-zA-Z0-9$@.-]{7-100}",$clave)){
        echo '
         <div class="notification is-danger is-light">
             <strong>¡Ocurrio un error inesperado!</strong><br>
             La clave no cumple con el formato solicitado
         </div>
        ';
        exit();
    }
    */
    


    $check_user = connection();
    $check_user=$check_user->query("SELECT * FROM usuario WHERE
    usuarioUsuario='$usuario'");

    if($check_user->rowCount()==1){
        $check_user=$check_user->fetch();

        if($check_user['usuarioUsuario']==$usuario &&
         password_verify($clave,$check_user['claveUsuario'])){
            $_SESSION['id']= $check_user['idUsuario'];
            $_SESSION['nombre']= $check_user['nombreUsuario'];
            $_SESSION['apellido']= $check_user['apellidoUsuario'];
            $_SESSION['usuario']= $check_user['usuarioUsuario'];

            if(headers_sent()) {
                echo "<script>window.location.href='index.php?vista=home'</script>";
            } else {
                header("Location: index.php?vista=home");
            }
        }else{
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El usuario o clave son incorrectos
            </div>
           ';
        }
    }else {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El usuario o clave son incorrectos
        </div>
       ';
    }
    $check_user=null;


