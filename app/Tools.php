<?php
    require_once 'medoo.php';
    require_once 'geohashTools.php';
    require_once 'weichatTools.php';
    require_once 'tencentMapTools.php';
    require_once 'databaseTools.php';
    require_once 'infoError/infoTools.php';   
    require_once 'Particle.php';
    class Tools{
        public function Tools(){

        }
        //post方法参数模型
        // $post_data = array(
        //     'username' => 'stclair2201',
        //     'password' => 'handan'
        //   );
        // send_post('http://www.jb51.net', $post_data);
        public static function sendPost($url,$datas){
            $datas = http_build_query($datas);
            $options = array(
                'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $datas,
                'timeout' => 15 * 60 // 超时时间（单位:s）
                )
            );
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            return $result;
        }

        public static function sendGet($url,$datas){
            $datas = http_build_query($datas);
            $options = array(
                'http' => array(
                'method' => 'GET',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $datas,
                'timeout' => 15 * 60 // 超时时间（单位:s）
                )
            );
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            return $result;
        }
        /**
            * 通用CURL请求
         * @param $url  需要请求的url
         * @param null $data
         * return mixed 返回值 json格式的数据
         */
        public function http_request($url, $data = null)
        {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            if (!empty($data)) {
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $info = curl_exec($curl);
            curl_close($curl);
            return $info;
        }
        //dubug信息方法
        public static function show($datas){
            foreach($datas as $data){
                echo($data['UID']." ".$data['nick_name']." ".$data['contribution']);
            }
        }
        //返回json信息
        public static function infoBack($info){
            $info = json_encode($info);
            echo($info); 
        }
        
        //生成UID 方法
        public static function create_guid($namespace = '') {   
            static $guid = '';
            $uid = uniqid("", true);
            $data = $namespace;
            $data .= $_SERVER['REQUEST_TIME'];
            $data .= $_SERVER['HTTP_USER_AGENT'];
            $data .= $_SERVER['SERVER_ADDR'];
            $data .= $_SERVER['SERVER_PORT'];
            $data .= $_SERVER['REMOTE_ADDR'];
            $data .= $_SERVER['REMOTE_PORT'];
            $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
            $guid = '{' .  
                substr($hash, 0, 8) . 
                '-' .
                substr($hash, 8, 4) .
                '-' .
                substr($hash, 12, 4) .
                '-' .
                substr($hash, 16, 4) .
                '-' .
                substr($hash, 20, 12) .
                '}';
            return $guid;
        }
    }
?>