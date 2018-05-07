<?php
    require_once 'Tools.php';

    $action = $_POST['action'];
    $session = $_POST['session'];
    session_id($session);
    session_start();

    //未校验用户
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
        $result = $database->select('waiting_server',
            array('form_id'),
            array('form_id'=>$formID)
        ); 
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

                $form = $database->select('form_basic',array(
                    '[>]user_basic'=>array('serverUID'=>'UID')
                ),array('car_number','nick_name','phone_number','qdName','zdName','bz','cf','masterUID','wxformId'),array(
                    'formID'=>$formID
                ));

                $weichatTools = new weichatTools();
                $weichatTools->updateAccessToken();
                $data = array(
                    'keyword1'=>array(
                        'value'=>$form['car_number']
                    ),
                    'keyword2'=>array(
                        'value'=>$form['nick_name']
                    ),
                    'keyword3'=>array(
                        'value'=>$jd
                    ),
                    'keyword4'=>array(
                        'value'=>$form['phone_number']
                    ),
                    'keyword5'=>array(
                        'value'=>$form['qdName'].'>>'.$form['zdName']
                    ),
                    'keyword6'=>array(
                        'value'=>$form['bz']
                    ),
                    'keyword7'=>array(
                        'value'=>$form['cf']
                    ),
                    'keyword8'=>array(
                        'value'=>$formID
                    ),
                );
                $result = $weichatTools->sendMessage($form['masterUID'],$data,$formID,$form['wxformId']);
                if($result['errcode']==0){
                    $info = new DealErrorInfo0("");
					Tools::infoBack($info); 
                }else{
					$info = new DealErrorInfo2(json_encode($result));
                    Tools::infoBack($info); 
                }
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

        $databaseTools = new databaseTools();
        $database = $databaseTools->databaseInit();

        $result = $database->select('form_basic',array('flag', 'masterUID', 'serverUID'),array('formID'=>$formID));

        if(empty($result) || $result[0]['flag'] != 1){
            $info = new DealErrorInfo2("已结束订单无法继续交流");
            Tools::infoBack($info);
            return;
        } else if($result[0]['masterUID'] != $UID && $result[0]['serverUID'] != $UID) {
            $info = new DealErrorInfo2("您无权操作");
            Tools::infoBack($info);
            return;
        }

        $database->insert('communication',array(
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
        $UID = $_SESSION['UID'];

        $databaseTools = new databaseTools();
        $database = $databaseTools->databaseInit();

        $result = $database->select('form_basic',array('flag', 'masterUID', 'serverUID'),array('formID'=>$formID));
        if(empty($result)){
            $info = new DealErrorInfo2("该订单不存在");
            Tools::infoBack($info);
            return;
        } else if($result[0]['masterUID'] != $UID && $result[0]['serverUID'] != $UID) {
            $info = new DealErrorInfo2("您无权操作");
            Tools::infoBack($info);
            return;
        }

        $result = $database->select('communication',array('AUID(UID)','content','time_stamp'),array(
            "AND"=>array(
            'form_id'=>$formID,
            'time_stamp[>]'=>$time),
            "ORDER"=>'time_stamp'
        ));
        
        $info = new DealErrorInfo0($result);
        Tools::infoBack($info);
    }
?>