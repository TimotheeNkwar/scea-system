<?php
// if (!defined("PABLO_EL_CHICO") ) exit;
// if(PABLO_EL_CHICO!="<pablo class='chicken-man'>el chicko the biggest dealer</pablo>") exit;
// if(!defined("REGISTER_ADMIN_PHP")) exit;
// if(REGISTER_ADMIN_PHP!="10101101_01101-111") exit;

$_register_admin_ = conn_to_main();
if(!$_register_admin_["ok"]){
    echo json_encode(array("ok"=>false, "response"=>false));
    exit;
}

function register_admin(){
    try{
        $fname = secure_name($GLOBALS["data"]["fname"]);
        $sname = secure_name($GLOBALS["data"]["sname"]);
        $tname = secure_name($GLOBALS["data"]["tname"]);
        $bdate = secure_name($GLOBALS["data"]["birthdate"]);
        $bplace = secure_name($GLOBALS["data"]["birthplace"]);
        $gender = secure_name($GLOBALS["data"]["gender"]);
        if(!is_numeric($gender)){
            $gender = "1";
        }
        $email = secure_name($GLOBALS["data"]["email"]);
        if(!verify_email($email)){
            echo json_encode(array("ok"=>false, "response"=>"email"));
            exit;
        }
        $phone1 = is_numeric(secure_name($GLOBALS["data"]["phone1"]))?secure_name($GLOBALS["data"]["phone1"]):null;
        if(!$phone1){
            echo json_encode(array("ok"=>false, "response"=>"phone1"));
        }
        $phone2 = $GLOBALS["data"]["phone2"]?(secure_name($GLOBALS["data"]["phone2"])):null;
        $address = secure_name($GLOBALS["data"]["address"]);
        $etat_civil = secure_name($GLOBALS["data"]["etat_civil"]);
        $role_id = "02";
        $dt = DateTime::createFromFormat('U.u', microtime(true));
        $temp_id = password_hash(base64_encode($GLOBALS["data"]["email"])
            .base64_encode($fname)
            .base64_encode($phone1)
            .base64_encode($email)
            .base64_encode($role_id)
            .base64_encode($dt->format("Y-m-d H:i:s.v"))
            .base64_encode(rand(1, 1000))
        , PASSWORD_BCRYPT);

        
    }
    catch(Exception $e){
        echo json_encode(array("ok"=>false, "response"=>$e->getMessage()));
    }
}