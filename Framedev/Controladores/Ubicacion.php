<?php
class Ubicacion extends Controlador
{
    public function index()
    {
		$this->se_requiere_logueo(true,'Ubicacion|index');
        require URL_TEMPLATE.'404.php';
    }
	  public function obtener_ubicaciones(){
    		$this->se_requiere_logueo(true,'Ubicacion|obtener_ubicaciones');
    		$ubicacion = $this->loadEloquent('Ubicacion');
    		$data_ubicaciones = $ubicacion->obtener_ubicaciones();
    		print $data_ubicaciones;
	  }
    public function test(){
        $ubicacion = $this->loadEloquent('Ubicacion');
        $ubicacion->queryUbicaciones();
    }
}
?>
