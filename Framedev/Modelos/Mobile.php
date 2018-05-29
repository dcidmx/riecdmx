<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Database\Capsule\Manager as Capsule;
class MobileElq extends Illuminate\Database\Eloquent\Model {

    protected $table = 'framework.fw_mobile';
    protected $primaryKey = 'id_mobile';
    public $timestamps = false;

}
