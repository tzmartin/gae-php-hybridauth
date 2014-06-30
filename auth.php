<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

session_start(); 

use google\appengine\api\users\User;
use google\appengine\api\users\UserService;

// config and includes

require_once 'Model.User.php';

$User = new UserModel;

try{
  
  switch($_REQUEST) {
    
    case isset($_REQUEST['hauth_start']):
      $User->process();
      break;
      
    case isset($_REQUEST['hauth_done']):
      $User->process();
      break;
      
    case isset($_REQUEST['googleaccount']):
    
      $profile = UserService::getCurrentUser();

      // Save to User model
      $User->setProfile(
        array(
          'identifier' => $profile->getUserId(),
          'username' => $profile->getNickname(),
          'email' => $profile->getEmail()
        )
      );
      
      // redirect
      $User->redirect('/');
      break;
      
    case isset($_REQUEST['hybridauth']):
    
      // Get raw user profile
      $profile = $User->adapter->getUserProfile();
      
      
      /*
       *
       * Insert authenticated user validation against internal user registration records
       * - If exists, then load profile and authorization rules
       * - If not exists, redirect to registration screen
       * 
       */
      
      // If validated, persist user session profile
      $User->setProfile((array) $profile);

      // redirect
      $User->redirect('/');
      
      break;
      
    default:
    
      // Create auth URL
      $url = $User->createLoginUrl($_GET['provider'],'/auth?provider='.$_GET['provider']);
      
      // Start authentication flow
      $User->authenticate( $_GET['provider'], array('hauth_return_to' => $url) );

  }
  
}
catch( Exception $e ){
  echo "Oops, error: " . $e->getMessage();
}

