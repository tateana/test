<?php
namespace Application\Contact;

use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;

use Zend\Form;
use Zend\Form\Element;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator;
use Zend\Filter;

class FormFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Zend\Form\Form
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $form = new Form\Form('contact-us');
        $form->setAttribute('method', 'post');
        
        $form->setHydrator(new Hydrator(false));

        $field = new Element\Text('name');
        $field->setLabel('Name');
        $form->add($field);
              
        $field = new Element\Email('email');
        $field->setLabel('Email');
        $form->add($field);
        
        $field = new Element\Text('subject');
        $field->setLabel('Subject');
        $form->add($field);
        
        $field = new Element\Textarea('message');
        $field->setLabel('Message');
        $form->add($field);
        
        $field = new Element\Submit('submit');
        $field->setValue('Send');
        $form->add($field);
        
        //////////////// add validators and filters //////////
        
        /*@var $inputFilter  \Zend\InputFilter\InputFilter */
        $inputFilter = $form->getInputFilter();
        $inputs = $inputFilter->getInputs();
        
        $this->setupName($form->get('name'), $inputs['name']);
        $this->setupEmail($form->get('email'), $inputs['email']);
        $this->setupSubject($form->get('subject'), $inputs['subject']);
        $this->setupMessage($form->get('message'), $inputs['message']);
    	return $form;
    }
    
    /**
    * @param \Zend\Form\Element $field
    * @param  \Zend\InputFilter\Input $input
    */
    protected function setupName($field, $input) {
    	$input->getFilterChain()->attach(new \Zend\I18n\Filter\Alpha(true));
    	$this->setupAsRequired($field, $input);
    	$this->setupLengthCheck($field, $input);
    }
    
    /**
     * @param \Zend\Form\Element $field
     * @param  \Zend\InputFilter\Input $input
     */
    protected function setupEmail($field, $input) {
    	$this->setupAsRequired($field, $input);
    	$field->setAttribute('data-bv-emailaddress', 'true');
    }
    
    /**
    * @param \Zend\Form\Element $field
    * @param  \Zend\InputFilter\Input $input
    */
    protected function setupSubject($field, $input) {
    	$this->setupAsRequired($field, $input);
    	$this->setupLengthCheck($field, $input);
    	$this->setupHtmlTextFilter($field, $input);
    }
    
    /**
    * @param \Zend\Form\Element $field
    * @param  \Zend\InputFilter\Input $input
    */
    protected function setupMessage($field, $input) {
    	$this->setupHtmlTextFilter($field, $input);
    }
    
    /**
    * @param \Zend\Form\Element $field
    * @param  \Zend\InputFilter\Input $input
    */
    protected function setupAsRequired($field, $input) {
    	$field->setAttribute('required', 'required');
    	$input->setAllowEmpty(false);
    	$input->setRequired(true);
    }
    
    /**
     * @param \Zend\Form\Element $field
     * @param  \Zend\InputFilter\Input $input
     */
    protected function setupLengthCheck($field, $input) {
    	$minlength = 3;
    	$maxlength = 50;
    	 
    	static $validator = null;
    	if($validator == null) {
    		$validator = new Validator\StringLength(
    		array(
    	    	        'min' => $minlength, 
    	    	        'max' => $maxlength
    		)
    		);
    	}
    	$field->setAttribute('data-bv-stringlength', 'true');
    	$field->setAttribute('maxlength', $maxlength);
    	$field->setAttribute('data-bv-stringlength-min', $minlength);
    	$input->getValidatorChain()->attach($validator);
    }
    
    /**
    * @param \Zend\Form\Element $field
    * @param  \Zend\InputFilter\Input $input
    */
    protected function setupHtmlTextFilter($field, $input) {
    	static $trimFilter = null;
    	if($trimFilter == null) {
    		$trimFilter = new Filter\StringTrim();
    	}
    	
    	static $htmlFilter = null;
    	if($htmlFilter == null) {
    		$htmlFilter = new Filter\HtmlEntities();
    	}
    	
    	$input->getFilterChain()->attach($trimFilter);
    	$input->getFilterChain()->attach($htmlFilter);
    }
    
}