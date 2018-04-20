<?php
    class databaseTools extends Tools{
        protected $database;

        public function databaseTools(){

        }

        public function databaseInit(){
            //medoo对象创建
            $database = new medoo(array(
            // 必须配置项
            'database_type' => 'mysql',
            'database_name' => 'app',
            'server' => 'localhost',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
                
            // 可选参数
            //'port' => 3306,
                
            // 可选，定义表的前缀
            //'prefix' => 'PREFIX_',
                
            // 连接参数扩展, 更多参考 http://www.php.net/manual/en/pdo.setattribute.php
            'option' => array(
                PDO::ATTR_CASE => PDO::CASE_NATURAL
            )
            ));
            return $database;
        }
    }


?>