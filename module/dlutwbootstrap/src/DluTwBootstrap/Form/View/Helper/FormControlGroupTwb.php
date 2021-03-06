<?php
namespace DluTwBootstrap\Form\View\Helper;

use Zend\Form\Element\Radio;

use Zend\Form\Element\Checkbox;

use Zend\Form\View\Helper\AbstractHelper as AbstractViewHelper;
use \Zend\Form\ElementInterface;

/**
 * FormControlGroupTwb
 * @package DluTwBootstrap
 * @copyright David Lukas (c) - http://www.zfdaily.com
 * @license http://www.zfdaily.com/code/license New BSD License
 * @link http://www.zfdaily.com
 * @link https://bitbucket.org/dlu/dlutwbootstrap
 */
class FormControlGroupTwb extends AbstractViewHelper
{
    /* **************************** METHODS ****************************** */

    /**
     * Renders the control group div tag
     * @param  ElementInterface $element
     * @param string $content
     * @return string
     */
    public function render(ElementInterface $element, $content) {
        $html   = $this->openTag($element);
        $html   .= "\n" . $content;
        $html   .= "\n" . $this->closeTag();
        return $html;
    }

    /**
     * Returns the control group open tag
     * @param ElementInterface $element
     * @return string
     */
    public function openTag(ElementInterface $element, $formType) {
        $class = 'form-group';

        $id     = 'cgroup-' . $element->getName();
        if($element->getMessages()) {
            $class  .= ' has-error';
        }
        $html   = sprintf('<div class="%s" id="%s">', $class, $id);
        return $html;
    }

    /**
     * Returns the control group closing tag
     * @return string
     */
    public function closeTag() {
        return '</div>';
    }

    /**
     * Invoke helper as function
     * Proxies to {@link render()}.
     * @param  ElementInterface $element
     * @param string $content
     * @return string
     */
    public function __invoke(ElementInterface $element = null, $content = null) {
        if(is_null($element)) {
            return $this;
        } else {
            return $this->render($element, $content);
        }
    }
}