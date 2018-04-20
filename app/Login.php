<?php
    require_once 'Tools.php';
    //指定json解析
    //header("Content-Type:application/json;charset=utf-8");
    //测试
    //header('Content-Type:text/html; charset=utf-8');
  

    $action = $_POST['action'];

    // $action = 1;
    // $code =  '003Wp6rX1uCIKS0QDeqX1F5orX1Wp6rU';

    // echo('success<\br>');
    // echo(time());

    // $datas = $database->select("user_basic","*");

    // echo($datas);
    if($action==1&&isset($_POST['code'])){
        login($_POST['code']);
    }
    else if($action==2&&isset($_POST['code'])){
        regist($_POST['code']);
    }
    else{
        $info = new loginErrorInfo1("提交参数有错误");
        Tools::infoBack($info);
    }
    // ----------------------------------------------------------------------------------------------------

    function login($code){
        $databaseTools = new databaseTools();
        $database = $databaseTools->databaseInit();
        //根据code获取微信的openid
        $weichatTool = new weichatTools();
        $i = 0;
        do{
            $i = $i + 1;
            $result = $weichatTool->getOpenidAndSessionkey($code);
            $obj = json_decode($result,true);
        }while(empty($obj)&&$i<10);

   

        if(array_key_exists('openid', $obj)){
            $openid = $obj['openid'];
            $session_key = $obj['session_key'];            
        }else{
            $info = new loginErrorInfo1("weichat login code timeout");
            Tools::infoBack($info);
            return;
        }

        //测试数据
        // $openid = 'ooi7b4oahDZ65w9i5vaS2PUv_f10';
        
        //查询数据库进行对比
        $columns = array('UID','nick_name','contribution');
        $where = array('UID'=>$openid);
        $result = $database->select('user_basic',$columns,$where);
        //debug
        // Tools::show($result);

        if(empty($result)){
            $info = new loginErrorInfo2();
            //需要前端进行解码
            Tools::infoBack($info);
        }
        elseif(sizeof($result)){
            $result = $result[0];

            session_start();

            if(isset($_SESSION['UID'])&&$_SESSION['UID']==$result['UID']){
                $info = new loginErrorInfo0($result['UID'],session_id(),$result['nick_name'],$result['contribution']);
                Tools::infoBack($info);
            }
            else{
                $_SESSION['UID']=$result['UID'];
                $info = new loginErrorInfo0($result['UID'],session_id(),$result['nick_name'],$result['contribution']);
                Tools::infoBack($info);
            }
        }
        else{
            $info = new loginErrorInfo1("服务器错误");
            Tools::infoBack($info);
        }
    }
    // ----------------------------------------------------------------------------------------------------


    function regist($code){
        $cp = $_POST['cp'];
        $phone = $_POST['phone'];
        $nickname = $_POST['nickname'];

        $databaseTools = new databaseTools();
        $database = $databaseTools->databaseInit();
        //根据code获取微信的openid
        $weichatTool = new weichatTools();
        //是否要加dowhile循环
        $i = 0;
        do{
            $i = $i + 1;
            $result = $weichatTool->getOpenidAndSessionkey($code);
            $obj = json_decode($result,true);
        }while(empty($obj)&&$i<10);

        if(array_key_exists('openid', $obj)){
            $openid = $obj['openid'];
            $session_key = $obj['session_key'];            
        }else{
            $info = new loginErrorInfo1("weichat login code timeout");
            Tools::infoBack($info);
            return;
        }

        // $openid = 'fhkhf';
        // $cp = "ABSLKAHJ";
        // $phone = "1237218947";
        // $nickname = "baba";

        $columns = array('UID');
        $where = array('UID'=>$openid);
        $result = $database->select('user_basic',$columns,$where);
        
        if(empty($result)){
            $database->insert("user_basic",array(
                "UID"=>"$openid",
                "car_number"=>"$cp",
                "phone_number"=>"$phone",
                "nick_name"=>"$nickname",
                "contribution"=>0.0,
                "money"=>0.0,
                "regist_time"=>time()
            ));

            session_start();

            $_SESSION['UID']=$openid;
            $info = new loginErrorInfo0($openid,session_id(),$nickname,0.0);
            Tools::infoBack($info);
        }
        else{
            $info = new loginErrorInfo1("用户已经存在");
            Tools::infoBack($info);
        }


    }
?>