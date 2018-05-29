<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Database\Capsule\Manager as Capsule;
use LiveControl\EloquentDataTable\DataTable as DT;
use LiveControl\EloquentDataTable\ExpressionWithName;
class LoginElq extends Illuminate\Database\Eloquent\Model {

    protected $table = 'framework.fw_login';
    protected $primaryKey = 'id_login';
    public $timestamps = false;


    static function recuperar_datos($correo,$token){

      $idu = Capsule::table('framework.fw_usuarios')
                ->select('id_usuario','usuario')
                ->where('correo', '=', $correo)
                ->get();
      if(count($idu)>=1){
        self::insert_lost_password($token,$idu[0]->id_usuario,$correo);
        $array[]=array('resp'=>"enviado",'usuario'=>$idu[0]->usuario);
      }else{
        $array[]=array('resp'=>"no_existe");
      }
      return $array;

    }

    static function insert_lost_password($token,$id,$correo){
      Capsule::table('framework.fw_lost_password')->where('correo', '=', $correo)->delete();
      Capsule::table('framework.fw_lost_password')->insert(
          [
              'token' => $token,
              'id_usuario' => $id,
              'correo' => $correo
          ]
      );
    }

    static function updateLogin2(){
        LoginElq::where('id_usuario', '=', $_SESSION['id_usuario'])
                  ->where('open','=',1)
                  ->update(array('ultima_verificacion' => date("Y-m-d H:i:s")));
     }

    public function keepAlive(){
      $resta = time()-$_SESSION['hora_acceso'];
      $array[]=array('resp'=>"intime",'tiempo'=>$resta);
      self::updateLogin2();
      return $array;
    }

    public function verificarSession(){
      if(is_file(session_save_path().'/sess_'.session_id())){
        $contenido=file_get_contents(session_save_path().'/sess_'.session_id());
        //Controlador::bug($contenido);
        if(strpos($contenido, 'id_usuario|')===false){
          $array[]=array('resp'=>"exitnow");
          print json_encode($array);
          exit();
        }
      }
      if(!isset($_SESSION['hora_acceso'])){
        $array[]=array('resp'=>"timeout");
      }else{
        $resta = time()-$_SESSION['hora_acceso'];
        /*1800 = 30 minutos*/
        /*3600 = 1 hr*/
        /*tiempo en segundos*/
        if(isset($_SESSION['hora_acceso']) && ($resta>1800)){
          $array[]=array('resp'=>"timeout",'tiempo'=>$resta);
        }else{
          $array = self::keepAlive();
        }
      }
      print json_encode($array);
    }

    static function inhabilitarUsuario($id_usuario){
      Capsule::table('framework.fw_usuarios')
            ->where('id_usuario', $id_usuario)
            ->update(['cat_status' => 9,'user_mod'=>$id_usuario]);
    }

    static function insertLoggerLogin($id_usuario){
        Capsule::table('framework.fw_login_log')->insert([
            [
              'id_usuario' => $id_usuario,
              'ip' => $_SERVER['REMOTE_ADDR'],
              'fecha' => date("Y-m-d H:i:s"),
              'intentos' => 1
            ]
        ]);
    }

    static function updateLoggerLogin($id_login_log){
      $intentos = Capsule::table('framework.fw_login_log')
            ->select('intentos')
            ->where('id_login_log', $id_login_log)->get();

      Capsule::table('framework.fw_login_log')
            ->where('id_login_log', $id_login_log)
            ->update(['ip' => $_SERVER['REMOTE_ADDR'],'fecha'=>date("Y-m-d H:i:s"),'intentos'=>$intentos[0]->intentos + 1]);
    }

    static function selectLoggerLogin($id_usuario){
      $logerLogin = Capsule::table('framework.fw_login_log')
                ->select('id_login_log','ip','fecha','intentos')
                ->where('id_usuario', '=', $id_usuario)
                ->orderBy('id_login_log', 'desc')
                ->skip(0)
                ->take(1)
                ->get();

      $array = array();
      if(count($logerLogin)>=1){
        foreach ($logerLogin as $num => $row) {
          $array['id_login_log'] 	= $row->id_login_log;
          $array['ip'] 			= $row->ip;
          $array['fecha'] 		= $row->fecha;
          $array['intentos'] 		= $row->intentos;
        }
        return $array;
      }else{
        return array('id_login_log' => NULL);
      }
    }

    static function getIdUsuario($usuario){
      $idu = Capsule::table('framework.fw_usuarios')
                ->select('id_usuario')
                ->where('usuario', '=', $usuario)
                ->get();
      if(count($idu)>=1){
        foreach ($idu as $num => $row) {
          return $row->id_usuario;
        }
      }
    }

