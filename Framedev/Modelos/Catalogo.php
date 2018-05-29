<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Database\Capsule\Manager as Capsule;
use LiveControl\EloquentDataTable\DataTable as DT;
use LiveControl\EloquentDataTable\ExpressionWithName;
class CatalogoElq extends Illuminate\Database\Eloquent\Model {

    protected $table = 'framework.cm_catalogo';
    protected $primaryKey = 'id_cat';
    public $timestamps = false;

    static function editar_catalogo($arreglo){
      foreach ($arreglo as $key => $value) {
        $post[$key] = $value;
      }

      $edit_cat = CatalogoElq::find($post['id_cat']);
      if($post['id_padre'] != ''){
        $edit_cat->id_padre = $post['id_padre'];
      }
      $edit_cat->catalogo = $post['catalogo'];
      $edit_cat->etiqueta = $post['etiqueta'];
      $edit_cat->activo = $post['activo'];
      $edit_cat->orden = $post['orden'];
      $edit_cat->valor = $post['valor'];
      $edit_cat->user_mod = $_SESSION['id_usuario'];
      if($edit_cat->save())
      {
        $respuesta = array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' );
      }else{
        $respuesta = array('resp' => false , 'mensaje' => 'Error en el sistema.' , 'error' => 'Error al insertar registro.' );
      }
      return $respuesta;
    }

    static function data_catalogo($id){
      $metodo = CatalogoElq::all()->where('id_cat','=',$id);
      if(count($metodo)>=1){
        foreach ($metodo as $row) {
          $array=array(
            $row->id_cat,
            $row->id_padre,
            $row->catalogo,
            $row->etiqueta,
            $row->activo,
            $row->orden,
            $row->valor
          );
        }
        return $array;
      }
    }

    static function eliminar_elemento($id_cat){
      $query_resp = CatalogoElq::where('id_cat','=',$id_cat)->delete();
      if($query_resp){
        $respuesta = array('resp' => true , 'mensaje' => 'Registro eliminado correctamente.' );
      }else{
        $respuesta = array('resp' => false , 'mensaje' => 'Error en el sistema.' , 'error' => 'Error al eliminar registro.' );
      }
      return $respuesta;
    }
    static function agregar_elemento($arreglo){
      foreach ($arreglo as $key => $value) {
        $pots[$key] = $value;
      }

      $store = new CatalogoElq;
      if($post['id_padre'] != ''){
        $store->id_padre = $post['id_padre'];
      }
      $store->catalogo = $post['catalogo'];
      $store->etiqueta = $post['etiqueta'];
      $store->activo = $post['activo'];
      $store->orden = $post['orden'];
      $store->valor = $post['valor'];
      $store->user_alta = $_SESSION['id_usuario'];
      $store->fecha_alta = date("Y-m-d H:i:s");
      if($store->save()){
        $respuesta = array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' );
      }else{
        $respuesta = array('resp' => false , 'mensaje' => 'Error en el sistema.' , 'error' => 'Error al insertar registro.' );
      }
      return $respuesta;
    }

    static function selectCatalog(HelpersMiddleware $help, $tipo,$id_cat){
        $array = array();
        $cat = CatalogoElq::where('catalogo','=',$tipo)
                    ->where('activo','=',1)
                    ->orderBy('orden','asc')
                    ->get();
        if(count($cat)>=1){
            $cont = 0;
            foreach ($cat as $row) {
                $array[$cont]['value']=$row->id_cat;
                $array[$cont]['valor']=$row->etiqueta;
                $cont++;
            }
        }
        return $help->setOption($array,$id_cat);
    }

    static function listaCatalogo(){
      $dataTable = new DT(
        CatalogoElq::where('id_cat', '>', 0),
        ['id_cat', 'id_padre', 'catalogo', 'etiqueta', 'activo', 'orden', 'valor']
      );
      return $dataTable->make();
    }

    static function getJsonCatalogo($name_cat,$id_padre,$other=null){
      //$data_catalogo = CatalogoElq::where('catalogo','=',$name_cat)->where('id_padre','=',$id_padre)->get();
        $data_catalogo = CatalogoElq::where('catalogo','=',$name_cat)->where('id_padre','=',$id_padre);

        if($other!=null){$data_catalogo->orWhere('valor','=','cat_lugar_nacimiento');}
        $data_catalogo = $data_catalogo->get();

        if(count($data_catalogo)>=1){
          foreach ($data_catalogo as $row) {
            $array[]=array('id'=>$row->id_cat,'value'=>$row->etiqueta);
          }
          return $array;
        }

        if(count($data_catalogo)>=1){
          foreach ($data_catalogo as $row) {
            $array[]=array('id'=>$row->id_cat,'value'=>$row->etiqueta);
          }
          return $array;
        }
    }

    /*static function getNameByIdCatalogo($id){
      $results = Capsule::select("SELECT etiqueta from framework.cm_catalogo where id_cat = '$id';");
      return $results[0]->etiqueta;
    }*/

    public function getNameByIdCatalogo($tabla,$campo,$whereName,$id){
        $results = Capsule::select("SELECT $campo from $tabla where $whereName = '$id';");
        if(count($results)>0){
            return $results[0]->$campo;
        }
    }
    public function getIdByName($tabla,$campo,$whereName,$name){
        $results = Capsule::select("SELECT $campo from $tabla where $whereName = '$name';");
        if(count($results)>0){
            return $results[0]->$campo;
        }
    }
    public function selectOtherCatalog(HelpersMiddleware $help, $tabla, $id_cat, $campo, $id_set = null){
        $array = array();
        $cat = Capsule::select("SELECT $id_cat,$campo from $tabla;");

        if(count($cat)>=1){
            $cont = 0;
            foreach ($cat as $row) {
                $array[$cont]['value']=$row->$id_cat;
                $array[$cont]['valor']=$row->$campo;
                $cont++;
            }
        }
        return $help->setOption($array,$id_set);
    }

    public function getAllDataCatalogo($name_catalogo){
      $data = Capsule::table('framework.cm_catalogo')
              ->select('*')
              ->where('catalogo',$name_catalogo)
              ->where('activo',1)
              ->get();
        return $data;
    }

}
