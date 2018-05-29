<?php
class Catalogo extends Controlador
{
    public function index()
    {
		$this->se_requiere_logueo(true,'Catalogo|index');
        require URL_VISTA.'catalogo/index.php';
    }
  	public function obtener_catalogo()
    {
  		$this->se_requiere_logueo(true,'Catalogo|index');
  		$catalogo = $this->loadEloquent('Catalogo');
  		$lista_catalogo = $catalogo->listaCatalogo($_POST);
  		echo json_encode( $lista_catalogo );
  	}
  	public function data_catalogo($id)
    {
  		$this->se_requiere_logueo(true,'Catalogo|editar_catalogo');
  		$controller = $this->loadEloquent('Catalogo');
  		$modelo = $controller->data_catalogo($id);
  		if($modelo[4]==1){$chk_activo = "checked";$activo = 1;}else{$chk_activo = "";$activo = $modelo[4];}
  		require URL_VISTA.'modales/catalogo/editar_catalogo.php';
    }
  	public function editar_catalogo(){
  		$this->se_requiere_logueo(true,'Catalogo|editar_catalogo');
  		$edita_modelo = $this->loadEloquent('Catalogo');
  		$modelo_upd = $edita_modelo->editar_catalogo($_POST);
  		print json_encode($modelo_upd);
  	}
  	public function eliminar_elemento($id){
  		$this->se_requiere_logueo(true,'Catalogo|eliminar_elemento');
  		$del_elemento = $this->loadEloquent('Catalogo');
  		$delete_catalogo = $del_elemento->eliminar_elemento($id);
  		print json_encode($delete_catalogo);
  	}
  	public function modal_add_elemento(){
  		$this->se_requiere_logueo(true,'Catalogo|add_elemento');
  		require URL_VISTA.'modales/catalogo/nuevo_elemento.php';
  	}
  	public function agregar_elemento(){
  		$this->se_requiere_logueo(true,'Catalogo|add_elemento');
  		$add_elemento = $this->loadEloquent('Catalogo');
  		$inserta_elemento = $add_elemento->agregar_elemento($_POST);
  		print json_encode($inserta_elemento);
  	}

    public function getCatalogoSecundario($id_padre,$nombre_cat,$other=null){

      $this->se_requiere_logueo(true);

      $objeto_catalogo = $this->loadEloquent('Catalogo');
      $data_catalogo = $objeto_catalogo->getJsonCatalogo($nombre_cat,$id_padre,$other);
      print json_encode($data_catalogo);
    }
}
