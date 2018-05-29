<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Database\Capsule\Manager as Capsule;
use LiveControl\EloquentDataTable\DataTable as DT;
use LiveControl\EloquentDataTable\ExpressionWithName;
class ViewlogElq extends Illuminate\Database\Eloquent\Model {

    protected $table = 'framework.view_log';
    protected $primaryKey = 'id_login';
    public $timestamps = false;

    static function logger(){
      $logins = new ViewlogElq();
      $dataTable = new DT(
        $logins,
        ['id_login', 'open', 'fecha_login', 'ultima_verificacion', 'fecha_logout', 'tiempo_session', 'ipv4', 'usuario', 'descripcion']
      );
      $dataTable->setFormatRowFunction(function ($logins) {
        return [
          $logins->id_login ,
          $logins->open ,
          $logins->fecha_login ,
          $logins->ultima_verificacion ,
          $logins->fecha_logout ,
          $logins->tiempo_session ,
          $logins->ipv4 ,
          $logins->usuario ,
          $logins->descripcion
        ];
      } );
      return $dataTable->make();
    }
}
