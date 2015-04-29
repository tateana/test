<?php
namespace DluTwBootstrap\Form\View\Helper;

use \Zend\Form\ElementInterface;
use Zend\Form\View\Helper\AbstractHelper as AbstractFormViewHelper;

/**
 * FormHelpTwb
 * @package DluTwBootstrap
 * @copyright David Lukas (c) - http://www.zfdaily.com
 * @license http://www.zfdaily.com/code/license New BSD License
 * @link http://www.zfdaily.com
 * @link https://bitbucket.org/dlu/dlutwbootstrap
 */
class FormHelpTwb extends AbstractFormViewHelper {
    /**
     * Which element types support the help?
     * @var array
     */
    protected $supportedTypes = array(
        'text',
        'password',
        'textarea',
        'checkbox',
        'radio',
        'select',
        'file',
    );

    /* **************************** METHODS ****************************** */

    /**
     * Render a help from the provided $element
     * @param  ElementInterface $element
     * @return string
     */
    public function render(ElementInterface $element) {
        $type = $element->getAttribute('type');
        if (!in_array($type, $this->supportedTypes)) {
            return '';
        }
        

        
        $html = '';
        //Description
        if ($element->getOption('help')) {
            
            //Translate
            if (null !== ($translator = $this->getTranslator())) {
                $helpContent = $translator->translate($element->getOption('help'), $this->getTranslatorTextDomain());
            }
            
            $id = $element->getName();
            $html = sprintf(
                '<span id = "helper-%s" 
					class="glyphicon glyphicon-question-sign element-help" 
					style= "position:absolute;
							left: -10px;
							top: 10px;
							cursor: pointer;" 
					data-content="' . $helpContent . '">
				</span>', $id);
        }
        return $html;
    }

    /**
     * Invoke helper as function
     * Proxies to {@link render()}.
     * @param  ElementInterface $element
     * @return string
     */
    public function __invoke(ElementInterface $element) {
        return $this->render($element);
    }
}
