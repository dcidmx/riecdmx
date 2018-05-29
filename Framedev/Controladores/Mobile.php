<?php
class Mobile extends Controlador
{
        /*
        Actualmente no se usa el redireccionador para movil por lo que redirige al sitio para desktop
        para usar la inferfaz de movil consultar documentacion para centralcar
        */
        public function index()
        {
            $this->se_requiere_logueo(true,'Mobile|index');
            $avatar_usr_circ = '';
            $usuario_name = array();

            if(isset($_SESSION['id_rol'])){
                $id_rol = $_SESSION['id_rol'];
                $id_usuario = $_SESSION['id_usuario'];

                $roles = $this->loadEloquent('Roles');
                $rol = $roles->rol();

                $usuario_data = $this->loadEloquent('Usuarios');
                $usuario_menu_top = $usuario_data->datos_usuario($id_usuario);
                $perfil_menu_top  = $usuario_data->perfil_usuario($id_usuario);

                $avatar_usr_circ = (empty ($perfil_menu_top['avatar'])) ? 'img/avatar.jpg' : 'tmp/'.$this->help->duplicatePublic($perfil_menu_top['avatar']);
                $usuario_name = $usuario_menu_top['nombres'];
            }
            require URL_TEMPLATE.'start.php';
        }
}
?>
