<?php
class Controllers extends Controlador
{
    public function index()
    {
		$this->se_requiere_logueo(true,'Controllers|index');
        require URL_VISTA.'controllers/index.php';
    }
	public function obtener_controllers(){
		$this->se_requiere_logueo(true,'Controllers|obtener_controllers');
		$modelocontroller = $this->loadEloquent('Controllers');
		$lista_controllers = $modelocontroller->obtenerControllers();
		echo json_encode( $lista_controllers );
	}
    public function data_controller($id)
    {
		$this->se_requiere_logueo(true,'Controllers|data_controller');
		$controller = $this->loadEloquent('Controllers');
		$modelo = $controller->data_controller($id);
		require URL_VISTA.'modales/controllers/editar_metodo.php';
    }
	public function editar_metodo(){
		$this->se_requiere_logueo(true,'Controllers|editar_metodo');
		$edita_modelo = $this->loadEloquent('Controllers');
		$modelo_upd = $edita_modelo->editar_metodo($_POST);
		print json_encode($modelo_upd);
	}
	public function modal_add_metodo(){
		$this->se_requiere_logueo(true,'Controllers|modal_add_metodo');
		require URL_VISTA.'modales/controllers/nuevo_metodo.php';
	}
	public function agregar_metodo(){
		$this->se_requiere_logueo(true,'Controllers|agregar_metodo');
		$add_metodo = $this->loadEloquent('Controllers');
		$inserta_metodo = $add_metodo->agregar_metodo($_POST);
		print json_encode($inserta_metodo);
	}
	public function eliminar_par($id){
		$this->se_requiere_logueo(true,'Controllers|eliminar_par');
		$del_metodo = $this->loadEloquent('Controllers');
		$delete_metodo = $del_metodo->eliminar_metodo($id);
		print json_encode($delete_metodo);
	}
}
