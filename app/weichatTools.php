<?php
    require_once  'Tools.php';
    class weichatTools extends Tools{
        public $appid;
        public $secret;
        public $loginAPI;
        // protected $code;
        public $grant_type;
        public $access_token;
        public $exptime = 0;
        public $template_id;

        public function weichatTools(){
            $this->appid = 'wx8b5eb1cda55cc4ef'; 
            $this->grant_type = 'authorization_code';
            $this->secret = '921f1328a46fd694d0ff228224301d6b';
            $this->loginAPI = "https://api.weixin.qq.com/sns/jscode2session";
            $this->template_id = '30pSR7S7OBBH1GW9F0e4t2A-Q_6xEIWKSgzY4aKkVDw';
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

        public function updateAccessToken(){
            $result = parent::http_request('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx8b5eb1cda55cc4ef&secret=921f1328a46fd694d0ff228224301d6b',null);
            $obj = json_decode($result,true);
            $this->access_token = $obj['access_token'];
            if($this->access_token != null)
            {
                $this->exptime = time() + $obj['expires_in'];
            }
        }

        public function sendMessage($uid, $data, $formId, $wxformId){

            $i = 0;
            while($this->exptime < time() && $i<10){
                $i = $i + 1;
                $this->updateAccessToken();
            }
            if ($this->exptime < time())
            {
                return;
            }

            $datas = array(
                'touser'=>$uid,
                'template_id'=>$this->template_id,
                'page'=>'pages/orderDetail/orderDetail?formID='.$formId,
                'form_id'=>$wxformId,
                'data'=>$data
            );
            $result = parent::http_request('https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$this->access_token,json_encode($datas));
            return $result;
        }
    }
/*
$data = array(
    'keyword1'=>array(
        'value'=>'123'
    ),
    'keyword2'=>array(
        'value'=>'wyc'
    ),
    'keyword3'=>array(
        'value'=>'jd'
    ),
    'keyword4'=>array(
        'value'=>'123456'
    ),
    'keyword5'=>array(
        'value'=>'aaaa'
    ),
    'keyword6'=>array(
        'value'=>'bnbbbb'
    ),
    'keyword7'=>array(
        'value'=>'dddddd'
    ),
    'keyword8'=>array(
        'value'=>'384107096365'
    ),
);
$wechatTool = new weichatTools();
$wechatTool->sendMessage('ooi7b4ksZH8cDAkCypoj78YEDy6U', $data, '384107096365', '1525690340307');
*/
?>