    static function putLoggerLogin(HelpersMiddleware $help, $usuario){
      $id_usuario = self::getIdUsuario($usuario);
      $ahora = date("Y-m-d H:i:s");
      $logger = self::selectLoggerLogin($id_usuario);
      if($logger['id_login_log'] !== NULL){
        if($logger['intentos'] <= 4){
          $segundos = $help->diferenciaSegundos($logger['fecha'],$ahora);
          ($segundos <= 600)?self::updateLoggerLogin($logger['id_login_log']):self::insertLoggerLogin($id_usuario);
        }else{
          self::inhabilitarUsuario($id_usuario);
        }
      }else{
        self::insertLoggerLogin($id_usuario);
      }
    }

    static function existeUsuario($usuario){

      $idu = Capsule::table('framework.fw_usuarios')
                ->select('id_usuario')
                ->where('usuario', '=', $usuario)
                ->get();
      if(count($idu)>=1){
        return true;
      }else{
        return false;
      }

    }

    static function storeSession(HelpersMiddleware $help, $id_usuario){
      $store = new LoginElq;
      $store->id_usuario = $id_usuario;
      $store->session_id = session_id();
      $store->open = 1;
      $store->fecha_login = date("Y-m-d H:i:s");
      $store->ipv4 = $_SERVER['REMOTE_ADDR'];
      $store->ipv6 = $help->ipv4to6();
      $store->user_alta = $_SESSION['id_usuario'];
      $store->fecha_alta = date("Y-m-d H:i:s");
      $store->save();
    }

    static function permisos($rol){

      $permisos = Capsule::table('framework.fw_permisos as fwp')
                ->join('framework.fw_metodos as fwm','fwp.id_metodo','=','fwm.id_metodo')
                ->select('fwm.controlador', 'fwm.metodo')
                ->where('fwp.id_rol', '=', $rol)
                ->get();

      $accesos = array();
      if(count($permisos)>=1){
        foreach ($permisos as $num => $row) {
          $accesos[$num] = $row->controlador .'|'. $row->metodo;
        }
        $_SESSION['permisos'] = $accesos;
      }else{
        $_SESSION['permisos'] = '';
      }
    }

    static function MobileDetect(){
      require_once '../vendor/mobiledetect/mobiledetectlib/namespaced/Detection/MobileDetect.php';
      $detect = new Mobile_Detect;
      $array = array();
      if($detect->isMobile()){
        if($detect->isTablet()){
          $_SESSION['dispositivo'] = 'tableta';
        }else{
          $_SESSION['dispositivo'] = 'celular';
        }
      }else{
        $_SESSION['dispositivo'] = 'pc';
      }
    }

    static function getStatusUser($usuario){
      $status = Capsule::table('framework.fw_usuarios')
                ->select('cat_status')
                ->where('usuario', '=', $usuario)
                ->get();
      if(count($status)>=1){
        foreach ($status as $num => $row) {
          return $row->cat_status;
        }
      }
    }

    static function logear(ConfigElq $config, HelpersMiddleware $help){

      $stat = self::getStatusUser($_POST['usuario']);
      if($stat == 9){
        $array[]=array('resp'=>"inhabilitado");
        print json_encode($array);
        exit();
      }

      $password_md5=md5($_POST['password']);

      $logged = Capsule::table('framework.fw_usuarios as fws')
                ->join('framework.fw_usuarios_config AS fwu', 'fwu.id_usuario', '=', 'fws.id_usuario')
                ->where('fws.usuario', '=', $_POST['usuario'])
                ->where('fws.password', '=', $password_md5)
                ->where('fws.cat_status', '=', 3)
                ->get();


      if(count($logged)>=1){

        foreach ($logged as $row) {
          self::session_duplicada($help,$row->id_usuario);

          session_name(SITE_NAME);
          $_SESSION['id_usuario']=$row->id_usuario;
          $_SESSION['id_rol']=$row->id_rol;
          $_SESSION['hora_acceso']= time();
          $_SESSION['usuario']=$row->usuario;
          $_SESSION['id_ubicacion']=$row->id_ubicacion;
          $_SESSION['correo']=$row->correo;
          $_SESSION['tyc']=$row->aceptar_tyc;
          $_SESSION['pass_chge']=$row->cat_pass_chge;
          $_SESSION['token'] = $help->token(62);
          $array[0]=array('resp'=>"acceso_correcto");
        }
          self::MobileDetect();
          $array[1] = array('dispositivo'=>$_SESSION['dispositivo']);

          $acceso = $config->getConfig(1,'login_permitido');

          if($acceso['valor'] == 1){

            self::permisos($_SESSION['id_rol']);
            $array[2] = array('via'=>"correcta");
            self::storeSession($help, $_SESSION['id_usuario']);

          }else{

            session_unset();
            unset($_SESSION);
            session_destroy();
            $array[2] = array('via'=>"disabled");

          }
      }else{
        if(self::existeUsuario($_POST['usuario'])){
            self::putLoggerLogin($help, $_POST['usuario']);
        }
        $array[]=array('resp'=>"acceso_incorrecto");
      }
      print json_encode($array);
    }

