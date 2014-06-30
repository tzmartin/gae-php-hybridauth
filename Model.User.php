<?php
/**
 * Base class for user model. Can be used with App Engine's default users API,
 * own auth or third party authentication methods (OpenID, OAuth etc).
 * 
 * Refer to this flow: 
 * https://docs.google.com/drawings/pub?id=1wd7o7Nxaq_IiafMZteDVsE0PflAsJBFk5mzbmkHZ5eU&w=652&h=1162
 */

use google\appengine\api\users\User;
use google\appengine\api\users\UserService;

require_once( "vendor/tzmartin/hybridauth-gae/hybridauth/Hybrid/Auth.php" );
require_once( "vendor/tzmartin/hybridauth-gae/hybridauth/Hybrid/Endpoint.php" );


final class UserModel {

  private static $instance = null;

  private $config = [];
  
  // Google auth identity object
  private $gAccount = null;
  
  // Config for Auth provider class
  private $auth_config = null;
  
  // Main Auth provider class that manages the auth lifecycle and invokes adapters.  Not available with Google authentication.
  public $auth = null;
  
  // Provider adapter that maintains the raw auth connection properties and methods. Not available with Google authentication.
  public $adapter = null;
  
  private $memcache = false;

  // Ephemeral object that holds profile data during a session.
  private static $profile = array();
  
  /**
   * Normalized properties across all identity providers as defined by HybridAuth.
   */
  private $hybridauth_template = array(
    "identifier" => false,
    "created" => '',
    "updated" => '',
    "username" => '',
    "name" => '',
    "last_name" => '',
    "email" => '',
    "country" => '',
    "tz" => '',
    "activated" => false
  );

  public function __construct() {
    require('config.php');
    $this->auth_config = $auth_config;
    
    // Set Hybrid auth
    $this->auth = new Hybrid_Auth($this->auth_config);
    
    // Set Google user
    if (UserService::getCurrentUser()) {
      $this->gAccount = UserService::getCurrentUser();
    } else {
      // Set provider
      if (count($this->auth->getConnectedProviders()) > 0) {
        $this->adapter = $this->auth->getAdapter($this->auth->getConnectedProviders()[0]);
      }
    }
    
    // Hydarate profile
    $this->profile = array_merge($this->hybridauth_template,array());
    
    // Set Memcache
    $this->memcache = new Memcache;
    
  }

  public function isGoogleAccount() {
    return isset($this->gAccount);
  }
  
  public function isUserLoggedIn() {
    return (isset($_SESSION['fingerprint'])) ? true : false;
  }
  
  public function getUserID() {        
    return $this->getProfile()['identifier'];
  }
  
  public function getProfile() {    
    return $this->memcache->get($_SESSION['fingerprint']);   
  }
  
  public function setProfile($options) {
    
    $uid = md5($_SERVER['HTTP_USER_AGENT'] . $this->profile['identifier'] . $_SERVER['REMOTE_ADDR']);
    $this->memcache->set($uid, array_merge($this->hybridauth_template,$options));
    $_SESSION['fingerprint'] = $uid;
  }
  
  public function createLoginUrl($provider,$url) {

    // Check if Google Account
    if ($provider == 'Google') {
      $out = UserService::createLoginUrl($url.'&googleaccount');
    } else {
      $out = $_SERVER['REQUEST_URI'].'&hybridauth';
    }
    return $out;
  }
  
  public function authenticate($provider, $params) {
    // Set provider    
    if ($provider == 'Google') {
      $this->redirect($params['hauth_return_to']);
    } else {
      $this->adapter = $this->auth->authenticate($provider,$params);
    }
  }
  
  public function logout($url) {
    $out = $url;
    if ($this->isGoogleAccount()){
      $out = UserService::createLogoutUrl($url);
      $this->gAccount = false;
    } else {
      if (count($this->auth->getConnectedProviders()) > 0) {
        $this->auth->logoutAllProviders();
        //$this->adapter = null;
      }
    }
    unset($_SESSION['fingerprint']);
    
    // clear 
    $this->profile = array_merge($this->hybridauth_template,array());
    
    session_destroy();
    
    $this->auth->redirect($out);
  }
  
  public function setUserID() {    
    if (isset($this->gAccount)) {
      return $this->gAccount->getUserId();
    }
    
    return null;
  }

  public function redirect($url) {  
    // Simple redirect method
    $isStatic = !(isset($this) && get_class($this) == __CLASS__);
    if (!isStatic) {
      echo "NOT STATIC";
    }
    $this->auth->redirect($url);
  }
  
  public function process() {
    // Run HybridAuth process
    Hybrid_Endpoint::process();
  }

}