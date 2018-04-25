<?php
    require_once 'Tools.php';
    require_once 'Particle.php';
    // $url = 'http://localhost/app/Login.php';
    // $datas = array(
    //     'action'=>2,
    //     'code'=>'123124'
    // );
    // $result = Tools::sendGet($url,$datas);
    // echo($result);
    // $result = Tools::create_guid("yhy");

    // $UID = array('yhy','fff');
    // $databaseTools = new databaseTools();
    // $database = $databaseTools->databaseInit();
    // $columns = array('UID','car_number','phone_number','nick_name','contribution','money','regist_time');
    // $where = array('UID'=>$UID);
    // $result = $database->select('user_basic',$columns,$where);
    // Tools::infoBack($result);
    // $geohash = new Geohash();
    // $n_latitude  =  34.236080797698;  
    // $n_longitude = 109.0145193757; 
    // $n_geohash = $geohash->encode($n_latitude,$n_longitude);  
    $key = 'yhy'.time();
    echo(Particle::timeFromParticle(Particle::generateParticle($key)));
    //根据fq时间倒序返回
    //过滤自己的订单 表加入masterUID 外键over

    //消息模板
    //导出view->csv

    //安全性校验 判断UID与订单是否匹配getuserInfo getdetaildeinfo dissent  over
    //订单UID生成函数修改数字 over

?>