<?php
// if(session_status() === PHP_SESSION_NONE){
//     session_start();
// }
// if(!isset($_SESSION["010_10010"])) exit;
// if($_SESSION["010_10010"]!="API_MYAPI") exit;
// if(!isset($_SESSION["1100.101101"])) exit;
// if($_SESSION["1100.101101"]!="scea.system") exit;

// $raw = file_get_contents("php://input");
// $data = json_decode($raw, true);

// if(!$data && !isset($_GET["action"])){
//     echo json_encode(array("ok"=>false, "response"=>null));
//     exit;
// }
// if($data){
//     if(!isset($data["action"]) && !isset($_GET["action"])){
//         echo json_encode(array("ok"=>false, "response"=>null));
//         exit;
//     }
// }


try{
    if(true){
        if(true){
            switch("e"){
                case "e":
                    if(true){
                        include("../private_mec/generale/method.php");
                        include("../private_mec/generale/login.php");
                        $GLOBALS["data"] = array(
                            "user"=>"905338740912",
                            "password"=>"Ngoy0001"
                        );
                        $log = login();
                        
                        echo "<pre>";
                        print_r($log);
                        echo json_encode(page_login());
                        exit;
                    }
            }
        }
    }
}
catch(Exception $e){
    echo $e->getMessage();
}



