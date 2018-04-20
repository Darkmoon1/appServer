<?php
    require_once 'Tools.php';
    $session = $_POST['session'];
    session_id($session);
    session_start();

    if(isset($_SESSION['UID'])){
        getDetailedInfo();
    }
    else{
        $info = new SearchErrorInfo1();
        Tools::infoBack($info);
    }

    function getDetailedInfo(){
        $formID = $_POST['formID'];
        $databaseTools = new databaseTools();
        $database = $databaseTools->databaseInit();

        $result = $database->select('form_basic',array(
            'formID','masterUID','serverUID','start_gps','end_gps','qdName','zdName','bz','fq','cf','jd','js','distance','flag','type'
        ),array(
            'formID' => $formID
        ));

        if(empty($result)){
            $info = new SearchErrorInfo2("订单不存在");
            Tools::infoBack($info); 
        }else{
            $info = new SearchErrorInfo0($result[0]);
            Tools::infoBack($info);
        }
    }

?>