<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Database\Capsule\Manager as Capsule;
class SiteElq extends Illuminate\Database\Eloquent\Model {

    protected $table = 'framework.fw_site';
    protected $primaryKey = 'id_site';
    public $timestamps = false;

}
