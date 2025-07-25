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
        $fname = format_name("".secure_name($GLOBALS["data"]["fname"]));
        $sname = format_name("".secure_name($GLOBALS["data"]["sname"]));
        $tname = format_name("".secure_name($GLOBALS["data"]["tname"]));
        $bdate = secure_name($GLOBALS["data"]["birthdate"]);
        $bplace = format_name(secure_name($GLOBALS["data"]["birthplace"]));
        $gender = secure_name($GLOBALS["data"]["gender"]);
        if(!is_numeric($gender)){
            $gender = "1";
        }
        $email = secure_name($GLOBALS["data"]["email"]);
        if(!verify_email($email)){
            return array("ok"=>false, "response"=>"Invalid email");
        }
        
        $phone1 = is_numeric(secure_name($GLOBALS["data"]["phone1"]))?secure_name($GLOBALS["data"]["phone1"]):null;
        if(!$phone1){
            return array("ok"=>false, "response"=>"Invalid phone number \"$phone1\"");
        }
        if(strlen("$phone1")!=9){
            return array("ok"=>false, "response"=>"Invalid phone number \"$phone1\"");
        }
        $prefixes = ["80", "81", "82", "85", "99", "90"];
        $valid = false;
        foreach ($prefixes as $prefix) {
            if (str_starts_with($phone1, $prefix)) {
                $valid = true;
                break;
            }
        }
        if (!$valid) {
            return array("ok"=>false, "response"=>"Invalid phone number \"$phone1\"");
        }
        $phone2 = $GLOBALS["data"]["phone2"]?(secure_name($GLOBALS["data"]["phone2"])):null;
        if($phone2){
            if(strlen("$phone2")!=9){
                return array("ok"=>false, "response"=>"Invalid phone number \"$phone2\"");
            }
            $valid = false;
            foreach ($prefixes as $prefix) {
                if (str_starts_with($phone2, $prefix)) {
                    $valid = true;
                    break;
                }
            }
            if (!$valid) {
                return array("ok"=>false, "response"=>"Invalid phone number \"$phone2\"");
            } 
        }

        $address = secure_name($GLOBALS["data"]["address"]);
        $etat_civil = secure_name($GLOBALS["data"]["etat_civil"]);
        $prov_id = secure_name($GLOBALS["data"]["province_id"]);
        $role_id = "02";
        $dt = DateTime::createFromFormat('U.u', microtime(true));
        $temp_id = password_hash(base64_encode($fname)
            .base64_encode($phone1)
            .base64_encode($email)
            .base64_encode($role_id)
            .base64_encode($dt->format("Y-m-d H:i:s.v"))
            .base64_encode(rand(1, 1000))
        , PASSWORD_BCRYPT);

        $sql_temp_id = file_get_contents("../private_qwr/generale/001_add_new_person.sql");
        $prep_temp_id = $GLOBALS["_register_admin_"]["connection"]->prepare($sql_temp_id);
        $prep_temp_id->bindParam(":temp_id", $temp_id);
        $prep_temp_id->execute();
        $prep_temp_id->closeCursor();

        $sql_temp_id = file_get_contents("../private_qwr/generale/001_select_person.sql");
        $prep_temp_id = $GLOBALS["_register_admin_"]["connection"]->prepare($sql_temp_id);
        $prep_temp_id->bindParam(":temp_id", $temp_id);
        $prep_temp_id->execute();
        $fetch_temp_id = $prep_temp_id->fetch();
        $prep_temp_id->closeCursor();
        
        $row_id = "".$fetch_temp_id["row_id"];
        while(strlen($row_id)<8){
            $row_id = "0".$row_id;
        }
        $edate = $fetch_temp_id["edate"];
        $date = dateToMonthDay($edate);
        $time = dateToHourMinute($edate);
        $rand_int = rand(100, 999);
        $init = strtoupper($fname[0].$sname[0].$tname[0]);
        
        $short_id = "SPSN-".$row_id."-".$init;
        $full_id = "SPSN-".$role_id."-".$date."-".$time."-".$rand_int."-".$row_id."-".$init;
        $short_idh = password_hash(base64_encode(makeItHard($short_id)), PASSWORD_BCRYPT);
        $full_idh = password_hash(base64_encode(makeItHard($full_id)), PASSWORD_BCRYPT);
        $default_paswword = $fname."0001";
        $password = password_hash($default_paswword, PASSWORD_BCRYPT);

        $sql_insert_person = file_get_contents("../private_qwr/generale/001_update_person.sql");
        $prep_insert_person = $GLOBALS["_register_admin_"]["connection"]->prepare($sql_insert_person);
        $prep_insert_person->bindParam(":short_id", $short_id);
        $prep_insert_person->bindParam(":full_id", $full_id);
        $prep_insert_person->bindParam(":short_idh", $short_idh);
        $prep_insert_person->bindParam(":full_idh", $full_idh);
        $prep_insert_person->bindParam(":fname", $fname);
        $prep_insert_person->bindParam(":sname", $sname);
        $prep_insert_person->bindParam(":tname", $tname);
        $prep_insert_person->bindParam(":bdate", $bdate);
        $prep_insert_person->bindParam(":bplace", $bplace);
        $prep_insert_person->bindParam(":gender", $gender);
        $prep_insert_person->bindParam(":email", $email);
        $prep_insert_person->bindParam(":phone1", $phone1);
        $prep_insert_person->bindParam(":phone2", $phone2);
        $prep_insert_person->bindParam(":role_id", $role_id);
        $prep_insert_person->bindParam(":temp_id", $temp_id);
        $prep_insert_person->execute();
        $prep_insert_person->closeCursor();

        $sql_insert_empl = file_get_contents("../private_qwr/admin/001_insert_into_employee.sql");
        $prep_insert_empl = $GLOBALS["_register_admin_"]["connection"]->prepare($sql_insert_empl);
        $prep_insert_empl->bindParam(":full_id", $full_id);
        $prep_insert_empl->bindParam(":address", $address);
        $prep_insert_empl->bindParam(":etat_civil", $etat_civil);
        $prep_insert_empl->bindParam(":admin_id", $full_id);
        $prep_insert_empl->bindParam(":province_id", $prov_id);
        $prep_insert_empl->execute();
        $prep_insert_empl->closeCursor();

        $sql_insert_log = file_get_contents("../private_qwr/admin/001_insert_into_employee.sql");
        $prep_insert_log = $GLOBALS["_register_admin_"]["connection"]->prepare("INSERT INTO login(person_id, role_id, password) VALUES (:full_id, :role_id, :password);");
        $prep_insert_log->bindParam(":full_id", $full_id);
        $prep_insert_log->bindParam(":role_id", $role_id);
        $prep_insert_log->bindParam(":password", $password);
        $prep_insert_log->execute();
        $prep_insert_log->closeCursor();

        $data = array(
            "shortID"=>$short_id,
            "fullID"=>$full_id,
            "password"=>$default_paswword,
            "roleID"=>$role_id,
            "email"=>$email
        );
        return array("ok"=>true, "response"=>true, "data"=>$data);
    }
    catch(Exception $e){
        return array("ok"=>false, "response"=>$e->getMessage());
    }
}