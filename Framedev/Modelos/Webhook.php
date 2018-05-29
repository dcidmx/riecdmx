<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Database\Capsule\Manager as Capsule;
class WebhookElq extends Illuminate\Database\Eloquent\Model {

    protected $table = 'framework.fw_webhook';
    protected $primaryKey = 'id_webhook';
    public $timestamps = false;

    static function member_added($user_id){

      $store = new WebhookElq;
      $store->user = $user_id;
      $store->fecha = date("Y-m-d H:i:s");
      $store->save();

    }
    static function member_removed($user_id){
      WebhookElq::where('user',$user_id)->delete();
    }

}
