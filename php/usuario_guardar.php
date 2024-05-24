<?php
    require_once "main.php";

    # Almacenando datos #
    $nombre = cleanStrings($_POST['usuario_nombre']);
    $apellido = cleanStrings($_POST['usuario_apellido']);
    $usuario = cleanStrings($_POST['usuario_usuario']);
    $email = cleanStrings($_POST['usuario_email']);
    $claveUsuario = cleanStrings($_POST['usuario_clave_1']);
    $claveUsuario2 = cleanStrings($_POST['usuario_clave_2']);

    #verificando campos obligatorios #
    if($nombre == "" || $apellido == "" || $usuario == "" || $email == ""
    || $claveUsuario == "" || $claveUsuario2 == "") {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No has llenado todos los campos que son obligatorios
        </div>
    ';
        exit();
    }

    #verificar integridad de datos#
    if(validateData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El nombre no cumple con el formato solicitado
        </div>
    ';
        exit();
    }

    if(validateData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido)){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El apellido no cumple con el formato solicitado
        </div>
    ';
        exit();
    }
    
    if(validateData("[a-zA-Z0-9]{4,20}",$usuario)){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El usuario no cumple con el formato solicitado
        </div>
    ';
        exit();
    }
    if(validateData("[a-zA-Z0-9$@.-]{7,100}",$claveUsuario) || validateData("[a-zA-Z0-9$@.-]{7,100}",$claveUsuario2)){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            Las claves no cumplen con el formato solicitado
        </div>
    ';
        exit();
    }

    #verificar email#
    if($email!=""){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $check_email = connection();
            $check_email = $check_email->query("SELECT emailUsuario FROM usuario WHERE emailUsuario = '$email'");
            if($check_email->rowCount()> 0){
                echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    Este correo ya se encuentra registrado, favor de usar un correo diferente
                </div>
               ';
            exit();
            }
            $check_email=null;
        } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El email no cumple con el formato solicitado
            </div>
           ';
        exit();
        }
    }

    #verificar usuario#
    $check_usuario = connection();
    $check_usuario = $check_usuario->query("SELECT usuarioUsuario FROM usuario WHERE usuarioUsuario = '$usuario'");
    if($check_usuario->rowCount()> 0){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            Este usuario ya se encuentra registrado, favor de usar un correo diferente
        </div>
       ';
    exit();
    }
    $check_usuario=null;

    #verificando claves#
    if($claveUsuario!=$claveUsuario2){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            Las contraseñas ingresadas no coinciden
        </div>
       ';
      exit();
    } else {
        $clave = password_hash($claveUsuario,PASSWORD_BCRYPT,["cost"=>10]);
    }

    #guardando datos#
    $guardar_usuario = connection();
    $guardar_usuario = $guardar_usuario->prepare("INSERT INTO usuario(nombreUsuario, apellidoUsuario, usuarioUsuario,
    claveUsuario, emailUsuario) VALUES(:nombre,:apellido,:usuario,:clave,:email)");

    $marcadores=[
        ":nombre"=>$nombre,
        ":apellido"=>$apellido,
        ":usuario"=>$usuario,
        ":clave"=>$clave,
        ":email"=>$email
    ];

    $guardar_usuario->execute($marcadores);

    if($guardar_usuario->rowCount()==1){
        echo '
        <div class="notification is-info is-light">
            <strong>¡Usuario registrado!</strong><br>
            El usuario se registró con éxito
        </div>
       ';
    } else {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No se pudo registrar el usuario, por favor intente nuevamente
        </div>
       ';
    }
    $guardar_usuario=null;