<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Database\Capsule\Manager as Capsule;
class SidebarElq extends Illuminate\Database\Eloquent\Model {

    protected $table = 'framework.fw_sidebar';
    protected $primaryKey = 'id_sidebar';
    public $timestamps = false;

}
