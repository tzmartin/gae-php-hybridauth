<?php
/*
  base_url defines the end point for all auth init and callback flows
  List supported providers.  Each provider must have a corresponding Provider class in Providers folder.  Verify these exist in HybridAuth directory.
*/
$auth_config = array( 
   "base_url" => 'http://'.$_SERVER['SERVER_NAME'].(isset($_SERVER['SERVER_PORT']) ? ":".$_SERVER['SERVER_PORT'] : "")."/auth",
   "providers" => array ( 
   
       "Google" => array (
           "enabled" => true
       ),
       
       // Create an app: http://dev.twitter.com
       "Twitter" => array ( 
           "enabled" => true, 
           "userid" => "displayName",
           "keys" => array ( "key" => "[GET KEY]", "secret" => "[GET SECRET]" )
       ),
       
       // Create an app: http://dev.github.com
       "GitHub" => array ( 
            "enabled" => true, 
            "userid" => "email",
            "keys" => array ( "id" => "[GET KEY]", "secret" => "[GET SECRET]" )
       ),
      
       // Create an app: http://dev.foursquare.com
       "Foursquare" => array ( 
            "enabled" => true, 
            "userid" => "email",
            "keys" => array ( "id" => "[GET KEY]", "secret" => "[GET SECRET]" )
       )
   ) 
);

?>