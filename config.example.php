<?php
/*
  List supported providers for this application
  
  base_url defines the end point for all auth init and callback flows
  todo: map userid to provider fields
  
  Note: Each provider listed here must have a corresponding class in vendor/tzmartin/hybridauth-gae/hybridauth/Hybrid/Providers/.  

*/
$auth_config = array( 
   "base_url" => 'http://'.$_SERVER['SERVER_NAME'].(isset($_SERVER['SERVER_PORT']) ? ":".$_SERVER['SERVER_PORT'] : "")."/auth",
   "providers" => array ( 
   
       "Google" => array (
           "enabled" => true
       ),
       
       // Create an app: https://developers.facebook.com/apps
       "Facebook" => array (
           "enabled" => true,
           "keys" => array ( "key" => "[GET KEY]", "secret" => "[GET SECRET]" )
       ),
       
       // Create an app: http://dev.twitter.com
       "Twitter" => array ( 
           "enabled" => true, 
           "userid" => "displayName",
           "keys" => array ( "key" => "[GET KEY]", "secret" => "[GET SECRET]" )
       ),
       
       // Create an app: https://github.com/settings/applications/new
       "GitHub" => array ( 
            "enabled" => true, 
            "userid" => "email",
            "keys" => array ( "id" => "[GET KEY]", "secret" => "[GET SECRET]" )
       ),
      
       // Create an app: https://foursquare.com/developers/apps
       "Foursquare" => array ( 
            "enabled" => true, 
            "userid" => "email",
            "keys" => array ( "id" => "[GET KEY]", "secret" => "[GET SECRET]" )
       )
   ) 
);

?>