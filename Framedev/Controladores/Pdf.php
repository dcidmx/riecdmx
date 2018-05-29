<?php
class Pdf extends Controlador
{
  public function index()
  {
	     require URL_TEMPLATE.'404_full.php';
  }

  public function constancia($id_persona,$id_episodio){
		$this->se_requiere_logueo(true,'Pdf|constancia');
		$token = $this->help->token(8);
    $persona = $this->loadEloquent('Persona');
    $catalog = $this->loadEloquent('Catalogo');
    $episodio = $this->loadEloquent('Episodios');
    $expediente = $this->loadEloquent('Expediente');
    $model_dictamen = $this->loadEloquent('Dictamen');

    $datos_p = $persona->searchByIdPersona($id_persona);
    $edad_persona = $this->help->getYearsOld($datos_p->fecha_nacimiento);
    $sexo_persona = $catalog->getNameByIdCatalogo('framework.cm_catalogo','etiqueta','id_cat',$datos_p->id_sexo);
    $nacionalidad = $catalog->getNameByIdCatalogo('framework.cm_catalogo','etiqueta','id_cat',$datos_p->id_nacionalidad);

    $datos_episodio = $episodio->getDataByEpisodio($id_episodio);
    $datos_dictamen = $this->help->toArray($model_dictamen->getDataDictamenFinal($id_episodio));

    $num_expediente = $expediente->getExpedientePersona($id_persona);

		require '../reportes/constancia.php';
		echo json_encode(array('resp'=>true,'token'=>$token));
	}
  public function acta_biometricos(){
		$this->se_requiere_logueo(true,'Pdf|acta_biometricos');
		$token = $this->help->token(8);
		require '../reportes/acta_biometricos.php';
		echo json_encode(array('resp'=>true,'token'=>$token));
	}
  public function acta_omision(){
		$this->se_requiere_logueo(true,'Pdf|acta_omision');
		$token = $this->help->token(8);
		require '../reportes/acta_omision.php';
		echo json_encode(array('resp'=>true,'token'=>$token));
	}
  public function escrito_solicitud(){
		$this->se_requiere_logueo(true,'Pdf|escrito_solicitud');
		$token = $this->help->token(8);
		require '../reportes/escrito_solicitud.php';
		echo json_encode(array('resp'=>true,'token'=>$token));
	}
  public function solicitud_ayuda(){
		$this->se_requiere_logueo(true,'Pdf|solicitud_ayuda');
		$token = $this->help->token(8);
		require '../reportes/solicitud_ayuda.php';
		echo json_encode(array('resp'=>true,'token'=>$token));
	}



	public function generar_pdf(){
		$this->se_requiere_logueo(true,'Pdf|generar_pdf');
		$token = $this->help->token(8);
    $user = $this->loadEloquent('Usuarios');
    $usuarios = $user->obtener_usuarios();
		require '../reportes/test.php';
		echo json_encode(array('resp'=>true,'token'=>$token));
	}

  public function valeservicio($id_persona){
		$this->se_requiere_logueo(true,'Pdf|valeservicio');
		$token = $this->help->token(8);
    $this->se_requiere_logueo(true);
    $persona = $this->loadEloquent('Persona');
    $catalog = $this->loadEloquent('Catalogo');
    $episodio = $this->loadEloquent('Episodios');
    $expediente = $this->loadEloquent('Expediente');
    $datos_p = $persona->searchByIdPersona($id_persona);
    $full_name = $datos_p->nombre_paciente.' '.$datos_p->apaterno_paciente.' '.$datos_p->amaterno_paciente;
    $edad_persona = $this->help->getYearsOld($datos_p->fecha_nacimiento);
    $sexo_persona = $catalog->getNameByIdCatalogo('framework.cm_catalogo','etiqueta','id_cat',$datos_p->id_sexo);

    $num_expediente = $expediente->getExpedientePersona($id_persona);
    $datos_episodio = $episodio->getEpisodioActivo($id_persona);
		require '../reportes/vale.php';
		echo json_encode(array('resp'=>true,'token'=>$token));
	}

  public function consentimiento($id_persona){
    $this->se_requiere_logueo(true,'Pdf|consentimiento');
    $token = $this->help->token(8);
    $this->se_requiere_logueo(true);
    $persona = $this->loadEloquent('Persona');
    $catalog = $this->loadEloquent('Catalogo');
    $episodio = $this->loadEloquent('Episodios');
    $expediente = $this->loadEloquent('Expediente');
    $datos_p = $persona->searchByIdPersona($id_persona);
    $full_name = $datos_p->nombre_paciente.' '.$datos_p->apaterno_paciente.' '.$datos_p->amaterno_paciente;
    $edad_persona = $this->help->getYearsOld($datos_p->fecha_nacimiento);
    $sexo_persona = ($datos_p->id_sexo=='1263')?"Hombre":'Mujer';
    $rfc = $datos_p->rfc;
    $num_expediente = $expediente->getExpedientePersona($id_persona);
    $datos_episodio = $episodio->getEpisodioActivo($id_persona);
    require '../reportes/consentimiento.php';
    echo json_encode(array('resp'=>true,'token'=>$token));
  }

  public function declaracion($id_persona){
    $this->se_requiere_logueo(true,'Pdf|declaracion');
    $token = $this->help->token(8);
    $persona = $this->loadEloquent('Persona');
    $catalog = $this->loadEloquent('Catalogo');
    $episodio = $this->loadEloquent('Episodios');
    $expediente = $this->loadEloquent('Expediente');
    $datos_p = $persona->searchByIdPersona($id_persona);
    $full_name = $datos_p->nombre_paciente.' '.$datos_p->apaterno_paciente.' '.$datos_p->amaterno_paciente;
    $edad_persona = $this->help->getYearsOld($datos_p->fecha_nacimiento);
    $sexo_persona = ($datos_p->id_sexo=='1263')?"Hombre":'Mujer';
    $rfc = $datos_p->rfc;
    $num_expediente = $expediente->getExpedientePersona($id_persona);
    $datos_episodio = $episodio->getEpisodioActivo($id_persona);
    require '../reportes/declaracion.php';
    echo json_encode(array('resp'=>true,'token'=>$token));
  }
}
?>
