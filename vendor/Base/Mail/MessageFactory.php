<?php
namespace Base\Mail;

use \Zend\View\Model\ViewModel;
use \Zend\Mail\Message;
use \Zend\Mime\Part as MimePart;
use \Zend\Mime\Message as MimeMessage;

class MessageFactory {

    /**
     * @var \Zend\View\Renderer\RendererInterface
     */
    protected $viewRenderer;
    protected $config;
    
    public function __construct($viewRenderer, $config) {
        $this->viewRenderer = $viewRenderer;
        $this->config = $config;
  
    }
    
    public function createMessage($template, $vars = array()) {

        $viewModel = new ViewModel($vars);
        $viewModel->setTemplate($template);
        
        $htmlContent = $this->viewRenderer->render($viewModel);
        
        $layout = new ViewModel();

        $textContent = filter_var($htmlContent, FILTER_SANITIZE_STRING);
        $text = new MimePart($textContent);
        $text->type = "text/plain";

        $layout->setTemplate('layout/email');
        $layout->content = $htmlContent;
        
        $htmlContent = $this->viewRenderer->render($layout);
        $html = new MimePart($htmlContent);
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->setParts(array($text, $html));
        
        $message = new Message();
        $message->setEncoding("UTF-8");
        $message->setBody($body);
        $message->setFrom($this->config['from']);
        $message->setTo($this->config['to']);
        $message->setSubject($this->config['subject']);
        $contentTypeHeader = $message->getHeaders()->get('ContentType');
        $contentTypeHeader->setType('multipart/alternative');

        return $message;
    }
}