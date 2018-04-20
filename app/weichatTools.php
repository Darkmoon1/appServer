<?php
    class weichatTools extends Tools{
        public $appid;
        public $secret;
        public $loginAPI;
        // protected $code;
        public $grant_type;

        public function weichatTools(){
            $this->appid = 'wx8b5eb1cda55cc4ef'; 
            $this->grant_type = 'authorization_code';
            $this->secret = '921f1328a46fd694d0ff228224301d6b';
            $this->loginAPI = "https://api.weixin.qq.com/sns/jscode2session";
        }


        public function getOpenidAndSessionkey($code){
            $datas = array(
                'appid'=>$this->appid,
                'secret'=>$this->secret,
                'js_code'=>$code,
                'grant_type'=>$this->grant_type,
                'connect_redirect'=>1
            );
            
            $result = parent::http_request($this->loginAPI,$datas);
            return $result;
        }

    }
?>