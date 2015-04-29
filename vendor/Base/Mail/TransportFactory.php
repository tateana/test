<?php
namespace Base\Mail;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class TransportFactory implements FactoryInterface {

	/**
	 * @param ServiceLocatorInterface $serviceLocator
	 * @return \Zend\Mail\Transport\TransportInterface
	 */
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$configs = $serviceLocator->get('Configuration');
		$config = $configs['mail'];
		$transport = null;
		
		switch ($config['transport']) {
			case 'file':
				$options = new \Zend\Mail\Transport\FileOptions($config['transport_options']);
				$transport = new \Zend\Mail\Transport\File($options);
				break;
			case 'smtp':
				$options = new \Zend\Mail\Transport\SmtpOptions($config['transport_options']);
				$transport = new \Zend\Mail\Transport\Smtp($options);
				break;
			default:
				$transport = new \Zend\Mail\Transport\Sendmail();
			
		}

		return $transport;
	}
}