<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class LoopedInSSO_Login
{
      /**
     * Start up
     */
    public function __construct()
    {
        add_filter( 'login_redirect', array( $this, 'LoopedInSSO_login_redirect_callback' ), 10, 3);
        add_action( 'login_form',array( $this, 'LoopedInSSO_hidden_login_field' ), 10, 0 );
        add_action('init', array( $this, 'LoopedInSSO_login_check' ));
    }

    public function LoopedInSSO_login_redirect_callback($redirect_to, $requested_redirect_to, $user){

       if( !isset( $user->user_login ) || $_POST['returnURL'] == '' )
        {
            return $redirect_to;
        }

       $LoopedInSSO_settings =  get_option( 'LoopedInSSO_settings_name' );

       if($LoopedInSSO_settings['LoopedInSSO_key'] != ''){

        $this->LoopedInSSO_authenticate_sso($user,$_POST['returnURL'],$LoopedInSSO_settings['LoopedInSSO_key']);

        }else{
            return $redirect_to;
        }

    }

    public function LoopedInSSO_hidden_login_field() {
        if(isset($_GET['returnURL'])){
        ?>
        <input type="hidden" name="returnURL" value="<?php echo esc_url($_GET['returnURL']); ?>" />
        <?php
        }
    }

    public function LoopedInSSO_login_check()
    {
        if(is_user_logged_in() && stripos($_SERVER["SCRIPT_NAME"], strrchr(wp_login_url(), '/')) !== false && isset($_GET['returnURL'])){
            $user = wp_get_current_user();
            $LoopedInSSO_settings =  get_option( 'LoopedInSSO_settings_name' );

            if($LoopedInSSO_settings['LoopedInSSO_key'] != ''){
                $this->LoopedInSSO_authenticate_sso($user,$_GET['returnURL'],$LoopedInSSO_settings['LoopedInSSO_key']);
            }
        }
        
    }

    public function LoopedInSSO_authenticate_sso($user,$returnURL,$LoopedInSSO_Token){

        $LoopedInSSO_payload = array("email" => $user->user_email, "name" => $user->first_name." ".$user->last_name);

        $LoopedInSSO_jwt = JWT::encode($LoopedInSSO_payload, $LoopedInSSO_Token, 'HS256');

        $LoopedInSSO_location = $returnURL."?token=".$LoopedInSSO_jwt;

        if(strpos($returnURL, 'settings') !== false){
            $LoopedInSSO_location .= '#/sso';
        }
        
        header("Location: ".$LoopedInSSO_location);
        exit();

    }

}