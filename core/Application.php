<?php
abstract class Application{
    protected $debug = false;
    protected $request;
    protected $response;
    protected $session;
    protected $db_manager;
    //ログイン画面へのコントローラとアクションを指定する
    protected $login_action = array();

    public function __constract($debug = false){
        $this->setDebugMode($debug);
        $this->initialize();
        $this->configure();
    }

    protected function setDebugMode($debug){
        if($debug){
            $this->debug = true;
            //PHP実行時に発生したエラー・警告内容を、HTML（ブラウザ）上に出力（表示）するかどうかの指定です。
            ini_set('display_errors',1);
            // 全ての PHP エラーを表示する
            error_reporting(-1);
        }else{
            $this->debug = false;
            ini_set('display_errors',0);
        }
    }
    protected function initialize(){
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->db_manager = new DbmManager();
        $this->router = new Router($this->registerRoutes());
    }

    protected function configure(){

    }
    abstract public function getRootDir();

    abstract public function registerRoutes();

    public function isDebugMode(){
        return $this->debug;
    }
    public function getRequest(){
        return $this->request;
    }
    public function getResponse(){
        return $this->response;
    }
    public function getSession(){
        return $this->session;
    }
    public function getDbManager(){
        return $this->db_manager;
    }
    public function getControllerDir(){
        return $this->getRootDir(). '/controllers';
    }
    public function getViewDir(){
        return $this->getRootDir().'/views';
    }
    public function getModelDir(){
        return $this->getRootDir().'/models';
    }
    public function getWebDir(){
        return $this->getRootDir().'/web';
    }

    public function run(){
        try{
            $params = $this->router->resolve($this->requesst->getPathInfo());
            if($params === false){
                throw new HttpNotFoundException('No route found for'.$this->request->getPathInfo());
            }

            $controller = $params['controller'];
            $action = $params['action'];

            $this->runAction($controller,$action,$params);
        }catch (HttpNotFoundException $e){
            $this->render404Page($e);
        }catch(UnauthorizedActionError $e){
            list($controller,$aciton) = $this->login_action;
            $this->runAction($controller,$action);
        }
        $this->response->send();
    }

    protected function render404Page($e){
        $this->response->setStatusCode(404,'Not Found');
        $message = $this->isDebugMode() ? $e->getMessage() : 'Page not found';
        $message = htmlspecialchars($message,ENT_QUOTES,'UTF-8');

        $this->response->setContent(<<<EOF
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404</title>
</head>
<body>
    {$message}
</body>
</html>
EOF
        );
    }

    public function runAction($controller_name,$action, $params = array()){
        $controller_class = ucfirst($controller_name).'Controller';

        $controller = $this->findController($controller_class);
        if($controller_class === false){
            throw new HttpNotFoundException($controller_class.'controller is not found');
        }

        $content->$controller->run($aciton,$params);

        $this->response->setContent($content);
    }

    protected function findController($controller_class){

        if(!class_exists($controller_class)){
            $controller_file = $this->getControllerDir(). '/' . $controller_class.'.php';
            if(!is_readable($controller_file)){
                return false;
            }else{
                require_once $controller_file;

                if(!class_exists($controller_class)){
                    return false;
                }
            }
        }
        //コンストラクタにはApplicationクラス自信を渡す
        return new $controller_class($this);
    }
}

?>