<?php
    require_once 'Tools.php';
    $session = $_POST['session'];
    session_id($session);
    session_start();

    if(isset($_SESSION['UID'])){
        dissent();
    }
    else{
        $info = new errorInfo("未登录");
        Tools::infoBack($info);
        return;
    }

    function dissent(){
        $formID = $_POST['formID'];
        $bz = $_POST['bz'];
        $databaseTools = new databaseTools();
        $database = $databaseTools->databaseInit();

        $result = $database->select('waiting_finish',array(
            '[>]user_basic'=>array('form_id'=>'formID')
        ),array('form_id','masterUID','serverUID'),array('form_id'=>$formID));

        if(empty($result)){
            $info = new errorInfo("订单未结算或不存在");
            Tools::infoBack($info);
        }else{
            $result = $result[0];
            if($result['masterUID']!=$_SESSION['UID']&&$_SESSION['UID']!=$result['serverUID']){
                $info = new errorInfo("该用户没有权限");
                Tools::infoBack($info);
                return;
            }
            $database->insert('waiting_judge',array('form_id'=>$formID,'bz'=>$bz));
            $database->update('form_basic',array('flag'=>4),array('formID'=>$formID));
            $database->delete('waiting_finish',array('form_id'=>$formID));
            $info = new infoTools("操作成功",0);
            Tools::infoBack($info);
        }
    }
?>