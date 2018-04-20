<?php
    require_once 'Tools.php';
    $session = $_POST['session'];
    session_id($session);
    session_start();

    if(isset($_SESSION['UID'])){
        search();
    }
    else{
        $info = new SearchErrorInfo1();
        Tools::infoBack($info);
    }
    
    function search(){
        
        $qd = $_POST['qd'];
        $cf = $_POST['cf'];
        $qd = json_decode($qd,true);
        $cf = (int)$cf;
        $tar_lng = $qd['lng'];
        $tar_lat = $qd['lat'];

        // $tar_lng = 117.190091;
        // $tar_lat = 39.071510;
        //$cf = 1523637800;
             

        $geohash=new Geohash;
        $n_geohash = $geohash->encode($tar_lat,$tar_lng);
        $n = 3;  
        $like_geohash = substr($n_geohash, 0, $n);
        
        $databaseTools = new databaseTools();
        $database = $databaseTools->databaseInit();
        //过滤自己的订单 表加入masterUID 外键
        
        $result = $database->select('waiting_server','form_id',array(
            "AND"=>array(
                'start_geohash[~]'=>$like_geohash."%",
                'cf[<>]'=>array($cf-1800,$cf+1800)
            )
        ));
        if(empty($result)){
            $info = new SearchErrorInfo0("");
            Tools::infoBack($info);
        }else{
            $UID = array();
            foreach($result as $var){
                array_push($UID,$var);
            }
            $result = $database->select('form_basic',array('formID','qdName','zdName','cf','type','distance'),array(
                'formID'=>$UID
            ));

            $info = new SearchErrorInfo0($result);
            Tools::infoBack($info);
        }

    }
    
?>