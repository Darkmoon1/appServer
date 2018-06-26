<?php
    class tencentMapTools extends Tools{
        protected $mode;
        protected $output;
        protected $key;
        protected $getDistanceAPI;
        protected $type;

        public function tencentMapTools(){
            // $this->key = "SDIBZ-3KIRK-ZQLJY-AHZ5N-FXRV3-CVBX2";  //腾讯key
            $this->key = "39bda44a349f1d5d712f2f9ad2d10c03";    //高德key
            $this->mode = "driving";
            $this->type = 1;
            $this->output = "json";
            // $this->getDistanceAPI = "http://apis.map.qq.com/ws/distance/v1?"; //腾讯
            $this->getDistanceAPI = "http://restapi.amap.com/v3/distance?";
        }

        public function getDistance($from,$to){
            $from = $from['lng'].','.$from['lat'];
            $to = $to['lng'].','.$to['lat'];
            $datas = array(
                'type'=>$this->type,
                'origins'=>$from,
                'destination'=>$to,
                'output'=>$this->output,
                'key'=>$this->key
            );
            $datas = http_build_query($datas);
            $result = parent::http_request($this->getDistanceAPI.$datas);
            return $result;
        }
        
    }
?>