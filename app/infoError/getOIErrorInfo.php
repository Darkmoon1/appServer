<?php
    class getOIErrorInfo extends infoTools{

    }
    /**
     * 成功信息
     */
    class getOIErrorInfo0 extends getOIErrorInfo{
        public $nickName;
        public $contribution;
        public function getOIErrorInfo0($data){
            $error_msg = '操作成功';
            $dm_error = 0;
            parent::infoTools($error_msg,$dm_error);
            $this->nickName = $data['nick_name'];
            $this->contribution = $data['contribution'];
        }
    }
    /**
     * 错误信息
     */
    class getOIErrorInfo1 extends getOIErrorInfo{
        public function getOIErrorInfo1(){
            $error_msg = "未登录";
            $dm_error = 1;
            parent::infoTools($error_msg,$dm_error);
        }
    }

    class getOIErrorInfo2 extends getOIErrorInfo{
        public function getOIErrorInfo2($error_msg){
            $this->error_msg =  $error_msg;
            $dm_error = 2;
            parent::infoTools($error_msg,$dm_error);
        }
    }
?>