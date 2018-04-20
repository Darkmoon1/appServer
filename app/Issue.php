<?php
    require_once 'Tools.php';

    $session = $_POST['session'];
    session_id($session);
    session_start();
    
    $action = $_POST['action'];
    // $action = 1;
    
    
    if(isset($_SESSION['UID'])){
        if($action == 1){
            issue();
        }elseif($action == 2){
            quitIssue();
        }else{
            $info = new IssueErrorInfo2("参数非法");
            Tools::infoBack($info);
        }
    }
    else{
        $info = new IssueErrorInfo1();
        Tools::infoBack($info);
    }

    function issue(){
        
        if(!isset($_POST['qd'],$_POST['zd'],$_POST['qdName'],$_POST['zdName'],$_POST['type'],$_POST['cf'])){
            $info = new IssueErrorInfo2("参数非法");
            Tools::infoBack($info);
            return;
        }
        $qd = $_POST['qd'];
        $zd = $_POST['zd'];
        $qdName = $_POST['qdName'];
        $zdName = $_POST['zdName'];
        $bz = $_POST['bz'];
        $type = $_POST['type'];
        $cf = $_POST['cf'];
        
        
        // $qd = array('lat'=>39.071510,'lng'=>117.190091);        
        // $zd = array('lat'=>39.071510,'lng'=>117.190010);
        // $bz = "test";
        // $type = 1;
        // $cf = 1522074844;
        $masterUID = $_SESSION['UID'];

        $databaseTools = new databaseTools();
        $database = $databaseTools->databaseInit();
        if($database->select('user_basic','contribution',array(
            'UID'=>$masterUID
        )) < 0){
            $info = new IssueErrorInfo2("贡献值不足");
            Tools::infoBack($info);
            return;
        }

        $qd = json_decode($qd,true);
        $zd = json_decode($zd,true);

        
        $fq = time();
        $key = "".$masterUID.$fq;
        $formID = Particle::timeFromParticle(Particle::generateParticle($key));
        $flag = 0;
        $mapTool = new tencentMapTools();

        $result = $mapTool->getDistance($qd,$zd);
        $result = json_decode($result,true);
        if($result['status']!=0){
            $info = new IssueErrorInfo2("腾讯api服务错误");
            Tools::infoBack($info);
            return;
        }
        //待查数据格式
        $distance = $result['result']['elements'][0]['distance'];

        // $distance = 1000.0;
        $datas = array(
            'formID'=>$formID,
            'masterUID'=>$masterUID,
            'start_gps'=>$qd['lat'].','.$qd['lng'],
            'end_gps'=>$zd['lat'].','.$zd['lng'],
            'qdName'=>$qdName,
            'zdName'=>$zdName,
            'bz'=>$bz,
            'fq'=>$fq,
            'cf'=>$cf,
            'distance'=>$distance,
            'flag'=>$flag,
            'type'=>$type
        );
        // --------------------------------------------------
        //基本表插入

        $result = $database->insert('form_basic',$datas);
        if($result == false){
            $info = new IssueErrorInfo2("服务器错误 请重新发布订单");
            Tools::infoBack($info);
            return; 
        }

        //插入待服务列表
        $geohash = new Geohash();
        $start_geohash =  $geohash->encode($qd['lat'],$qd['lng']);
        $end_geohas = $geohash->encode($zd['lat'],$zd['lng']);
        $datas = array(
            'form_id'=>$formID,
            'masterUID'=>$masterUID,
            'cf'=>$cf,
            'start_geohash'=>$start_geohash,
            'end_geohash'=>$end_geohas
        );
        $result = $database->insert('waiting_server',$datas);

        $info = new IssueErrorInfo0($formID);
        Tools::infoBack($info); 
        
        
    
    }

    function quitIssue(){
        $formID = $_POST['formID'];
        $bz = $_POST['bz'];

        // $formID = '{E7447C5C-F2E0-DEA8-211A-A1341F132F59}';
        // $bz = 'test';

        $databaseTools = new databaseTools();
        $database = $databaseTools->databaseInit();

        $result = $database->select('waiting_server','form_id',array('form_id'=>$formID));
        if(empty($result)){
            $info = new IssueErrorInfo2("该订单不存在或已经结束");
            Tools::infoBack($info); 
        }else{
            //从等待列表中删除
            $result = $database->delete('waiting_server',array('form_id'=>$formID));
            if($result){
                //更新基础表中的flag
                $database->update('form_basic',array('flag'=>5,'bz[+]'=>$bz),array('formID'=>$formID));
                $info = new IssueErrorInfo0($formID);
                Tools::infoBack($info); 
            }
            else{
                $info = new IssueErrorInfo2("服务器错误");
                Tools::infoBack($info); 
            }
        }
    }


?>