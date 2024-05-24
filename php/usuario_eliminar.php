<?php
    $user_id_del = cleanStrings($_GET['user_id_del']);

    //verificar usuario
    $check_usuario = connection();
    $check_usuario =  $check_usuario->query("SELECT idUsuario FROM usuario
    WHERE idUsuario = '$user_id_del'");

    if($check_usuario->rowCount() == 1) {
        $eliminar_usuario = connection();
        $eliminar_usuario =  $eliminar_usuario->prepare("DELETE FROM usuario
        WHERE idUsuario=:id");

        $eliminar_usuario->execute([":id"=>$user_id_del]);

        if($eliminar_usuario->rowCount()==1) {
            echo '
                <div class="notification is-info is-light">
                    <strong>Usuario eliminado</strong><br>
                    El usuario se ha eliminado exitosamente
                </div>
            ';
        } else {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    No se pudo eliminar el usuario, por favor intente nuevamente
                </div>
            ';
        }
        $eliminar_usuario = null;
    }else {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El usuario que intenta eliminar no existe
        </div>
    ';
    }

    $check_usuario = null;