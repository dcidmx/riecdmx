<?php
class Roles extends Controlador
{
    public function index()
    {
		    $this->se_requiere_logueo(true,'Roles|index');
        require URL_TEMPLATE.'404.php';
    }
    public function test(){
      $rol_data = $this->loadEloquent('Roles');
      $r = $rol_data->test();
      echo "<pre>";
      var_dump($r);
      echo "</pre>";
    }

  	public function clonar($id_rol,$transfer){
  		$this->se_requiere_logueo(true,'Roles|clonar');
  		$rol_data = $this->loadEloquent('Roles');
  		$result = $rol_data->clonar_permisos($id_rol,$transfer);
  		print $result.'ok';
  	}
  	public function modal_roles(){
  		$this->se_requiere_logueo(true,'Roles|modal_roles');
  		$rol_data = $this->loadEloquent('Roles');
      $catalog = $this->loadEloquent('Catalogo');
  		$roles = $rol_data->queryRoles(null);
  		$tiporol = $catalog->selectCatalog($this->help,'tiporol',null);
  		require URL_VISTA.'modales/roles/gestion_roles.php';
  	}
  	public function agregar_rol(){
  		$this->se_requiere_logueo(true,'Roles|agregar_rol');
  		$roles_model = $this->loadEloquent('Roles');
  		$inserta_rol = $roles_model->agregar_rol($_POST);
  		print json_encode($inserta_rol);
  	}
  	public function permisos($rol){
  		$this->se_requiere_logueo(true,'Roles|permisos');
  		$roles_data = $this->loadEloquent('Roles');
  		$descripcion = $roles_data->get_rol($rol);
          $metodos = $roles_data->getMetodos();
  		$roles = $roles_data->select_roles();
  		$roles_ck = $roles_data->check_roles();

          require URL_VISTA.'permisos/index.php';
  	}
  	public function establecer_permiso($role,$metodo,$estado){
  		$this->se_requiere_logueo(true,'Roles|establecer_permiso');
  		$setear_permiso = $this->loadEloquent('Roles');
  		$doSet = $setear_permiso->setear_permiso($role,$metodo,$estado);
  		print json_encode($doSet);
  	}
  	public function establecer_acceso($id_rol,$access,$estado){
  		$this->se_requiere_logueo(true,'Roles|establecer_permiso');
  		$setear_acceso = $this->loadEloquent('Roles');
  		$doSet = $setear_acceso->setear_acceso($id_rol,$access,$estado);
  		print json_encode($doSet);
  	}
}
?>
