<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Database\Capsule\Manager as Capsule;
class InicioElq extends Illuminate\Database\Eloquent\Model {

    protected $table = 'fw_inicio';
    protected $primaryKey = 'id_inicio';
    public $timestamps = false;

}
