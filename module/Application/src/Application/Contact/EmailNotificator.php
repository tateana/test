<?php
namespace Application\Contact;


class EmailNotificator  {
    
	/**
	 * @var \Base\Mail\MessageFactory
	 */
	protected $messageFactory;
	
	/**
	 * @var \Zend\Mail\Transport\TransportInterface
	 */
	protected $transport;
	
	protected $options;
	
	public function __construct($messageFactory, $transport, $options) {
		$this->messageFactory = $messageFactory;
		$this->transport = $transport;
		$this->options = $options;
	}
	
	/**
	 * @param Contact\Entity $contact
	 */
	public function notify($contact) {
		$email = $this->messageFactory->createMessage('application/mail/contact/admin-message.phtml', array('contact' => $contact));
		$email->addTo($this->options['to']);
		$email->setSubject($this->options['subject']);
		$this->transport->send($email);
		return $email;
	}

    
}