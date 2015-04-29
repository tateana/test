<?php
namespace Base\View\Helper;

/**
 * Add bootstrap form validator to use with declarations in html attributes
 * http://bootstrapvalidator.com
 */
class BootstrapFormValidator extends \Zend\View\Helper\AbstractHelper
{
    public function __invoke() {
        $this->getView()->headScript()->appendFile('//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.js');
        $this->getView()->headLink()->prependStylesheet('//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/css/bootstrapValidator.min.css');
        $inline =  <<<'JS'
$(function(){
   $('form') 
    .bootstrapValidator({})
    .on('status.field.bv', function(e, data) {
    	if(data.status == 'NOT_VALIDATED') {
        	$(data.element.next(".php-error").hide());
        }
     });
});
JS;
        $this->getView()->inlineScript()->appendScript($inline);
    }
}