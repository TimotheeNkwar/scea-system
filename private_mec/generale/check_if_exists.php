<?php
// if (!defined("PABLO_EL_CHICO") ) exit;
// if(PABLO_EL_CHICO!="<pablo class='chicken-man'>el chicko the biggest dealer</pablo>") exit;
// if(!defined("CHECK_IF_EXISTS_PHP")) exit;
// if(CHECK_IF_EXISTS_PHP!="11011_01_010111-111") exit;

$_check_if_exists_ = conn_to_main();
if(!$_check_if_exists_["ok"]){
    echo json_encode(array("ok"=>false, "response"=>false));
    exit;
}

function check_person($user_id = null, $email = null, $phone=null){
    if(!$user_id && !$email && !$phone){
        return array("ok"=>false, "response"=>false);
    }
    $user_id = secure_name($user_id);
    $email = secure_name($email);
    $phone = secure_name($phone);
    $sql = file_get_contents("../private_qwr/generale/003_check_person.sql");
    $prep = $GLOBALS["_check_if_exists_"]["connection"]->prepare($sql);
    $prep->bindParam(":full_id", $user_id);
    $prep->bindParam(":short_id", $user_id);
    $prep->bindParam(":email", $email);
    $prep->bindParam(":phone1", $phone);
    $prep->bindParam(":phone2", $phone);
    $prep->execute();
    $fecth = $prep->fetch();
    $prep->closeCursor();
    if(!$fecth){
        return array("ok"=>true, "response"=>null);
    }
    return array("ok"=>true, "reponse"=>true, "data"=>$fecth["full_id"]);
}

