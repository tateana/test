<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature;
use Zend\EventManager\EventInterface;

class Module implements Feature\ConfigProviderInterface,
                        Feature\AutoloaderProviderInterface,
                        Feature\BootstrapListenerInterface
{
    public function onBootstrap(EventInterface $event)
    {
    	$app = $event->getApplication();
        $eventManager        = $app->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $logApp = $app->getServiceManager()->get('Log\App');
        register_shutdown_function(array($this, 'onShutdown'), $logApp);
        
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onError'));
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_RENDER_ERROR, array($this, 'onError'));
    }

    /**
     * @param Zend\Log\Logger $logger
     */
    public function onShutdown($logger) {
    	$error = error_get_last();

    	if($error) {
    		$logger->emerg($error['message'], $error);
    	}
    }
    
    /**
    * @param Zend\Mvc\MvcEvent $e
    */
    public function onError($e)
    {
    	$sm = $e->getApplication()->getServiceManager();
    	$logger = $sm->get('Log\App');
    	if ($e->getParam('exception')){
    		$exception = $e->getParam('exception');
    		$logger->crit($exception->getMessage(), array($exception));
    		return;
    	}
    
    	if($e->getError() === \Zend\Mvc\Application::ERROR_ROUTER_NO_MATCH) {
    		$logger->notice('Response Code 404 '.$e->getRequest()->getUriString());
    		return;
    	}
    
    	$logger->crit('Not defined exception '.$e->getName().' '.$e->getError(), $e->getParams());
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
	        'Zend\Loader\ClassMapAutoloader' => array(
	        	__DIR__ . '/autoload_classmap.php',
	        ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
