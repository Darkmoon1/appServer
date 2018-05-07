<?php
    class getUIErrorInfo extends infoTools{

    }
    /**
     * 成功信息对象
     */
    class getUIErrorInfo0 extends getUIErrorInfo{
        public $UID;
        public $phone;
        public $car_number;
        public $nickname;
        public $contribution;
        public $abscontribution;
        public $money;
        public $registTime;
        public function getUIErrorInfo0($data){
            $error_msg = '操作成功';
            $dm_error = 0;
            parent::infoTools($error_msg,$dm_error);
            $this->UID = $data["UID"];
            $this->phone = $data["phone_number"];
            $this->car_number = $data["car_number"];
            $this->nickname = $data["nick_name"];
            $this->contribution = $data["contribution"];
            $this->abscontribution = $data["abs_contribution"];
            $this->money = $data["money"];
            $this->registTime = $data["regist_time"];
        }
    }
    /**
     * session不存在
     * 
     */
    class getUIErrorInfo1 extends getUIErrorInfo{

        public function getUIErrorInfo1(){
            $error_msg = "未登录";
            $dm_error = 1;
            parent::infoTools($error_msg,$dm_error);
        }

    }
    /**
     * 其他错误
     */
    class getUIErrorInfo2 extends getUIErrorInfo{
        public $error_msg;

        public function getUIErrorInfo1($error_msg){
            $this->error_msg =  $error_msg;
            $dm_error = 2;
            parent::infoTools($error_msg,$dm_error);
        }
    }
?>