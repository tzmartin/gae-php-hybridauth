<?php

use google\appengine\api\users\User;
use google\appengine\api\users\UserService;

require_once 'Model.User.php';

# Looks for current Google account session

$User = new UserModel;

//
// Set vars
//
$userName = ($User->getProfile()['username']) ? $User->getProfile()['username'] : $User->getProfile()['displayName'];
$userInfo = var_export($User->getProfile(), true);
$sessionInfo = var_export($_SESSION, true);

//
// Check auth state
//
if( $User->isUserLoggedIn() ) 
{
 
// Logged IN! 
$title = "Welcome!";
$header = <<< EOF
  <h3>Welcome, {$userName}!</h3>
  <div>
    <a href="/logout">sign out</a>
  </div>
EOF;
  
} 
  else 
{

// Logged OUT!
$title = "GAE PHP Hybrid Auth Demo - Login";
$header = <<< EOF
  <h3>Try it</h3><img src="/images/img.arrow.png" width="70" height="30" style="margin-left: 10px;">
  <ul>
    <li>
      <a href="/auth?provider=Google"><img src="http://g.etfv.co/http://www.google.com" alt="Google">Google</a>
    </li>
    <li class="facebook">
      <a href="/auth?provider=Facebook"><img src="http://g.etfv.co/http://www.facebook.com" alt="Google">Facebook</a>
    </li>
    <li class="github">
      <a href="/auth?provider=GitHub"><img src="http://g.etfv.co/http://www.github.com" alt="Google">Github</a>
    </li>
    <li class="twitter">
      <a href="/auth?provider=Twitter"><img src="http://g.etfv.co/http://www.twitter.com" alt="Google">Twitter</a>
    </li>
  </ul>
EOF;

}


// 
// Styles
//
$style = <<< EOF
  <style type="text/css">
    html, body {height: 100%;margin: 0;padding: 5px;border: none;font-family:Helvetica;background-color:#f1f1f3;}
    ul {}
    li {display: inline;list-style-type: none;padding-right: 15px;margin-right: 20;border-right: 1px solid #ddd;}
    li a {text-decoration: none;font-size: 1.2em;}
    li img {width:16px;height:16px;padding-right: 4px;}
    h1 a {font-size:11px;font-style:superscript;}
    h3 {float: left;margin: 0;margin-bottom: 20px;}
    h4 {color:#333;font-size:80%;margin-bottom: 0px;border-left: 1px #ddd solid;border-right: 1px #ddd solid;padding: 0px 5px 0px 5px;border-top: 1px #ddd solid;width: 80px;text-align: center;}
    pre {margin-top: 0;border:1px solid #ddd;padding:6px 10px;background-color:#fafafa;overflow: auto;-webkit-font-smoothing: auto;font-size: 13px;color: #080;font-family:courier;}
    #maincontent {padding:1.5em;margin: 3em;border-radius: 3px;background: #fff;}
    .btnbar {margin-top: -15px;margin-bottom: 20px;}
    
    /* Smartphones (portrait and landscape) ----------- */
    @media only screen and (min-device-width : 320px) and (max-device-width : 480px) {
      
    }
    
  </style>
EOF;


//
// Layout Template
//
$html = <<< EOF
  <html>
  <head>
    <title>{$title}</title>
    {$style}
  </head>
  <body>
    <div id="maincontent">
    <div>
      <h1>GAE - PHP HybridAuth Demo <a href="https://github.com/tzmartin/gae-php-hybridauth">How it works</a></h1>
      <div class="btnbar">
        <iframe src="http://ghbtns.com/github-btn.html?user=tzmartin&amp;repo=gae-php-hybridauth&amp;type=watch&amp;count=true&amp;size=large" allowtransparency="true" frameborder="0" scrolling="0" width="160px" height="30px"></iframe>
      </div>
    </div>
    {$header}
    <h4>User Info</h4>
    <pre>{$userInfo}</pre>

    <h4>Session Info</h4>
    <pre>{$sessionInfo}</pre>
    </div>
  </body>
  </html>
EOF;

echo $html;