    static function salirDirect(HelpersMiddleware $help){

      $id_login = self::getId_login();

      self::closeSession($help,$id_login, $_SESSION['id_usuario']);
      session_unset();
      unset($_SESSION);
      session_destroy();
    }

    static function salir(HelpersMiddleware $help){
      $id_login = self::getId_login();
      self::closeSession($help,$id_login, $_SESSION['id_usuario']);
      session_unset();
      unset($_SESSION);
      if(session_destroy()){
        $array[]=array('resp'=>"correcto");
      }else{
        $array[]=array('resp'=>"incorrecto");
      }
      print json_encode($array);
    }

    static function session_duplicada(HelpersMiddleware $help, $id_usuario){
      $result = LoginElq::where('id_usuario', '=', $id_usuario)
                ->where('open', '=', 1)
                ->select('id_login','session_id','fecha_login')
                ->get();

      if(count($result)>=1){
        foreach ($result as $num => $row) {
          self::closeSession($help, $row->id_login,$id_usuario,$row->fecha_login,$row->session_id);
        }
      }
    }

    static function closeSession(HelpersMiddleware $help, $id_login, $id_usuario, $fecha_login = NULL, $session_id = NULL){

      $fecha_login =($fecha_login === NULL)?self::initLogin($id_login):$fecha_login;
      $fin = date("Y-m-d H:i:s");
      $tiempo = $help->diferenciaFechasD($fecha_login , $fin);

      $close = LoginElq::find($id_login);
      $close->open = '0';
      $close->fecha_logout = $fin;
      $close->tiempo_session = $tiempo;
      $close->user_mod = $id_usuario;
      $close->fecha_mod = $fin;
      if($close->save())
      {
        $session_id =($session_id === NULL)?self::getSession_id($id_login):$session_id;
        if(($session_id != session_id())&&(file_exists(session_save_path().'/sess_'.$session_id))){
          unlink(session_save_path().'/sess_'.$session_id);
        }

        return json_encode(array('resp' => true ));
      }else{
          return json_encode(array('resp' => false ));
      }
    }

    static function getSession_id($id_login){
      $session_id = LoginElq::find($id_login)
                ->select('session_id')
                ->where('id_login', '=', $id_login)
                ->get();

      if(count($session_id)>=1){
        foreach ($session_id as $num => $row) {
          return $row->session_id;
        }
      }
    }

    static function initLogin($id_login){
      $fecha_login = LoginElq::find($id_login)
                ->select('fecha_login')
                ->where('id_login', '=', $id_login)
                ->get();

      if(count($fecha_login)>=1){
        foreach ($fecha_login as $num => $row) {
          return $row->fecha_login;
        }
      }
    }
    static function getId_login($id_usuario = NULL){

      $id_usuario =($id_usuario === NULL)?$_SESSION['id_usuario']:$id_usuario;

      $id_login = LoginElq::where('id_usuario', '=', $id_usuario)
                ->where('open', '=', 1)
                ->select('id_login')
                ->get();

      if(count($id_login)>=1){
        foreach ($id_login as $num => $row) {
          return $row->id_login;
        }
      }
    }

    static function signout($id_usuario, HelpersMiddleware $help){

          $id_login = self::getId_login($id_usuario);
          $fecha_login = self::initLogin($id_login);
          $fin = date("Y-m-d H:i:s");
          $tiempo = $help->diferenciaFechasD($fecha_login , $fin);

          $logout = LoginElq::find($id_login);
          $logout->open = '0';
          $logout->fecha_logout = $fin;
          $logout->tiempo_session = $tiempo;
          $logout->user_mod = $id_usuario;
          $logout->fecha_mod = $fin;
          if($logout->save())
          {
            $session_id = self::getSession_id($id_login);
            if(file_exists(session_save_path().'/sess_'.$session_id)){
              unlink(session_save_path().'/sess_'.$session_id);
            }
            return json_encode(array('resp' => true ));
          }else{
              return json_encode(array('resp' => false ));
          }
    }

    static function whoisLogged(){
       $result = Capsule::table('framework.fw_login AS fwl')
                        ->select('fwl.id_usuario AS id_usuario', 'fwl.session_id AS session_id')
                        ->where('fwl.open', '=', 1)
                        ->join('framework.fw_usuarios AS fwu', 'fwl.id_usuario', '=', 'fwu.id_usuario')
                        ->orderBy('fwl.id_usuario', 'asc')
                        ->get();

       if(count($result)>=1){
         $num=0;
         foreach ($result as $num => $row){
           $array[$num]['id_usuario'] = $row->id_usuario;
           $array[$num]['session_id'] = $row->session_id;
           $num++;
         }
       }
       return $array;
    }

}
