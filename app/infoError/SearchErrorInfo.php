<?php
    class SearchErrorInfo extends infoTools{
        
    }
    class SearchErrorInfo0 extends SearchErrorInfo{
        public $forms;
        public function SearchErrorInfo0($forms){
            $error_msg = '操作成功';
            $dm_error = 0;
            parent::infoTools($error_msg,$dm_error);
            $this->forms = $forms;
        }
    }

    class SearchErrorInfo1 extends SearchErrorInfo{
        public function SearchErrorInfo1(){
            $error_msg = "未登录";
            $dm_error = 1;
            parent::infoTools($error_msg,$dm_error);
        }
    }

    class SearchErrorInfo2 extends SearchErrorInfo{
        public function SearchErrorInfo2($error_msg){
            $this->error_msg =  $error_msg;
            $dm_error = 2;
            parent::infoTools($error_msg,$dm_error);
        }
    }
?>