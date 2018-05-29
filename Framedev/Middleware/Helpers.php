<?php
class HelpersMiddleware
{
  function __construct($push,$help) {
      try {
        $this->push = $push;
        $this->help = $help;
      } catch (PDOException $e) {
          exit('No se ha podido establecer la conexión socket con pusher.');
      }
  }

  public function ipv4to6($ip = NULL) {
    $ip =($ip === NULL)?$_SERVER['REMOTE_ADDR']:$ip;
    $ipAddressBlocks = explode('.', $ip);
    if (count($ipAddressBlocks) == 0) {
      return;
    }
    $ipv6       = '';
    $ipv6Pieces = 0;
    foreach ($ipAddressBlocks as $ipAddressBlock) {
      if ($ipv6Pieces%4 == 0 && $ipv6Pieces > 0) {
        $ipv6 .= '::';
      }
      $ipv6Piece = dechex($ipAddressBlock);
      $ipv6 .= (is_numeric($ipv6Piece) && $ipv6Piece < 10 ? '0'.$ipv6Piece : $ipv6Piece);
      $ipv6Pieces = strlen(str_replace('::', '', $ipv6));
    }
    return $ipv6.'::/48';
  }

  public function setOption($arreglo,$id){
    $opciones = "<option value=''>Seleccione...</option>";
    for($i=0;$i<count($arreglo);$i++){
      if($id==""){
          $opciones .=  "<option value='".$arreglo[$i]['value']."'>".ucwords($arreglo[$i]['valor'])."</option>";
      }else{
        if($id==$arreglo[$i]['value']){
          $opciones .=  "<option value='".$arreglo[$i]['value']."' selected>".ucwords($arreglo[$i]['valor'])."</option>";
        }else{
          $opciones .=  "<option value='".$arreglo[$i]['value']."'>".ucwords($arreglo[$i]['valor'])."</option>";
        }
      }
    }
    return $opciones;
  }
  public function sendMail($datamail){
    include_once("../vendor/visualmx/mail/Email.php");
    $correo = new Email();
    $correo->envia_correo($datamail);
  }
  public function tiene_permiso($levels=0){
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
  public function token($long=25){
    $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    mt_srand((double)microtime()*1000000);
    $i=0;
    $pass = '';
    while ($i != $long) {
      $rand=mt_rand() % strlen($chars);
      $tmp=$chars[$rand];
      $pass=$pass . $tmp;
      $chars=str_replace($tmp, "", $chars);
      $i++;
    }
    return strrev($pass);
  }
  public function descargar_archivo($archivo) {
    if (file_exists($archivo)) {
      $filename = basename($archivo);
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename=' . $filename);
      header('Content-Transfer-Encoding: binary');
      header('Expires: 0');
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Pragma: public');
      header('Content-Length: ' . filesize($archivo));
      ob_clean();
      flush();
      readfile($archivo);
    }
  }
  public function diferenciaFechas($init,$end){
    $datetime1 = new DateTime($init);
    $datetime2 = new DateTime($end);
    $dteDiff = $datetime1->diff($datetime2);
    return $dteDiff->format("%H:%I:%S");
  }
  public function diferenciaFechasD($init,$end){
    $datetime1 = new DateTime($init);
    $datetime2 = new DateTime($end);
    $dteDiff = $datetime1->diff($datetime2);
    return $dteDiff->format("0000-%M-%D %H:%I:%S");
  }
  public function diferenciaSegundos($init,$end){
    $segundos = strtotime($end) - strtotime($init);
    return $segundos;
  }
  public function duplicatePublic($imagen,$newfldr){
    $token = self::token();
    $destino = $token.$imagen;

    $tmp = '../public/tmp/';
    $files = scandir($tmp);
    foreach($files as $file){
      if ((is_file($tmp.$file))&&($file != '.gitkeep')) {
        unlink($tmp.$file);
      }
    }

    $cache = '../public/plugs/cache/';
    $filesc = scandir($cache);
    foreach($filesc as $filec){
      if ((is_file($cache.$filec))&&($filec != '.gitkeep')) {
        unlink($cache.$filec);
      }
    }
    copy('../uploads/'.$newfldr.'/'.$imagen, $tmp.$destino);
    return $destino;
  }
   function getYearsOld($date) {
     list($Y,$m,$d) = explode("-",$date);
     return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
   }
   function getBooleanStatus($status){
     return ($status==1)?'Si':'No';
   }
   function toArray($data){
       return json_decode(json_encode($data), true);
   }
   function pad_left($valor,$num_total,$fill){
     return (!empty(trim($valor)))?str_pad($valor, $num_total, $fill , STR_PAD_LEFT):'';
   }
   function pad_right($valor,$num_total,$fill){
     return (!empty(trim($valor)))?str_pad($valor, $num_total, $fill , STR_PAD_RIGHT):'';
   }
}
