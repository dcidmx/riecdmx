<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Database\Capsule\Manager as Capsule;
class ConfigElq extends Illuminate\Database\Eloquent\Model {

    protected $table = 'framework.fw_config';
    protected $primaryKey = 'id_config';
    public $timestamps = false;

    static function getConfig($id_site,$config){

          $result = ConfigElq::where('id_site', '=', $id_site)
                ->where('descripcion', '=', $config)
                ->select('valor','tmp_val as temporal','data')
                ->get();

          $array = array();

          if(count($result)>=1){
            foreach ($result as $row) {
              $array['valor']=$row->valor;
              $array['temporal']=$row->temporal;
              $array['datos']=$row->datos;
            }
          }
          return $array;
   }
   static function existConfig($data){

     $result = ConfigElq::where('id_site', '=', $id_site)
           ->where('descripcion', '=', $data['descripcion'])
           ->select('id_config')
           ->get();

     if(count($result)>=1){
       $return = true;
     }else{
       $return = false;
     }
     return $return;
   }
   static function setConfig($data){
     $exist = self::existConfig($data);
     if(!$exist){

       $store = new ConfigElq;
       $store->id_site = $data['id_site'];
       $store->descripcion = $data['descripcion'];
       $store->valor = $data['valor'];
       $store->tmp_val = $data['tmp_val'];
       $store->data = $data['data'];
       $store->user_alta = $_SESSION['id_usuario'];
       $store->fecha_alta = date("Y-m-d H:i:s");
       $store->save();

     }else{
       ConfigElq::where('id_site', '=', $data['id_site'])
                 ->where('descripcion','=',$data['descripcion'])
                 ->update(array(
                      'valor' => $data['valor'],
                      'tmp_val' => $data['tmp_val'],
                      'data' => $data['data'],
                      'user_mod' => $_SESSION['id_usuario'],
                      'fecha_mod' => NOW()
                    ));
     }
   }
}
