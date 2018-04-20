<?php
        require_once 'Tools.php';
        $session = $_POST['session'];
        session_id($session);
        session_start();
        
        if(isset($_SESSION['UID'],$_POST['uid'])){
            getOtherInfo($_POST['uid']);
        }
        else{
            $info = new getOIErrorInfo1();
            Tools::infoBack($info);
        }


        function getOtherInfo($UID){
            $databaseTools = new databaseTools();
            $database = $databaseTools->databaseInit();
            $columns = array('nick_name','contribution');
            $where = array('UID'=>$UID);
            $result = $database->select('user_basic',$columns,$where);
            if(empty($result)){
                $info = new getOIErrorInfo2("没有该用户");
                Tools::infoBack($info);
            }
            elseif(sizeof($result)){
                $result = $result[0];
                $info = new getOIErrorInfo0($result);
                Tools::infoBack($info);
            }
            else{
                $info = new getOIErrorInfo2("服务器错误");
                Tools::infoBack($info);
            }
        }
?>  