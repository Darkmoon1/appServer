<?php
        require_once 'Tools.php';

        $version = $_POST['version'];
        $session = $_POST['session'];
        session_id($session);
        session_start();
    
        if(isset($_SESSION['UID'],$_POST['version'])){
            getCustomPositions($version);
        }
        else{
            $info = new positionInfo1("用户未登录");
            Tools::infoBack($info);
        }

        function getCustomPositions($version){
            if($version>=1.0){
                $info = new positionInfo1("已经是最新版本");
                Tools::infoBack($info);
                return;
            }

            $databaseTools = new databaseTools();
            $database = $databaseTools->databaseInit();
            $columns = array('GPS','position_name','position_type');
            $where = array("position_type[>]"=>0);
            $result = $database->select('position_cache',$columns,$where);
            $info = new positionInfo0($result);
            Tools::infoBack($info);
        }

?>