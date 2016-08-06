<?php 
use Phalcon\Session\Adapter\Files as Session;
use Phalcon\Dispatcher;

try {
    //���������� ����������
    $loader = new Phalcon\Loader();
    $loader->registerDirs(array(
        '../app/controllers/',
        '../app/models/',
        '../app/plugins'
        ))->register();
        
    //�������� DI
    $di = new Phalcon\DI\FactoryDefault();
    
    //��������� ���������� View
    $di->set('view', function() {
        $view = new Phalcon\Mvc\View();
        $view->registerEngines(array(
                '.phtml' => 'voltService'
            ));
        $view->setViewsDir('../app/views/');
        return $view;
    });
    
    //��������� Volt
    $di->set('voltService', function ($view, $di) {
        $volt = new Phalcon\Mvc\View\Engine\Volt($view, $di);
        $volt->setOptions(array(
                'compiledPath' => '../app/views/volt-compiled/'
            ));
        return $volt;
    });
    
    //��������� url
    $di->set('url', function() {
        $url = new Phalcon\Mvc\Url;
        $url->setBaseUri("/");
        return $url;
    });
    
    $di->set('session', function() {
        $session = new Session();
        $session->start();
        return $session;
    });
        
    //�������� ����������
    $di->set('dispatcher', function() {
        $eventsManager = new Phalcon\Events\Manager;
        $eventsManager->attach('dispatch:beforeExecuteRoute', new SecurityPlugin());
        $eventsManager->attach('dispatch:beforeException', new NotFoundPlugin());
        $dispatcher = new Phalcon\Mvc\Dispatcher;
        $dispatcher->setEventsManager($eventsManager);
        return $dispatcher;
    });          
           
    //��������� ���������� � ��
    $di->set('db', function() {
        return new Phalcon\Db\Adapter\Pdo\Mysql(array(
            'host' => 'localhost',
            'username' => 'root',
            'password' => '',
            'dbname' => 'phalcon'));
    });
    
    //��������� ��������
    $application = new Phalcon\Mvc\Application($di);
    echo $application->handle()->getContent();
} catch (Phalcon\Exception $e) {
    echo "PhalconExceprion: ", $e->getMessage();    
}