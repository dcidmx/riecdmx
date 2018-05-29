<?php
class PusherMiddleware
{
  function __construct($db) {
      try {
          $this->db = $db;
      } catch (PDOException $e) {
          exit('No se ha podido establecer la conexión a la base de datos.');
      }
  }

  public function transmit($emision,$proceso){

			require_once('../vendor/pusher/pusher-php-server/src/Pusher.php');
			$options = array('encrypted' => true);
			$pusher = new Pusher\Pusher(PUSHER_KEY,PUSHER_SECRET,PUSHER_APP_ID,$options);

			$emision = json_decode($emision,true);
			$data['message'] = $emision;
			$pusher->trigger($proceso, 'evento', $data);

	}
}
