<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'invokables' => array(
        	'Application\Contact\Dao' => 'Application\Contact\Dao'
        ),
        'factories' => array(
        	'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
            'Application\Contact\Form' => 'Application\Contact\FormFactory',
        	'Base\Mail\Transport' => 'Base\Mail\TransportFactory',
        	'Base\Mail\MessageFactory' => function ($sm) {
		        $configs = $sm->get('Configuration');
		        $config = $configs['mail'];
		        return new Base\Mail\MessageFactory($sm->get('ViewRenderer'), $configs['mail']);
	        },
	        'Application\Contact\EmailNotificator'  => function ($sm) {
		        $configs = $sm->get('Configuration');
		        $config = $configs['contact-form-email'];
		        return new Application\Contact\EmailNotificator($sm->get('Base\Mail\MessageFactory'), $sm->get('Base\Mail\Transport'), $configs['contact-form-email']);
	        },
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
            'dbAdapter' => 'Zend\Db\Adapter\Adapter'
        ),
        'initializers' => array(
            function ($instance, $sm) {
            	if ($instance instanceof Zend\Db\Adapter\AdapterAwareInterface) {
            		$instance->setDbAdapter($sm->get('dbAdapter'));
            	}
            }
        ),
        'shared' => array(
        	'Application\Contact\Form' => false,
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => false,
        'display_exceptions'       => false,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'view_helpers' => array(
		'invokables' => array(
            'bootstrapFormValidator' => 'Base\View\Helper\BootstrapFormValidator',
    	),        
    ),
);
