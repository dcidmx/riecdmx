<?php
class Login extends Controlador
{
    public function index()
    {
      $this->se_requiere_logueo(false);
      require_once '../vendor/mobiledetect/mobiledetectlib/namespaced/Detection/MobileDetect.php';
      $detect = new Mobile_Detect;
      if($detect->isMobile()){
        if($detect->isTablet()){
          //include (URL_VISTA.'login/index.php');
          include (URL_VISTA.'login/index.php');
        }else{
          /*Celular*/
          //include (URL_VISTA.'material/login.php');
          include (URL_VISTA.'login/index.php');
        }
      }else{
        include (URL_VISTA.'login/index.php');
      }
    }

    public function modal_all_sign_out(){
      $this->se_requiere_logueo(true,'Login|force_all_sign_out');
      require URL_VISTA.'modales/login/sign-all-out.php';
    }

    public function modal_sign_out($id_usuario){
      $this->se_requiere_logueo(true,'Login|force_sign_out');
      require URL_VISTA.'modales/login/sign-out.php';
    }

  	public function verifica_session()
      {
  		/*se_requiere_logueo no se llama por que este reconstruye la sesion cuando es verdadero, y cuando es falso redirige al estar la sesion iniciada*/
  		$obtener_modelo = $this->loadEloquent('Login');
  		$verificar = $obtener_modelo->verificarSession();
          return $verificar;
      }
  	public function keepAlive()
      {
  		//$this->se_requiere_logueo(true,'Login|salir');
  		$obtener_modelo = $this->loadEloquent('Login');
  		print json_encode($obtener_modelo->keepAlive());
      }
  	public function keepAliveReset()
      {
  		$this->se_requiere_logueo(true,'Login|salir');
  		$obtener_modelo = $this->loadEloquent('Login');
  		print json_encode($obtener_modelo->keepAlive());
      }
    public function logear()
      {
      $this->se_requiere_logueo(false);
      $login = $this->loadEloquent('Login');
      $config = $this->loadEloquent('Config');
      $loguear = $login->logear($config, $this->help);
      $this->push->transmit('doIt','loginusr');
          return $loguear;
      }
  	public function salir()
      {
  		$this->se_requiere_logueo(true,'Login|salir');
  		$obtener_modelo = $this->loadEloquent('Login');
  		$salir = $obtener_modelo->salir($this->help);
      $this->push->transmit('doIt','loginusr');
          return $salir;
      }
  	public function salirAlternativo()
      {
  		$obtener_modelo = $this->loadEloquent('Login');
  		$salir = $obtener_modelo->salir($this->help);
      $this->push->transmit('doIt','loginusr');
          return $salir;
      }
  	public function lockSession()
      {
  		$this->se_requiere_logueo(true,'Login|salir');

  		$usuario_data = $this->loadEloquent('Usuarios');
  		$model = $this->loadEloquent('Login');

  		$usuario = $usuario_data->datos_usuario($_SESSION['id_usuario']);
  		$perfil  = $usuario_data->perfil_usuario($_SESSION['id_usuario']);
  		if($perfil['avatar']){$avatar = $this->help->duplicatePublic($perfil['avatar'],'perfiles');}

  		$usuario_menu_top = $usuario_data->datos_usuario($_SESSION['id_usuario']);
  		$correo = $_SESSION['correo'];
  		$username = $_SESSION['usuario'];
  		$usuario_name = $usuario_menu_top['nombres'];

  		$model->salirDirect($this->help);

  		include (URL_VISTA.'login/lock.php');

      }
	  public function recuperar_datos()
      {
  		$this->se_requiere_logueo(false);
  		$obtener_modelo = $this->loadEloquent('Login');
  		$token = $this->help->token(62);
  		D::bug($_POST['correo']);
  		$recuperar = $obtener_modelo->recuperar_datos($_POST['correo'],$token);

  		$datamail['subject'] = 'Recuperación de cuenta';
  		$datamail['plantilla'] = 'lostpassword';
  		$datamail['destinatarios'] = array(
  										$_POST['correo']
  									);
  		$datamail['body'] = array(
  								'token' => $token,
  								'usuario' => $recuperar[0]['usuario']
  							);

  		$this->help->sendMail($datamail);

          print json_encode($recuperar);
      }

    public function sign_all_out()
      {
        $this->se_requiere_logueo(true,'Login|force_sign_out');

        $login = $this->loadEloquent('Login');
        $whosLogin = $login->whoisLogged();

        foreach ($whosLogin as $logged){
          $login->signout($logged['id_usuario'], $this->help);
        }
      }
    public function sign_out($id_usuario)
      {
        $this->se_requiere_logueo(true,'Login|force_sign_out');
        $login = $this->loadEloquent('Login');
        print $login->signout($id_usuario, $this->help);
      }
    public function loginlogger()
      {
      $this->se_requiere_logueo(true,'Login|loginlogger');
      include (URL_VISTA.'login/logger.php');

      }
    public function loginlogger_get()
      {
      $this->se_requiere_logueo(true,'Login|loginlogger');

      $login = $this->loadEloquent('Viewlog');
      print json_encode($login->logger($this->help));

      }

}
?>
