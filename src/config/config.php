<?php 

return array(
    
//Redirect URL after successfull login.
'redirect' => '/callback',   
    
//set up your facebook appid and secret key    
        'secret' => array(
                        //put your app id and secret
                        'appId'  => '103866106482730',
                        'secret' => '8533f07ba350199fe1d6210691c083a3'
                    ),

        //When Someone Logout from your Site
        'logout' => url('/'),
        //you can add scope according to your requirement
        'scope' => 'email',
    
// Set your application twitter consumer key & consumer secret
        'twitter' => array(
                        'consumer_key' => "7SRXELvgzRHz7uLugrcNcNj6Y",
                        'consumer_secret' => "rnkA9yPfiwCpm9YwCB7FxBqwasXW8iDQz5SENTUGD3aKj5Z9j8",
                        ),

// Set your application google keys
    'google' => array(
                        'client_id' => "114982529972-golvfcltogjmmh6ten08scpd9kh9hs09.apps.googleusercontent.com",
                        'client_secret' => "pN0HcoigD2glx1hfLxxNybsL",
                        'developer_key' => 'AIzaSyC9rchVvRX1LQsQ9PMm43HixQTy9qaH6RY',
                      ),
        );
