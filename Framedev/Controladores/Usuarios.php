<?php
class Usuarios extends Controlador
{
    public function index()
    {
		    $this->se_requiere_logueo(true,'Usuarios|index');
        $login = $this->loadEloquent('Usuarios');
        $bloqueados = $login->usuarios_bloqueados();
        require URL_VISTA.'usuarios/usuarios.php';
    }
	  public function logueados()
    {
		$this->se_requiere_logueo(true,'Usuarios|logueados');
        require URL_VISTA.'usuarios/logueados.php';
    }
	  public function logueados_get()
    {
		      $this->se_requiere_logueo(true,'Usuarios|logueados');
          $login = $this->loadEloquent('Viewlogins');
		      print json_encode($login->logueados_get($this->help));
    }
	  public function tyc($stat){
		  $model = $this->loadEloquent('Usuarios');
		  print json_encode($model->acceptTyc($stat));
	  }
    public function obtener_usuarios()
    {
  		$this->se_requiere_logueo(true,'Usuarios|obtener_usuarios');
  		$usuarios = $this->loadEloquent('Viewusuarios');
  		print json_encode($usuarios->obtener_usuarios());
    }

  public function desbloquea_usuario($id){
		$this->se_requiere_logueo(true,'Usuarios|desbloquea_usuario');
		$desbloquea_usr = $this->loadEloquent('Usuarios');
		$desbloquea_user = $desbloquea_usr->desbloquea_usuario($id);
		print json_encode($desbloquea_user);
	}

  public function desbloquear_usuarios(){
		$this->se_requiere_logueo(true,'Usuarios|desbloquear_usuarios');
		$desbloquea_usr = $this->loadEloquent('Usuarios');
		$desbloquea_user = $desbloquea_usr->desbloquear_usuarios();
		print json_encode($desbloquea_user);
	}

  public function upload_dropzone($folder,$permisos){
    $this->se_requiere_logueo(true,'Usuarios|'.$permisos);

    $newfldr = str_replace('|', '/', $folder);
    $upload_dir = '../uploads/'.$newfldr.'/';

    if(!is_dir($upload_dir)){
      if(!mkdir($upload_dir, 0777, true)) {
          Controlador::bug('Error al crear la estructura del directorio');
          exit();
      }
    }

    $allowed_ext = array('jpg','jpeg','png','gif','pdf');

    if(strtolower($_SERVER['REQUEST_METHOD']) != 'post'){
      Controlador::bug('Error! Error en el metodo HTTP!'.$_SERVER['REQUEST_METHOD']);
    }

    if(((strpos($_FILES['file']['type'], 'image') !== false) ||
        (strpos($_FILES['file']['type'], 'application/pdf') !== false)) && $_FILES['file']['error'] == 0 ){
      $pic = $_FILES['file'];


      /*
      $allowed_ext = array('jpg','jpeg','png','gif');

      if(!in_array(self::get_extension($pic['name']),$allowed_ext)){
        Controlador::bug('Solo las extensiones '.implode(',',$allowed_ext).' son permitidas!');
      }
      */
      $extension_or = pathinfo($pic['name']);
      $destino_final = $upload_dir.$this->help->token(6).'.'.$extension_or['extension'];
      if (file_exists($destino_final)){
        $destino_final = self::smart_rename($destino_final);
      }
      if(move_uploaded_file($pic['tmp_name'], $destino_final)){
        $elemento = pathinfo($destino_final);
        $extension = $elemento['extension'];
        $nombre = $elemento['filename'];
        $original = $nombre.'.'.$extension;
        $temporal =  $this->help->duplicatePublic($original,$newfldr);
        echo $temporal.'|'.$original;
      }
    }else{
      Controlador::bug('Algunos errores ocurrieron al actualizar el avatar: '.strpos($_FILES['file']['type'], 'image'));
    }
  }
  public function update_avatar($file){
    $this->se_requiere_logueo(true,'Usuarios|upload_avatar');
    $usuarios = $this->loadEloquent('Usuarios');
    $out = $usuarios->set_avatar($file);
    return $out;
  }
	private function exit_status($str){
		$this->se_requiere_logueo(true,'Usuarios|upload_avatar');
		if($str){
			echo json_encode(array('status'=>$str));
			exit;
		}
	}
	private function get_extension($file_name){
		$this->se_requiere_logueo(true,'Usuarios|upload_avatar');
		if($file_name){
			$ext = explode('.', $file_name);
			$ext = array_pop($ext);
			return strtolower($ext);
		}
	}
	private function smart_rename($ruta){
		$this->se_requiere_logueo(true,'Usuarios|upload_avatar');
		if($ruta){
			$elemento = pathinfo($ruta);
			$hash = $this->help->token(3);
			$new_file = $elemento['dirname'].'/'.$elemento['filename'].'_'.$hash.'.'.$elemento['extension'];
			if (file_exists($new_file)){
				$new_file = self::smart_rename($new_file);
			}else{
				return $new_file;
			}
		}
	}

