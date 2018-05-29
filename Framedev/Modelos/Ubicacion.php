<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Database\Capsule\Manager as Capsule;
class UbicacionElq extends Illuminate\Database\Eloquent\Model {

    protected $table = 'framework.fw_ubicacion';
    protected $primaryKey = 'id_ubicacion';
    public $timestamps = false;

    static function queryUbicaciones(){

      $ubicaciones = UbicacionElq::all();

      if(count($ubicaciones)>=1){
        return $ubicaciones;
      }else{
        return false;
      }

    }
    static function obtener_ubicaciones(){
      $ubicaciones = self::queryUbicaciones();
      foreach ($ubicaciones as $row) {
        $array[]=array($row->id_ubicacion,$row->descripcion_ubicacion);
      }
      return $array;
    }
    static function select_ubicaciones(HelpersMiddleware $help, $id_ubicacion){
      $ubicaciones = self::queryUbicaciones();
      $cont = 0;
      $array = array();
      if($ubicaciones){
        foreach ($ubicaciones as $row) {
          $array[$cont]['value']=$row->id_ubicacion;
          $array[$cont]['valor']=strtoupper($row->descripcion_ubicacion);
          $cont++;
        }
      }
      return $help->setOption($array,$id_ubicacion);
    }


}
