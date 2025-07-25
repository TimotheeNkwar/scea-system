<?php
// if (!defined("PABLO_EL_CHICO") ) exit;
// if(PABLO_EL_CHICO!="<pablo class='chicken-man'>el chicko the biggest dealer</pablo>") exit;
// if(!defined("LOGIN_PHP")) exit;
// if(LOGIN_PHP!="10101-111") exit;

$_login_ = conn_to_main();
if(!$_login_["ok"]){
    echo json_encode(array("ok"=>false, "response"=>false));
    exit;
}

function login(){
    try{
        $user = secure_name($GLOBALS["data"]["user"]);
        $password = secure_name($GLOBALS["data"]["password"]);
        $log = try_login($user, $password, $user);
        return $log;
    }
    catch(Exception $e){
        return array("ok"=>false, "response"=>$e->getMessage());
    }
}

function page_login($path = "../"){
    try{
        if(!isset($_COOKIE["token"]))
        {
            return array("ok"=>false, "response"=>false);
        }
        $jwt = readJWT($_COOKIE["token"]);
        if(!$jwt["ok"]){
            return array("ok"=>false, "response"=>false);
        }
        $val = $jwt["val"];
        $user_id = $val->sid;
        $password = $val->password;
        $role_id = $val->r;
        $email = $val->email;
        $phone = $val->phone;
        return try_login($email, $password, $phone, $user_id, $path);
    }
    catch(Exception $e){
        return array("ok"=>false, "response"=>$e->getMessage());
    }
}