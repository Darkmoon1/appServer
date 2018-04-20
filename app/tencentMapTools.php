<?php
    class tencentMapTools extends Tools{
        protected $mode;
        // protected $from;
        // protected $to;
        protected $output;
        protected $key;
        protected $getDistanceAPI;

        public function tencentMapTools(){
            $this->key = "SDIBZ-3KIRK-ZQLJY-AHZ5N-FXRV3-CVBX2";
            $this->mode = "driving";
            $this->output = "json";
            $this->getDistanceAPI = "http://apis.map.qq.com/ws/distance/v1?";
        }

        public function getDistance($from,$to){
            $from = $from['lat'].','.$from['lng'];
            $to = $to['lat'].','.$to['lng'];
            $datas = array(
                'mode'=>$this->mode,
                'from'=>$from,
                'to'=>$to,
                'output'=>$this->output,
                'key'=>$this->key
            );
            $datas = http_build_query($datas);
            $result = parent::http_request($this->getDistanceAPI.$datas);
            return $result;
        }
        
    }
?>