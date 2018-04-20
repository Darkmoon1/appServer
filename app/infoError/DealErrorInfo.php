<?php
    class DealErrorInfo extends infoTools{

    }

    class DealErrorInfo0 extends DealErrorInfo{
        public $datas;
        public function DealErrorInfo0($datas){
            $error_msg = '操作成功';
            $dm_error = 0;
            parent::infoTools($error_msg,$dm_error);
            $this->datas = $datas;
        }
    }

    class DealErrorInfo1 extends DealErrorInfo{
        public function DealErrorInfo1(){
            $error_msg = "未登录";
            $dm_error = 1;
            parent::infoTools($error_msg,$dm_error);
        }
    }

    class DealErrorInfo2 extends DealErrorInfo{
        public function DealErrorInfo2($error_msg){
            $this->error_msg =  $error_msg;
            $dm_error = 2;
            parent::infoTools($error_msg,$dm_error);
        }
    }
?>