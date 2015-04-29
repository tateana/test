<?php
namespace DluTwBootstrap\Form\View\Helper;

use DluTwBootstrap\GenUtil;

use Zend\Form\View\Helper\AbstractHelper;
use Zend\Form\View\Helper\FormLabel;
use Zend\Form\ElementInterface;
use Zend\Form\Exception\DomainException;

/**
 * FormLabelTwb
 * @package DluTwBootstrap
 * @copyright David Lukas (c) - http://www.zfdaily.com
 * @license http://www.zfdaily.com/code/license New BSD License
 * @link http://www.zfdaily.com
 * @link https://bitbucket.org/dlu/dlutwbootstrap
 */
class FormLabelTwb extends AbstractHelper
{
    /**
     * @var GenUtil
     */
    protected $genUtil;

    /**
     * ZF2 label helper
     * @var FormLabel
     */
    protected $formLabelHelper;

    /**
     * Constructor
     * @param \Zend\Form\View\Helper\FormLabel $formLabelHelper
     * @param \DluTwBootstrap\GenUtil $genUtil
     */
    public function __construct(FormLabel $formLabelHelper, GenUtil $genUtil)
    {
        $this->formLabelHelper  = $formLabelHelper;
        $this->genUtil          = $genUtil;
    }

    /**
     * Renders a form element label from an element
     * @param \Zend\Form\ElementInterface $element
     * @param array|null $displayOptions
     * @return string
     * @throws \Zend\Form\Exception\DomainException
     */
    public function render(ElementInterface $element, array $displayOptions = array())
    {
        $labelContent   = $element->getLabel();
        if (empty($labelContent)) {
            throw new DomainException(sprintf('%s: No label set in the element.', __METHOD__));
        }
        //Translate
        if (null !== ($translator = $this->getTranslator())) {
            $labelContent = $translator->translate($labelContent, $this->getTranslatorTextDomain());
        }

        //Label attributes
        $labelAttributes = $element->getLabelAttributes();
        if (empty($labelAttributes)) {
            $labelAttributes = array();
        }
        
        $labelAttributes    = $this->genUtil->addWordsToArrayItem('control-label', $labelAttributes, 'class');
        
        if(isset($displayOptions['formType']) && $displayOptions['formType'] == \DluTwBootstrap\Form\FormUtil::FORM_TYPE_HORIZONTAL) {
            $labelAttributes    = $this->genUtil->addWordsToArrayItem('col-sm-4', $labelAttributes, 'class');
        }
        
        if ($this->isRequired($element, $displayOptions)) {
            $labelAttributes    = $this->genUtil->addWordsToArrayItem('required', $labelAttributes, 'class');
        }
        
        if (!array_key_exists('id', $labelAttributes)) {
            $labelAttributes['id']  = 'label-' . $element->getName();
        }
        $element->setLabelAttributes($labelAttributes);
        $formLabelHelper    = $this->formLabelHelper;
        return $formLabelHelper($element, $labelContent);
    }

    /**
     * Generate a form label from an element
     * @param  ElementInterface $element
     * @param array|null $displayOptions
     * @throws \Zend\Form\Exception\DomainException
     * @return string|FormLabelTwb
     */
    public function __invoke(ElementInterface $element = null, array $displayOptions = array())
    {
        if (!$element) {
            return $this;
        }
        return $this->render($element, $displayOptions);
    }
    
    protected function isRequired(ElementInterface $element, $displayOptions) {
    	if(array_key_exists('required', $displayOptions) && $displayOptions['required']) {
    		return true;
    	}
    	
    	if($element->hasAttribute('required') && $element->getAttribute('required')) {
    		return true;
    	}
    	
    	return false;
    }
}