<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Database\Capsule\Manager as Capsule;
class CoreElq extends Illuminate\Database\Eloquent\Model {

    protected $table = 'fw_config';
    protected $primaryKey = 'id_config';
    public $timestamps = false;

    static function updateLogin(){

        Capsule::table('framework.fw_login')
            ->where('id_usuario', $_SESSION['id_usuario'])
            ->where('open', 1)
            ->update(['ultima_verificacion' => date("Y-m-d H:i:s")]);
    }

}
