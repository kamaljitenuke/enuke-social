<?php

//configuration file
$config = \Config::get("socialogin::config");

//OAuth response
Route::get($config['redirect'], function() {

    //get OAuth provider name store in session 
   $oauth_provider = Session::get('oauth_provider');

    if (!Session::has('oauth_provider'))
    {
        Redirect::to('/')->with('message', 'Invalid Access');
    }
    

  if ( $oauth_provider == 'facebook' ) {  
        $code = Input::get('code');
        if (strlen($code) == 0) return Redirect::to('/')->with('message', 'There was an error communicating with Facebook');

        $uid = Socialogin::getUser();

        if ($uid == 0) return Redirect::to('/')->with('message', 'There was an error');

        $me = Socialogin::api('/me');
        $user = User::whereOauth_uid($me['id'])->first();

        if (empty($user)) {
            $user = new User;
            $user->oauth_uid = $me['id'];
            $user->oauth_provider = 'Facebook';
            $user->name = $me['first_name'].' '.$me['last_name'];
            $user->email = $me['email'];
            $user->photo = 'https://graph.facebook.com/'.$me['username'].'/picture?type=large';
            $user->save();
        } 

  }  else if ( $oauth_provider == 'google' ) {
        $google = new Enuke\Socialogin\Google;
        $data = $google->get_data();
        if ( empty($data) ) return Redirect::to('/')->with('message', 'There was an error');
            $user = User::whereOauth_uid($data['id'])->first();
        if (empty($user)) {
            $user = new User;
            $user->oauth_uid = $data['id'];
            $user->oauth_provider = 'Google';
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->photo = $data['picture'];

            $user->save();
        } 
  } else if ( $oauth_provider == 'twitter' ){
        $twitter = new Enuke\Socialogin\Twitter;
        $data = $twitter->get_return();

        if ( empty($data) ) return Redirect::to('/')->with('message', 'There was an error');

        $user = User::whereOauth_uid($data->id)->first();

        if (empty($user)) {
          $user = new User;
          $user->oauth_uid = $data->id;
          $user->oauth_provider = 'Twitter';
          $user->name = $data->screen_name;
          $user->email = '';
          $user->photo = $data->profile_image_url_https;

          $user->save();
        } 
  }
    //Removing An Item From The Session
    Session::forget('key');
    
    Auth::login($user);
    return Redirect::to('/')->with('message', 'Logged in with'.ucfirst($oauth_provider));
});

Route::get('/login/', function() { 
    $type = Input::get('type');
    if (empty($type)){
         Redirect::to('/')->with('message', 'Invalid Access');
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

Route::get('logout', function() {
    Auth::logout();
    return Redirect::to('/');
});

/*
Route::get('/', function()
{
    $data = array();

    if (Auth::check()) {
        $data = Auth::user();
    }
    return View::make('socialogin::users', array('data'=>$data));
});*/

