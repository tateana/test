<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $viewModel = new ViewModel();

        $services = $this->getServiceLocator();
        $form = $services->get('Application\Contact\Form');
        $viewModel->form = $form;
        
        if($this->getRequest()->isPost()) {
        	$form->setData($this->getRequest()->getPost());
        	
        	if ($form->isValid()) {
        		$dao = $services->get('Application\Contact\Dao');
        		$contact = $dao->getNewEntity();
        		$form->bind($contact)->bindValues();
        		$dao->insert($contact);
        		$services->get('Application\Contact\EmailNotificator')->notify($contact);
        		
        		$this->flashMessenger()->addSuccessMessage("Your message was sent successfully. Thank you.");
        		return $this->redirect()->toRoute('home');
        	}         	
        }
        
        return $viewModel;
    }
}
