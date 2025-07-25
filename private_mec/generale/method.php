<?php
// if (!defined("PABLO_EL_CHICO") ) exit;
// if(PABLO_EL_CHICO!="<pablo class='chicken-man'>el chicko the biggest dealer</pablo>") exit;
// if(!defined("METHOD_PHP")) exit;
// if(METHOD_PHP!="101101-111") exit;

define("PABLO_EL_CHICO", "<pablo class='chicken-man'>el chicko the biggest dealer</pablo>");
define("JWT_AUTHENTICATOR", "111_0011011010101");

require '../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

include("../private_mec/generale/jwt_auth.php");


function connect_to_server($host, $dbname, $username, $password, $server = "mysql"){
    //Create the connection to the server
    try{
        $conn = new PDO("$server:host=$host;dbname=$dbname", $username, $password);
        return array("ok"=>true, "connection"=>$conn);
    }catch(PDOException $e){
        return array("ok"=>false, "response"=>$e->getMessage());
    }
}

function conn_to_main(){
    return connect_to_server($_SERVER["HTTP_HOST"], "scea_main", "steam", "scea@team.db");
}

function secure_name($name){
    //return a secure name
    if(is_string($name) || is_int($name)){
        return trim(stripslashes(''.$name));
    }
    return $name;
}

function delCookie($cookie){
    //delete a cookie
    setcookie($cookie, '', time()-3600,'/');
}

function createCookie($cookie, $cookie_value, $cookie_expire = null) {
    //set a cookie
    if (!$cookie_expire) {
        $cookie_expire = time() + 36600 * 24 * 30;
    }
    else{
        $cookie_expire = time() + 3600 * $cookie_expire;
    }

    setcookie($cookie, $cookie_value, [
        'expires' => $cookie_expire,
        'path' => '/',
        'domain' => '',
        'httpOnly' => false,
        'secure' => false,
        'samesite' => "Lax"
    ]);
}

function monthDayToDate($string, $end=3){
    $month = substr($string, 0, 2);
    $day = substr($string, 2, 2); 
    $year = date("Y");
    $time = date("H:i:s");
    $start = "$year-$month-$day $time";
    $startDate = new DateTime($start);
    $startDate->modify("+$end days");
    $exp = $startDate->format("Y-m-d H:i:s");
    return [$start, $exp];
}

function format_name($string) {
    $list = explode(" ", $string);
    $final = [];
    foreach ($list as $word) {
        if ($word !== "") {
            $first = strtoupper($word[0]);
            $rest = strtolower(substr($word, 1));
            $final[] = $first . $rest;
        }
    }
    return secure_name(implode(" ", $final));
}

function makeItHard($hard){
    $word = '';
    $c=0; $v = 0; $r=0;
    for($i=0; $i<strlen($hard); $i++){
        if(str_contains("eyuioa", strtolower($hard[$i]))){
            $v++;
            $word = $word.''.($v%2==0?0:$hard[$i]);
        }
        else if(str_contains("qwrtpsdfghjklzxcvbnm", strtolower($hard[$i]))){
            $c++;
            $word = $word.''.($c%2==0?1:$hard[$i]);
        }
        else{
            $r++;
            $word= $word.''.($r%2==0?-1:$hard[$i]);
        }
    }
    return $word;
}

function createJWT($value){
    $key = base64_encode(makeItHard($GLOBALS["SIGNATURE"]));
    return JWT::encode($value, $key, "HS256");
}

function readJWT($jwt){
    try{
        $decoded = JWT::decode($jwt, new Key(base64_encode(makeItHard($GLOBALS["SIGNATURE"])), "HS256"));
        return array("ok"=>true, "val"=>$decoded);
    }
    catch(Exception $e){
        return array("ok"=>false, "val"=>$e->getMessage());
    }
}

function verify_email($email) {
    if (is_string($email) && secure_name($email)) {
        $reg = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        return preg_match($reg, secure_name($email)) === 1;
    }
    return false;
}

