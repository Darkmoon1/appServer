<?php
    class IssueErrorInfo extends infoTools{

    }

    class IssueErrorInfo0 extends IssueErrorInfo{
        public $formID;
        public function IssueErrorInfo0($formID){
            $error_msg = '操作成功';
            $dm_error = 0;
            parent::infoTools($error_msg,$dm_error);
            $this->formID = $formID;
        }
    }

    class IssueErrorInfo1 extends IssueErrorInfo{
        public function IssueErrorInfo1(){
            $error_msg = "未登录";
            $dm_error = 1;
            parent::infoTools($error_msg,$dm_error);
        }
    }

    class IssueErrorInfo2 extends IssueErrorInfo{
        public function IssueErrorInfo2($error_msg){
            $this->error_msg =  $error_msg;
            $dm_error = 2;
            parent::infoTools($error_msg,$dm_error);
        }
    }
?>