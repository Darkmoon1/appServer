<?php
    //登陆信息类0为登陆成功 1为服务器错误登陆失败 2为不存在用户需要登录信息
    class loginErrorInfo extends infoTools{
        
    }
    /**
     * 0为登陆成功
     */
    class loginErrorInfo0 extends loginErrorInfo{
        public $session;
        public $nickname;
        public $contribution;
        public $UID;
        public function loginErrorInfo0($UID,$session,$nickname,$contribution){
            $error_msg = '操作成功';
            $dm_error = 0;
            parent::infoTools($error_msg,$dm_error);
            $this->session = $session;
            $this->nickname = $nickname;
            $this->contribution = $contribution;
            $this->UID = $UID;
        }
    }
    /**
     * 服务器错误
     */
    class loginErrorInfo1 extends loginErrorInfo{
        public function loginErrorInfo1($error_msg){
            $this->error_msg = $error_msg;
            $dm_error = 1;
            parent::infoTools($error_msg,$dm_error);
        }
    }
    /**
     * 不存在用户需要登录信息
     */
    class loginErrorInfo2 extends loginErrorInfo{
        public function loginErrorInfo2(){
            $error_msg = '该用户不存在';
            $dm_error = 2;
            parent::infoTools($error_msg,$dm_error);
        }
    }
?>