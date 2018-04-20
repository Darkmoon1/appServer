<?php
    require_once 'Tools.php';
    
    $session = $_POST['session'];
    session_id($session);
    session_start();

    if(isset($_SESSION['UID'])){
       getForms();
    }
    else{
        $info = new SearchErrorInfo1();
        Tools::infoBack($info);
    }

    function getForms(){
        $UID = $_SESSION['UID'];

        // $UID = 'ooi7b4oahDZ65w9i5vaS2PUv_f10';

        $databaseTools = new databaseTools();
        $database = $databaseTools->databaseInit();

        $result = $database->select('form_basic',array('formID','qdName','zdName','js','flag','distance'),array(
            "OR"=>array(
                'masterUID'=>$UID,
                'serverUID'=>$UID
            )
        ));
        //根据fq时间倒序返回
        
        $info = new SearchErrorInfo0($result);
        Tools::infoBack($info);
    }

?>