<?php
    class positionInfo extends infoTools{
        public $forms;
        public $version;
        public function positionInfo($forms,$error_msg,$dm_error,$version){
            // $error_msg = '操作成功';
            // $dm_error = 0;
            parent::infoTools($error_msg,$dm_error);
            $this->forms = $forms;
            $this->version = $version;
        }
    }

    class positionInfo0 extends positionInfo{
        public function positionInfo0($forms){
            $error_msg = '操作成功';
            $dm_error = 0;
            parent::positionInfo($forms,$error_msg,$dm_error,1.0);
        }
    }

    class positionInfo1 extends positionInfo{
        public function positionInfo1($error_msg){
            $dm_error = 1;
            parent::positionInfo("",$error_msg,$dm_error,1.0);
        }
    }

?>