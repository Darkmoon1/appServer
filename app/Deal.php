<?php
    require_once 'Tools.php';

    $action = $_POST['action'];
    $session = $_POST['session'];
    session_id($session);
    session_start();

    if(isset($_SESSION['UID'])){
        if($action == 1){
            deal();
        }elseif($action == 2){
            sendInfo();
        }elseif($action == 3){
            receiveInfo();
        }elseif($action == 4){
            dealFinish();
        }else{
            $info = new DealErrorInfo2("参数非法");
            Tools::infoBack($info);
        }
    }
    else{
        $info = new DealErrorInfo2();
        Tools::infoBack($info);
    }    

    function deal(){
        $formID = $_POST['formID'];

        // $formID = '{E97F3640-1255-7713-CFB9-7E30FFE82091}';

        $databaseTools = new databaseTools();
        $database = $databaseTools->databaseInit();
        $result = $database->select('waiting_server','form_id',array('form_id'=>$formID)); 
        if(empty($result)){
            $info = new DealErrorInfo2("该订单不存在或已经被接单");
            Tools::infoBack($info); 
        }else{
            //从等待列表中删除
            $result = $database->delete('waiting_server',array('form_id'=>$formID));
            if($result){
                $jd = time();
                $serverUID = $_SESSION['UID'];
                //更新基础表中的flag 0未接单 1已接单 2等待结算 3结算完成 
                $database->update('form_basic',array('flag'=>1,'jd'=>$jd,'serverUID'=>$serverUID),array('formID'=>$formID));
                $info = new DealErrorInfo0("");
                Tools::infoBack($info); 
            }
            else{
                $info = new DealErrorInfo2("服务器错误");
                Tools::infoBack($info); 
            }
        }       
    }


    function dealFinish(){
        $formID = $_POST['formID'];
        $waitingDealFinishTime = 300;

        $databaseTools = new databaseTools();
        $database = $databaseTools->databaseInit();
        $result = $database->select('form_basic',array('flag','fq'),array('formID'=>$formID));
        if(empty($result)||$result[0]['flag']!=1){
            $info = new DealErrorInfo2("该订单不存在或已经结算");
            Tools::infoBack($info); 
        }else{
            //加入等待结算表
            $fq = $result[0]['fq'];
            $expected_settlement_time = time()+$waitingDealFinishTime;
            $result = $database->insert('waiting_finish',array(
                'form_id'=>$formID,
                'expected_settlement_time'=>$expected_settlement_time
            ));
            // if($result){
                $js = time();
                //更新基础表中的flag 2为等待结算
                $database->update('form_basic',array('flag'=>2,'js'=>$js),array('formID'=>$formID));
                $info = new DealErrorInfo0(array(
                    'formID'=>$formID,
                    'fq'=>$fq,
                    'js'=>$js
                ));
                Tools::infoBack($info); 
            // }
            // else{
            //     $info = new DealErrorInfo2("服务器错误");
            //     Tools::infoBack($info); 
            // }
        } 
    }

    function sendInfo(){
        $formID = $_POST['formID'];
        $content = $_POST['info'];
        $time = time();
        $UID = $_SESSION['UID'];

        // $UID = 'yhy';

        $databaseTools = new databaseTools();
        $database = $databaseTools->databaseInit();
        $result = $database->insert('communication',array(
                'comID'=>$formID.$time,
                'form_id'=>$formID,
                'AUID'=>$UID,
                'content'=>$content,
                'time_stamp'=>$time
            ));

        $info = new DealErrorInfo0("");
        Tools::infoBack($info);

    }

    function receiveInfo(){
        $formID = $_POST['formID'];
        $time = $_POST['lastTimeStamp'];
        // $UID = $_SESSION['UID'];

        $databaseTools = new databaseTools();
        $database = $databaseTools->databaseInit();
        $result = $database->select('communication',array('AUID','content','time_stamp'),array("AND"=>array(
            'form_id'=>$formID,
            'time_stamp[>=]'=>$time
        )));
        
        $info = new DealErrorInfo0($result);
        Tools::infoBack($info);
    }
?>