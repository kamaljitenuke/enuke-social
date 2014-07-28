<?php namespace Enuke\Socialogin;

require_once __DIR__ . '/TwitterOAuth/TwitterOAuth.php';
require_once __DIR__ . '/TwitterOAuth/OAuth.php';
use Session;
use TwitterOAuth\TwitterOAuth;
use App;

class Twitter {

	
        protected $twitter_key = array();
    
        public function __construct(){
            $this->twitter_key = \Config::get("socialogin::config");
        }
        
        
        public function login()
        {
            
           $connection = new TwitterOAuth($this->twitter_key['twitter']['consumer_key'],$this->twitter_key['twitter']['consumer_secret']);
            
            //$request_token = $connection->getRequestToken('http://dev.laravel.com/response');
           $request_token = $connection->getRequestToken(url($this->twitter_key['redirect']));
            Session::put('oauth_token', $request_token['oauth_token']);
            Session::put('oauth_token_secret', $request_token['oauth_token_secret']);
            
            if ($connection->http_code == 200) {
                 $url = $connection->getAuthorizeURL($request_token['oauth_token']);
                 return $url;
            } else {
                return false;
            }
        }
        
        public function get_return(){
            
            if (!empty($_GET['oauth_verifier']) && 
                    Session::has('oauth_token') && Session::has('oauth_token_secret')) {
                $twitteroauth = new TwitterOAuth($this->twitter_key['twitter']['consumer_key'],$this->twitter_key['twitter']['consumer_secret'],Session::get('oauth_token'), Session::get('oauth_token_secret'));
                // Let's request the access token
                $access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']);
                // Save it in a session var
                Session::put('access_token', $access_token);
               // Let's get the user's info
                $user_info = $twitteroauth->get('account/verify_credentials');
                
                if (isset($user_info->error)) {
                    die('Something went wrong!');
                } else {

                    $twitter_otoken=Session::get('oauth_token');
                    $twitter_otoken_secret=Session::get('oauth_token_secret');
                    return $user_info;
                }
            } else {
                // Something's missing, go back to square 1
                die('Something went wrong!');
            }
        }
}
