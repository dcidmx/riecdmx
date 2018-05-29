<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Database\Capsule\Manager as Capsule;
use LiveControl\EloquentDataTable\DataTable as DT;
use LiveControl\EloquentDataTable\ExpressionWithName;
class ControllersElq extends Illuminate\Database\Eloquent\Model {

    protected $table = 'framework.fw_metodos';
    protected $primaryKey = 'id_metodo';
    public $timestamps = false;


    static function eliminar_metodo($id_metodo){
      $sql0 = Capsule::table('framework.fw_permisos')->where('id_metodo', '=', $id_metodo)->delete();
      if($sql0){
        $sql1 = ControllersElq::where('id_metodo','=',$id_metodo)->delete();
      }
      if($sql1){
        $respuesta = array('resp' => true , 'mensaje' => 'Registro eliminado correctamente.' );
      }else{
        $respuesta = array('resp' => false , 'mensaje' => 'Error en el sistema.' , 'error' => 'Error al eliminar registro.' );
      }
      return $respuesta;
    }

    static function agregar_metodo($arreglo){

      foreach ($arreglo as $key => $value) {
        $post[$key] = $value;
      }

      $store = new ControllersElq;
      $store->controlador = $post['controlador'];
      $store->metodo = $post['metodo'];
      $store->nombre = $post['nombre'];
      $store->descripcion = $post['descripcion'];
      $store->user_alta = $_SESSION['id_usuario'];
      $store->fecha_alta = date("Y-m-d H:i:s");
      if($store->save()){
        $respuesta = array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' );
      }else{
        $respuesta = array('resp' => false , 'mensaje' => 'Error en el sistema.' , 'error' => 'Error al insertar registro.' );
      }
      return $respuesta;
    }

    static function editar_metodo($arreglo){
  		foreach ($arreglo as $key => $value) {
  			$post[$key] = $value;
  		}

      $upd_metodo = ControllersElq::find($post['id_metodo']);
      $upd_metodo->controlador = $post['controlador'];
      $upd_metodo->metodo = $post['metodo'];
      $upd_metodo->nombre = $post['nombre'];
      $upd_metodo->descripcion = $post['descripcion'];
      $upd_metodo->user_mod = $_SESSION['id_usuario'];
      if($upd_metodo->save())
      {
  			$respuesta = array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' );
  		}else{
  			$respuesta = array('resp' => false , 'mensaje' => 'Error en el sistema.' , 'error' => 'Error al insertar registro.' );
  		}

  		return $respuesta;
  	}

    static function data_controller($id){
      $metodo = ControllersElq::all()->where('id_metodo','=',$id);
  		if(count($metodo)>=1){
  			foreach ($metodo as $row) {
  				$array[]=array(
  					$row->id_metodo,
  					$row->controlador,
  					$row->metodo,
  					$row->nombre,
  					$row->descripcion
  				);
  			}
  		}
  		return $array;
  	}

    static function obtenerControllers(){
      $dataTable = new DT(
        ControllersElq::where('id_metodo', '>', 0),
        ['id_metodo', 'controlador', 'metodo', 'nombre', 'descripcion']
      );
      return $dataTable->make();
    }

}
