<?php namespace Enuke\Socialogin;

//include google api files
require_once __DIR__ .'/GoogleOAuth/Google_Client.php';
require_once __DIR__ .'/GoogleOAuth/contrib/Google_Oauth2Service.php';

use Session;
use App;

class Google {
    
        protected $google_key = array();
        protected $gClient;
        protected $google_oauthV2;
        
        public function __construct(){
            
            $this->google_key = \Config::get("socialogin::config");
            
            $this->gClient = new \Google_Client();
            $this->gClient->setApplicationName('Login to dev.laravel.com');
            $this->gClient->setClientId($this->google_key['google']['client_id']);
            $this->gClient->setClientSecret($this->google_key['google']['client_secret']);
            $this->gClient->setRedirectUri(url($this->google_key['redirect']));
            $this->gClient->setDeveloperKey($this->google_key['google']['developer_key']);
            
            $this->google_oauthV2 = new \Google_Oauth2Service($this->gClient);
            
            
        }
        public function login(){
           
             //get google login url
                $authUrl = $this->gClient->createAuthUrl();
                return $authUrl;
            
            if (Session::has('google_token')) 
            { 
                $this->gClient->setAccessToken(Session::get('google_token'));
                die('You are already login');
            }
            else 
            {
                //get google login url
                $authUrl = $this->gClient->createAuthUrl();
                return $authUrl;
            }
        }
        
       
        public function get_data(){
            
           if (isset($_REQUEST['reset'])) 
            {
                Session::forget('google_token');
                $this->gClient->revokeToken();
                header('Location: ' . filter_var($this->google_redirect_url, FILTER_SANITIZE_URL));
            }

            if (isset($_GET['code'])) 
            { 
                $this->gClient->authenticate($_GET['code']);
                Session::put('google_token', $this->gClient->getAccessToken());
                return $this->google_oauthV2->userinfo->get();
            }
            return false;
      }
}            
