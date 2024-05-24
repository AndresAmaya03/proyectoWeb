<?php
    //Conexion a la base de datos

    function connection(){
        $pdo = new PDO('mysql:host=localhost;dbname=inventario','root','');
        return $pdo;
    }

    //verificar datos
    function validateData($filter, $string) {
        if(preg_match("/^".$filter."$/", $string)) {
            return false;
        }else {
            return true;
        }
    }

    //limpiar cadenas de texto
    function cleanStrings($string) {
        $string=trim($string);
        $string=stripslashes($string);
        $string=str_ireplace("<script>","", $string);
        $string=str_ireplace("</script>", "", $string);
		$string=str_ireplace("<script src", "", $string);
		$string=str_ireplace("<script type=", "", $string);
		$string=str_ireplace("SELECT * FROM", "", $string);
		$string=str_ireplace("DELETE FROM", "", $string);
		$string=str_ireplace("INSERT INTO", "", $string);
		$string=str_ireplace("DROP TABLE", "", $string);
		$string=str_ireplace("DROP DATABASE", "", $string);
		$string=str_ireplace("TRUNCATE TABLE", "", $string);
		$string=str_ireplace("SHOW TABLES;", "", $string);
		$string=str_ireplace("SHOW DATABASES;", "", $string);
		$string=str_ireplace("<?php", "", $string);
		$string=str_ireplace("?>", "", $string);
		$string=str_ireplace("--", "", $string);
		$string=str_ireplace("^", "", $string);
		$string=str_ireplace("<", "", $string);
		$string=str_ireplace("[", "", $string);
		$string=str_ireplace("]", "", $string);
		$string=str_ireplace("==", "", $string);
		$string=str_ireplace(";", "", $string);
		$string=str_ireplace("::", "", $string);
		$string=trim($string);
		$string=stripslashes($string);
		return $string;
    }

    //renombrar fotos
    function renamePhotos($nombre) {
        $nombre=str_ireplace(" ", "_", $nombre);
		$nombre=str_ireplace("/", "_", $nombre);
		$nombre=str_ireplace("#", "_", $nombre);
		$nombre=str_ireplace("-", "_", $nombre);
		$nombre=str_ireplace("$", "_", $nombre);
		$nombre=str_ireplace(".", "_", $nombre);
		$nombre=str_ireplace(",", "_", $nombre);
		$nombre=$nombre."_".rand(0,100);
		return $nombre;        
    }

	# Funcion paginador de tablas #
	function paginador_tablas($pagina,$Npaginas,$url,$botones){
		$tabla='<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">';

		if($pagina<=1){
			$tabla.='
			<a class="pagination-previous is-disabled" disabled >Anterior</a>
			<ul class="pagination-list">';
		}else{
			$tabla.='
			<a class="pagination-previous" href="'.$url.($pagina-1).'" >Anterior</a>
			<ul class="pagination-list">
				<li><a class="pagination-link" href="'.$url.'1">1</a></li>
				<li><span class="pagination-ellipsis">&hellip;</span></li>
			';
		}

		$ci=0;
		for($i=$pagina; $i<=$Npaginas; $i++){
			if($ci>=$botones){
				break;
			}
			if($pagina==$i){
				$tabla.='<li><a class="pagination-link is-current" href="'.$url.$i.'">'.$i.'</a></li>';
			}else{
				$tabla.='<li><a class="pagination-link" href="'.$url.$i.'">'.$i.'</a></li>';
			}
			$ci++;
		}

		if($pagina==$Npaginas){
			$tabla.='
			</ul>
			<a class="pagination-next is-disabled" disabled >Siguiente</a>
			';
		}else{
			$tabla.='
				<li><span class="pagination-ellipsis">&hellip;</span></li>
				<li><a class="pagination-link" href="'.$url.$Npaginas.'">'.$Npaginas.'</a></li>
			</ul>
			<a class="pagination-next" href="'.$url.($pagina+1).'" >Siguiente</a>
			';
		}

		$tabla.='</nav>';
		return $tabla;
	}