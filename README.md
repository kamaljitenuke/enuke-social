enuke-social
============

Social login package laravel

Installation Note

Package Installation:

1)composer.json and add below code
 	
	"require": {
		-- -- -- -- -- --
                "enuke/socialogin": "dev-master"
	},

2)app/config/app.php

	'providers' => array(
		-- -- -- -- -- --
                'Enuke\Socialogin\SocialoginServiceProvider',

	),

	'aliases' => array(
		-- -- -- -- -- --
                'Socialogin'      => 'Enuke\Socialogin\SocialoginFacade',
	),


3) From your project terminal

	composer update


4) configure your key in vendor/enuke/socialogin/src/config/config.php

5) paste the below line in your view 

	<a href="/login?type=facebook">Login with Facebook</a><a href="/login?type=twitter">Login with Twitter</a>
	<a href="/login?type=google">Login with Google</a>
	
	Note: don't change the type param.

6)In your route place

	//package configuration file
	$config = \Config::get("socialogin::config");

	//OAuth response
	Route::get($config['redirect'], function() {

		//get OAuth provider name store in session 
		$oauth_provider = Session::get('oauth_provider');

		if (!Session::has('oauth_provider'))
		{
			die('Invalid Access');
		}


		if ( $oauth_provider == 'facebook' ) {  
			$code = Input::get('code');
			if (strlen($code) == 0) die('There was an error communicating with Facebook');
			$uid = Socialogin::getUser();
			if ($uid == 0) die('There was an error');
			$data = Socialogin::api('/me');

		}  else if ( $oauth_provider == 'google' ) {
			$google = new Enuke\Socialogin\Google;
			$data = $google->get_data();
			if ( empty($data) ) die('There was an error');
		} else if ( $oauth_provider == 'twitter' ){
			$twitter = new Enuke\Socialogin\Twitter;
			$data = $twitter->get_return();
			if ( empty($data) ) die('There was an error communicating with Twitter');
		}
		//Removing An Item From The Session
		Session::forget('key');
		//return data from Oauth provider
		print_r($data);
	});

	// login route 
	Route::get('/login/', function() { 
		$type = Input::get('type');
		if (empty($type)){
			die('Invalid Access');
		}
		Session::put('oauth_provider', $type);

		if($type == 'facebook') {
			return Redirect::to(Socialogin::loginUrl());
		} else if ($type == 'twitter') {
			$twitter = new Enuke\Socialogin\Twitter;
			$check_connection = $twitter->login(); 
			if ( $check_connection ){
			   return Redirect::to($check_connection);
			} else {
			    die('Something Went Wrong');
			}
		} elseif ($type == 'google'){
			$google = new Enuke\Socialogin\Google;
			$url = $google->login();
			return Redirect::to($url); 
		} 
	});