    public function datos_usuario($user_id)
    {
		$this->se_requiere_logueo(true,'Usuarios|datos_usuario');

		$usuario_data = $this->loadEloquent('Usuarios');
		$usuario = $usuario_data->datos_usuario($user_id);

		$ubicacion_data = $this->loadEloquent('Ubicacion');
		$ubicacion = $ubicacion_data->select_ubicaciones($this->help, $usuario['id_ubicacion']);

		$usuarios = $this->loadEloquent('Roles');
		$roles = $usuarios->selectRolesByTipo($this->help, '8,6',$_SESSION['id_rol'],$usuario['id_rol']);
		if(($usuario['cat_status'])==3){$chk_cat_status = "checked";$cat_status = 3;}else{$chk_cat_status = "";$cat_status = $usuario['cat_status'];}
		if(($usuario['cat_pass_chge'])==10){$chk_change_pass = "checked";$change_pass = 10;}else{$chk_change_pass = "";$change_pass = $usuario['cat_pass_chge'];}

		require URL_VISTA.'modales/usuarios/editar_usuario.php';
    }
	public function modal_add_usr(){
		$this->se_requiere_logueo(true,'Usuarios|modal_add_usr');

		$ubicacion_data = $this->loadEloquent('Ubicacion');
		$ubicacion = $ubicacion_data->select_ubicaciones($this->help, '');

		$usuarios = $this->loadEloquent('Roles');
		$roles = $usuarios->selectRolesByTipo($this->help, '8,6',$_SESSION['id_rol']);

		require URL_VISTA.'modales/usuarios/nuevo_usuario.php';
	}
	public function agregar_usuario(){
		$this->se_requiere_logueo(true,'Usuarios|agregar_usuario');
		$usuario_model = $this->loadEloquent('Usuarios');
		$inserta_usuario = $usuario_model->agregar_usuario($_POST);
		print json_encode($inserta_usuario);
	}
	public function resetpassword($token){
		if(!$token){Header("Location: ".URL_APP."login"); exit();}
		$this->se_requiere_logueo(false);
		$modelo = $this->loadEloquent('Usuarios');
		$token_valid = $modelo->verifica_token($token);
		if($token_valid['valid']){
			require URL_VISTA.'login/restore.php';
		}else{
			Header("Location: ".URL_APP."login");
		}
	}
	public function editar_usuario(){
		$this->se_requiere_logueo(true,'Usuarios|editar_usuario');
		$usuario_model = $this->loadEloquent('Usuarios');
		$edita_usuario = $usuario_model->editar_usuario($_POST);
		print json_encode($edita_usuario);
	}

	public function cambiar_password(){
		$_SESSION['pass_chge'] = 11;
		$this->se_requiere_logueo(true,'Usuarios|editar_perfil');
		$usuario_model = $this->loadEloquent('Usuarios');

		if(($_POST['password1'] == $_POST['password2'])&&($_POST['password1'])){
			$chge_pass = $cambiar_password = $usuario_model->cambiar_password($_POST['password1'],$_SESSION['id_usuario']);
		}
		if($chge_pass){
			$set_pass_chge = $usuario_model->pass_chge_stat(11,$_SESSION['id_usuario']);
			if($set_pass_chge){
				$respuesta = array('resp' => true , 'dispositivo' => $_SESSION['dispositivo'] );
			}else{
				$_SESSION['pass_chge'] = 10;
				$respuesta = array('resp' => false , 'dispositivo' => $_SESSION['dispositivo'] );
			}
		}else{
			$_SESSION['pass_chge'] = 10;
			$respuesta = array('resp' => false , 'dispositivo' => $_SESSION['dispositivo'] );
		}
		print json_encode($respuesta);
	}

	public function editar_perfil(){
		$this->se_requiere_logueo(true,'Usuarios|editar_perfil');
		$usuario_model = $this->loadEloquent('Usuarios');
		$edita_perfil = $usuario_model->editar_perfil($_POST);
		print json_encode($edita_perfil);
	}
	public function baja_usuario($id){
		$this->se_requiere_logueo(true,'Usuarios|baja_usuario');
		$baja_usr = $this->loadEloquent('Usuarios');
		$baja_user = $baja_usr->baja_usuario($id);
		print json_encode($baja_user);
	}
  public function perfil()
  {
      $this->se_requiere_logueo(true,'Usuarios|perfil');

      $usuario_data = $this->loadEloquent('Usuarios');
      $roles = $this->loadEloquent('Roles');

      $usuario = $usuario_data->datos_usuario($_SESSION['id_usuario']);
      $perfil  = $usuario_data->perfil_usuario($_SESSION['id_usuario']);

        if($perfil['avatar']){
            $avatar = $this->help->duplicatePublic($perfil['avatar'],'perfiles');
        }

      $rol = $roles->rol();

      $usuario_name = $usuario['nombres'];

      require URL_VISTA.'usuarios/perfil.php';
  }
}
?>
