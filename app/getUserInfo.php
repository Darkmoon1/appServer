<?php
    require_once 'Tools.php';
    $session = $_POST['session'];
    session_id($session);
    session_start();

    if(isset($_SESSION['UID'])){
        getUserInfo();
    }
    else{
        $info = new getUIErrorInfo1();
        Tools::infoBack($info);
    }

    // "UID"=>"$openid",
    // "car_number"=>"$cp",
    // "phone_number"=>"$phone",
    // "nick_name"=>"$nickname",
    // "contribution"=>0.0,
    // "money"=>0.0,
    // "regist_time"=>time()

    function getUserInfo(){
        $UID = $_SESSION['UID'];
        // $UID = 'yhy';
        $databaseTools = new databaseTools();
        $database = $databaseTools->databaseInit();
        $columns = array('UID','car_number','phone_number','nick_name','contribution','money','regist_time','abs_contribution');
        $where = array('UID'=>$UID);
        $result = $database->select('user_basic',$columns, $where);
        if(empty($result)){
            $info = new getUIErrorInfo2("没有该用户");
            Tools::infoBack($info);
        }
        elseif(sizeof($result)){
            $result = $result[0];
            $info = new getUIErrorInfo0($result);
            Tools::infoBack($info);
        }
        else{
            $info = new getUIErrorInfo2("服务器错误");
            Tools::infoBack($info);
        }
    }

?>