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
        return;
    }

    function getDetailedInfo(){
        $formID = $_POST['formID'];
        $databaseTools = new databaseTools();
        $database = $databaseTools->databaseInit();

        $result = $database->select('form_basic',
            array(
                '[>]user_basic (master)'=>["masterUID"=>'UID'],
                '[>]user_basic (server)'=>["serverUID"=>'UID']
            ),
            array(
            'formID','masterUID','serverUID','start_gps','end_gps','qdName','zdName','bz','fq','cf','jd','js','distance','flag','type', 'master.nick_name(master_nickname)', 'server.nick_name(server_nickname)', 'master.phone_number(master_phone)', 'server.phone_number(server_phone)', 'server.car_number'
            ),
            array(
            'formID' => $formID
        ));

        if(empty($result)){
            $info = new SearchErrorInfo2("订单不存在");
            Tools::infoBack($info); 
        }else{
            if($result[0]['flag']==0||$result[0]['masterUID']==$_SESSION['UID']||$_SESSION['UID']==$result[0]['serverUID']){
                $info = new SearchErrorInfo0($result[0]);
                Tools::infoBack($info);
            }else{
                $info = new SearchErrorInfo2("该订单已被接单");
                Tools::infoBack($info);
                return;
            }
        }
    }

?>