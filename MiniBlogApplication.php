<?php

class MiniBlogApplication extends Application{
    
    protected $login_action = array("account","signin");
    
    //ルートディレクトリを登録する
    public function getRootDir(){
        return dirname(__FILE__);
    }
    
    //ルーティング定義を登録する
    protected function registerRoutes(){
        return array(
            '/' => array('controller' => 'status', 'action' =>'index'),
            '/status/post' =>array('controller' => 'status', 'action' => 'post'),
            '/user/:user_name' => array('controller' => 'status' , 'action' => 'user'),
            '/user/:user_name/status/:id' => array('controller' => 'status' , 'action'=> 'show'),
            '/account' => array('controller' => 'account', 'action' => 'index'),
            '/account/:action'
                => array('controller' => 'account'),
            '/follow' => array('controller' => 'account', 'action' => 'follow'),
        );
    }
    
    //アプリケーションの設定部分を登録　ここではデータベースの設定
    protected function configure(){
        $this->db_manager->connect('master',array(
            'dsn' => 'mysql:dbname=mini_blog_db;host=localhost',
            'user' => 'root',
            'password' => '',
        ));
    }
}

?>