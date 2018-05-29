<?php
require_once( '../vendor/autoload.php' );
use Illuminate\Database\Capsule\Manager as Capsule;
class Controlador {

    public $help = null;
    public $push = null;
    public $core = null;
    function __construct()
    {
        $this->help = self::loadMiddleware('Helpers');
        $this->push = self::loadMiddleware('Pusher');
        $this->core = self::loadEloquent('Core');
        self::capsule();
    }
    static function acceso_usr($levels=0){
      if(isset($_SESSION['token'])){
        if(!in_array($levels,$_SESSION['permisos'])){
          $permiso = false;
        }else{
          $permiso = true;
        }
        return $permiso;
      }else{
        $permiso = false;
      }
    }
    public function capsule(){
      $capsule = new Capsule;
      $capsule->addConnection([
       'driver' =>DB_TYPE,
       'host' => DB_HOST,
       'database' => DB_NAME,
       'username' => DB_USER,
       'password' => DB_PASS,
       'charset' => 'utf8',
       'collation' => 'utf8_unicode_ci',
       'prefix' => '',
      ]);
      $capsule->setAsGlobal();
      $capsule->bootEloquent();
    }
    static function loadEloquent($database)
    {
        require URL_MODELO . $database . '.php';
    		$object = $database.'Elq';
        return new $object();
    }
    public function loadMiddleware($middleware)
    {
        require URL_MIDDLEWARE . $middleware . '.php';
    		$mdw = $middleware . "Middleware" ;
        return new $mdw($this->push, $this->help);
    }
    static function bug($var){
        require_once('../vendor/php-console/php-console/src/PhpConsole/__autoload.php');
        PhpConsole\Connector::getInstance()->getDebugDispatcher()->dispatchDebug($var,'PHP>>');
    }
    public function se_requiere_logueo($requerido,$levels=0){
    if($requerido == true){
        if(!isset($_SESSION['token'])){
          Header("Location: ".URL_APP."login");
          exit();
        }else{
          if(($_SESSION['tyc'] == 'SI')&&($_SESSION['pass_chge'] == 11)){

            $_SESSION['hora_acceso']=time();
            $this->core->updateLogin();
            if(!in_array($levels,$_SESSION['permisos'])){
              require URL_TEMPLATE.'403.php';
              exit();
            }

          }else{
            if($_SESSION['tyc'] != 'SI'){
              require URL_TEMPLATE.'tyc.php';
              exit();
            }else if($_SESSION['pass_chge'] != 11){
              require URL_TEMPLATE.'pass_chge.php';
              exit();
            }
          }
        }
      }elseif($requerido == false){
        if(isset($_SESSION['token'])){
          Header("Location: ".URL_APP."inicio");
        }
      }
    }
}
?>
