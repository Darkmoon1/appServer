<?php
    require_once 'loginErrorInfo.php';
    require_once 'positionInfo.php';
    require_once 'getUIErrorInfo.php';
    require_once 'getOIErrorInfo.php';
    require_once 'IssueErrorInfo.php';
    require_once 'DealErrorInfo.php';
    require_once 'SearchErrorInfo.php';

    class infoTools extends Tools{
        public $error_msg;
        public $dm_error;
        public function infoTools($error_msg,$dm_error){
            $this->error_msg = $error_msg;
            $this->dm_error = $dm_error;
        }   
    }

    class errorInfo extends infoTools{
        public function errorInfo($error_msg){
            $dm_error = 1;
            parent::infoTools($error_msg,$dm_error);
        }
    }
?>