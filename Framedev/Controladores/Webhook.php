<?php
class Webhook extends Controlador
{
    public function index(){}
    public function presence(){

        $app_secret = PUSHER_SECRET;
        $app_key = $_SERVER['HTTP_X_PUSHER_KEY'];
        $webhook_signature = $_SERVER ['HTTP_X_PUSHER_SIGNATURE'];
        $body = file_get_contents('php://input');
        $expected_signature = hash_hmac( 'sha256', $body, $app_secret, false );

        if($webhook_signature == $expected_signature) {

            $payload = json_decode( $body, true );

            foreach($payload['events'] as &$event) {

                $hook = $this->loadEloquent('Webhook');
                if($event['name'] == 'member_removed'){
                    $hook->member_removed($event['user_id']);
                }else if($event['name'] == 'member_added'){
                    $hook->member_added($event['user_id']);
                }

            }
            header("Status: 200 OK");

        } else {
            header("Status: 401 Not authenticated");
        }
    }
    public function pusher_auth(){
        $this->se_requiere_logueo(true,'Mobile|index');
        require_once('../vendor/pusher/pusher-php-server/src/Pusher.php');
        $pusher = new Pusher\Pusher(PUSHER_KEY, PUSHER_SECRET, PUSHER_APP_ID);
        $presence_data = array(
            'name' => $_SESSION['usuario'],
            'id_usuario' => $_SESSION['id_usuario']
        );
        echo $pusher->presence_auth(
            $_POST['channel_name'],
            $_POST['socket_id'],
            $_SESSION['id_usuario'],
            $presence_data
        );
    }
}
?>
